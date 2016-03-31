<?php require_once(dirname(__FILE__).'/../functions/common.php') ?>

<?php
	session_start();
	require_once(dirname(__FILE__).'/../class/database.class.php');

	if(isset($_POST['subBtn'])){
		$db = new database();
		$_SESSION['uid'] = $db->checkPassword($_POST['email'],$_POST['password']);
	}else{
		$_SESSION['uid'] = FALSE;
	}
	if($_SESSION['uid']!==FALSE){
		header('Location:'.(new database())->getOption('blog_siteLink').'user/');
	}
?>

<?php blog_header() ?>

<body>
<div class="loginBox">
	<span>Sign in to xblog</span>
	<form method="post">
		<div>
			<ul>Email:
			<input type="text" name="email" value="" style="max-width:270px;">
			</ul>
		</div>
		<div>
			<ul>Password:
			<input type="password" name="password" value="" style="max-width:270px;">
			</ul>
		</div>
		<ul><input type="submit" name="subBtn" value="Sign in" style="max-width:290px;"></ul>
	</form>
</div>
</body>

<?php blog_footer() ?>