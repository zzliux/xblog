<?php
require_once(dirname(__FILE__).'/../../../functions/common.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<meta name="description" content="<?php blog_description() ?>">
	<title><?php blog_title() ?> | <?php blog_description() ?></title>
	<link rel="stylesheet" href='<?php blog_siteLink(); ?>templates/default/css/default.css'>
	<link rel="stylesheet" href="//cdn.bootcss.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body>
<header>
	<h1><a href="<?php blog_siteLink() ?>"><?php blog_title() ?></a> | <?php blog_description() ?></h1>
</header>