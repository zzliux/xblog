<?php require_once(dirname(__FILE__).'/../../functions/common.php') ?>
<?php require_once(dirname(__FILE__).'/component/articleHeader.php') ?>
	<div class="nav">
		<div class="blog-b"><a href="<?php blog_siteLink() ?>">Blog</a></div>
		<div class="blog-b">|</div>
		<div class="about-b"><a href="<?php blog_siteLink() ?>article/9">About Me</a></div>
		<div class="blog-b">|</div>
		<div class="blog-b">
			<form action="../search/" method="get">
				<input type="text" id="searchBox" name="sub" style="display:inline-block;height:13px;font-size:15px;vertical-align:top;">
				<input class="btn" type="submit" style="display:inline-block;height:27px;vertical-align:top;line-height:1" value="搜索">
			</form>
		</div>
	</div>
	<div class="container-article">
		<div class="article-nav">文章:</div>
		<?php blog_article($cid,!$_GET['viewDraft']) ?>
	</div>
	<?php require_once(dirname(__FILE__).'/component/comment.php') ?>
	<script type="text/javascript" src="../plugins/highlightjs/highlight.pack.js"></script>
	<script type="text/javascript" src="../templates/default/js/article.js"></script>
	<script type="text/javascript" src="../templates/default/js/thumb2full.js"></script>
<?php blog_footer() ?>