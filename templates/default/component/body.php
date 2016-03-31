	<div class="container">
		<div class="nav">
			<div class="blog-b"><a href="<?php echo $db->getOption('blog_siteLink') ?>">Blog</a></div>
			<div class="blog-b">|</div>
			<div class="about-b"><a href="article/9">About Me</a></div>
			<div class="blog-b">|</div>
			<div class="blog-b">
				<form action="./search/" method="get">
					<input type="text" id="searchBox" name="sub" style="display:inline-block;height:13px;font-size:15px;vertical-align:top;">
					<input class="btn" type="submit" style="display:inline-block;height:27px;vertical-align:top;line-height:1" value="搜索">
				</form>
			</div>
		</div>
		<div class="postFields">
			<div id="postBox">
			<?php blog_listArticles() ?>
			</div>
		<div class="more">加载更多</div>
		</div>
		<?php blog_sidebar() ?>
	</div>
<script type="text/javascript" src="./templates/default/js/body.js"></script>
