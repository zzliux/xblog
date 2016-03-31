<?php require_once(dirname(__FILE__).'/../functions/common.php'); ?>
<?php
	if($_GET['sub']){
		$arr = explode(':', $_GET['sub']);
		if($arr[0]=='tag' || $arr[0]=='category' || $arr[0]=='author'){
			header('Location:'.(new database)->getOption('blog_siteLink').'search/?'.$arr[0].'='.$arr[1]);
		}else{
			header('Location:'.(new database)->getOption('blog_siteLink').'search/?s='.$arr[0]);
		}
	}
?>
<?php blog_header(); ?>
	<div class="container">
		<div class="nav">
			<div class="blog-b"><a href="<?php blog_siteLink() ?>">Blog</a></div>
			<div class="blog-b">|</div>
			<div class="about-b"><a href="<?php blog_siteLink() ?>article/9">About Me</a></div>
			<div class="blog-b">|</div>
			<div class="blog-b">
				<form action="" method="get">
					<input type="text" id="searchBox" name="sub" style="display:inline-block;height:13px;font-size:15px;vertical-align:top;">
					<input class="btn" type="submit" style="display:inline-block;height:27px;vertical-align:top;line-height:1" value="搜索">
				</form>
			</div>
		</div>
		<div class="postFields">
			<div id="postBox">
				加载中 <i class="fa fa-circle-o-notch fa-spin"></i>
			</div>
		</div>
		<div>
		</div>
	</div>
<script type="text/javascript" src="../templates/default/js/search.js"></script>
<?php blog_footer(); ?>