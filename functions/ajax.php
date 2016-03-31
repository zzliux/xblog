<?php
require_once(dirname(__FILE__).'/common.php');
if(!$_SERVER['HTTP_REFERER']){
	die('Request Error');
}

if($_GET['f'] === 'blog_articlesJson'){
	$s = $_GET['s'];
	$l = $_GET['l'];
	blog_articlesJson($s,$l);
}else if($_GET['f'] === 'blog_commentsJson' && isset($_GET['cid'])){
	$cid = $_GET['cid'];
	blog_commentsJson($cid);
}else if($_GET['f'] === 'blog_searchByTagJson' && isset($_GET['tag'])){
	$tag = $_GET['tag'];
	blog_searchByTagJson($tag);
}else if($_GET['f'] === 'blog_searchByCategoryJson' && isset($_GET['category'])){
	$category = $_GET['category'];
	blog_searchByCategoryJson($category);
}else if($_GET['f'] === 'blog_searchByAuthorJson'){
	$author = $_GET['author'];
	blog_searchByAuthorJson($author);
}else if($_GET['f'] === 'blog_searchJson' && isset($_GET['s'])){
	$s = $_GET['s'];
	blog_searchJson($s);
}else if($_GET['f'] === 'blog_tagsJson'){
	blog_tagsJson();
}else if($_GET['f'] === 'blog_categoriesJson'){
	blog_categoriesJson();
}else if($_GET['f'] === 'blog_insertComment'){
	if(abs(time()-$_COOKIE['postTime']) < 60)
		echo blog_insertComment();
	else{
		echo json_encode(array(
			'err' => '1',
			'msg' => '<span style="color:red;"><strong>request error</strong></span>'
		));
	}
}else if($_GET['f'] === 'userInfoJson'){
	userInfoJson($_POST['un']);
}