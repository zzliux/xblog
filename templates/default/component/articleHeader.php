<?php
require_once(dirname(__FILE__).'/../../../functions/common.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" >
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<meta name="keywords" content="<?php blog_articleKeywords($_GET['cid']); ?>">
	<title><?php blog_articleTitle($_GET['cid']) ?> | <?php blog_title() ?></title>
	<link rel="stylesheet" href='<?php blog_siteLink(); ?>templates/default/css/default.css'>
	<link rel="stylesheet" href="//cdn.bootcss.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?php blog_siteLink() ?>plugins/highlightjs/styles/railscasts.css">
</head>
<body>
<header>
	<h1><a href="<?php blog_siteLink() ?>"><?php blog_title() ?></a> | <?php blog_description() ?></h1>
</header>