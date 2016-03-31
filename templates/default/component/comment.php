<div class="container-comment">
	<div class="comment-nav">评论:</div>
	<div class="comment-box"></div>
	<div class="comment-nav">撰写评论:</div>
	<div class="comment-editor-box" style="max-width:500px;"> 
		<form method="post" action="" onsubmit="return checkForm()" id="fm">
			<input type="hidden" name="parent" id="parent" value="0">
			<input type="text" name="name" id="name" placeholder="名字" style="max-width:150px" value="<?php echo $_SESSION['name'] ?>">
			<input type="text" name="email" id="email" placeholder="Email" value="<?php echo $_SESSION['email'] ?>">
			<input type="text" name="url" id="url" placeholder="站点(选填)" value="<?php echo $_SESSION['url'] ?>">
			<textarea type="text" placeholder="评论内容" id="content" name="content"></textarea>
			<div class="msg"><?php if($out) echo $out; ?></div>
			<button type="submit" name="sub" class="editor-btn-default">提交评论</button>
			<div id="lb"><span style="color:#44AF00">0</span>/140</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	var uName = '<?php echo $_SESSION['name'] ?>';
	var uEmail = '<?php echo $_SESSION['email'] ?>';
	var uUrl = '<?php echo $_SESSION['url'] ?>';
</script>
<script type="text/javascript" src="../templates/default/js/comment.js"></script>