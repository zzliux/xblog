<?php require_once(dirname('__FILE__').'/../functions/common.php') ?>
<?php blog_userPage() ?>
<?php require_once(dirname(__FILE__).'/../templates/default/component/editorHeader.php') ?>
<div class="post">
<?php
	$db = new database();
	echo $db->getUser($_SESSION['uid']).' , 您好';
?>



<?php if($_GET['listArticles']){ ?>
<div class="user-articlesBox">
<?php blog_listUserArticles($_SESSION['uid']) ?>
</div>
<?php } ?>



<?php if($_GET['editor']){ ?>
<?php
	if(isset($_POST['editormd-markdown-doc'])){
		$_POST['content'] = $_POST['editormd-markdown-doc'];
	}
	if(isset($_GET['cid'])){
		if(isset($_POST['publish'])){
			if($_GET['cid']&&$_POST['title']&&$_POST['content']){
				$db->publishArticle($_GET['cid']);
				$published = TRUE;
			}else{
				$out = '请求非法';
			}
		}
		if(isset($_POST['draft'])){
			if($_GET['cid']&&$_POST['title']&&$_POST['content']){
				$db->updateArticle($_GET['cid'],$_POST['title'],$_POST['content'],$_POST['tags'],$_POST['categories'],0);
				$saved=TRUE;
			}else{
				$out = '请求非法';
			}
		}
		if(isset($_POST['show'])){
			echo '<script>window.open(\''.$db->getOption('blog_siteLink').'article/?cid='.$_GET['cid'].'&viewDraft=1\')</script>';
		}
		$article = (new database())->getArticle($_GET['cid'],0);

		/* 防止A用户操作B用户的文章 */
		if($article['uid'] != $_SESSION['uid']){
			header('Location:.');
		}


		if(isset($_POST['delete'])){
			$db->deleteArticle($_GET['cid']);
			header('Location:'.$db->getOption('blog_siteLink').'user/?editor=1');
		}
	}else{
		if(isset($_POST['draft'])){
			if($_POST['title']&&$_POST['content']){
				$cid = $db->insertArticle($_SESSION['uid'],$_POST['title'],$_POST['content'],$_POST['tags'],$_POST['categories'],0);
				header('Location:'.$db->getOption('blog_siteLink').'user/?editor=1&cid='.$cid);
			}else{
				$out = '请求非法';
			}
		}
	}
?>
<div class="editor">
	<form method="post">
		<div style="display:inline-block;vertical-align:top;width:100%;">
			<div class="editor-header">
				<strong>撰写文章</strong>
			</div>
			<div class="editor-title">
				<input type="text" placeholder="标题" name="title" style="width:300px" value="<?php echo $article['title'] ?>">
			</div>
			<div class="editor-content" id="editormd">
				<textarea style="display:none;"><?php echo htmlentities($article['content']) ?></textarea>
			</div>
			<div class="articleDetails">
				<div class="tags">
					标签:
					<input type="text
	" name="tags" placeholder="使用'|'分开" value="<?php echo $article['tags'] ?>">
				</div>
				<div class="categories">
					分类:
					<input type="text
	" name="categories" placeholder="使用'|'分开" value="<?php echo $article['categories'] ?>">
				</div>
			</div>
		</div>
		<div class="msg">
			<?php if($saved){ ?>
				保存成功！
			<?php } ?>
			<?php if($published){ ?>
				发布成功！
			<?php } ?>
			<?php echo $out ?>
		</div>
		<div class="editor-button">
			<button type="submit" class="editor-btn-default" name="draft" value="1">保存</button>
			<?php if($_GET['cid']){ ?>
			<a href="../article/?cid=<?php echo $_GET['cid'] ?>&viewDraft=1" target="_blank" style="color:#A9A9A9" class="editor-btn-default">预览</a>
			<button type="submit" class="editor-btn-default" name="publish" value="1">发布</button>
			<button type="submit" class="editor-btn-default" name="baiduPush" value="1">向百度推送本文章链接</button>
			<button type="submit" class="editor-btn-danger" name="delete" value="1">删除</button>
			<?php } ?>
		</div>
	</form>
</div>
<?php } ?>

<div class="msg">
	<p>
	<a href="?listArticles=1"><strong>文章列表</strong></a>
	<a href="?editor=1" style="margin-left:20px;"><strong>撰写文章</strong></a>
	<a href="../uploads/" style="margin-left:20px;" target="_blank"><strong>上传文件</strong></a></p>
</div>

</div>
<script src="../plugins/editormd/editormd.min.js"></script>
	<script type="text/javascript">
		$(function() {
			var editor = editormd("editormd", {
			path : "../plugins/editormd/lib/",
			height : 900
		});
	});
</script>
<script>
<?php if($_POST['baiduPush'] === '1'){ ?>
	var bdpr = <?php echo $db->baiduPush($_GET['cid']) ?>;
	if(bdpr.success === 1) alert("success!\nRemain:"+bdpr.remain);
	else if(bdpr.success === 0) alert("fail!");
	else if(bdpr.message) alert("fail!"+bdpr.message);
<?php } ?>
</script>

<?php blog_footer() ?>
