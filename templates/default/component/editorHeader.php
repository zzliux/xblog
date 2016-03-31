<?php
require_once(dirname(__FILE__).'/../../../functions/common.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php blog_title() ?> | <?php blog_description() ?></title>
	<link rel="stylesheet" href='<?php blog_siteLink()?>templates/default/css/default.css'>
	<link rel="stylesheet" href="//cdn.bootcss.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?php blog_siteLink() ?>plugins/highlightjs/styles/railscasts.css">
	<link rel="stylesheet" href="<?php blog_siteLink() ?>plugins/editormd/css/editormd.min.css" />
	<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>
<header>
	<h1><a href="<?php blog_siteLink() ?>"><?php blog_title() ?></a> | <?php blog_description() ?></h1>
</header>