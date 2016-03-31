<?php
header("Content-Type:text/html; Charset=UTF-8");
require_once(dirname(__FILE__).'/../functions/common.php');
$cid = $_GET['cid'];
$isSetArticle = blog_isSetArticle($_GET['cid']);
if($_GET['viewDraft']){
	blog_userPage();
	if($isSetArticle){
		header("Location:".(new database)->getOption('blog_siteLink')."article/".$_GET['cid']);
	}
}else if(!$isSetArticle){
	include_once(dirname(__FILE__).'/../templates/default/404.php');
	exit;
}
?>
<?php include_once(dirname(__FILE__).'/../templates/default/article.php') ?>