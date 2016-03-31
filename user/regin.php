<?php require_once(dirname(__FILE__).'/../functions/common.php') ?>

<?php
	/* 只允许让已经注册了的用户使用 */
	blog_userPage();
	require_once(dirname(__FILE__).'/../class/database.class.php');

	if(isset($_POST['subBtn'])){
		$db = new database();
		if($_POST['password'] !== $_POST['conPassword']) $out = '<span style="color:#f00;font-weight:bold;">注册失败!</span>';
		else {
			$res = $db->insertUser($_POST['userName'],$_POST['email'],$_POST['password'],$_POST['url']);
			$out = $res ? '<span style="color:#0f0;font-weight:bold">注册成功</span>' : '<span style="color:#f00;font-weight:bold;">注册失败!</span>';
		}
	}
?>

<?php blog_header() ?>

<body>
<div class="loginBox">
	<span>Reg in to xblog</span>
	<form method="post">
		<div>
			<ul>User Name:
			<input type="text" name="userName" value="" style="max-width:270px;">
			</ul>
		</div>
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
		<div>
			<ul>Confirm Password:
			<input type="password" name="conPassword" value="" style="max-width:270px;">
			</ul>
		</div>
		<div>
			<ul>Site Url:
			<input type="text" name="url" value="" style="max-width:270px;">
			</ul>
		</div>
		<ul><input type="submit" name="subBtn" value="Reg in" style="max-width:290px;"></ul>
		<?php if($out) echo $out; ?>
	</form>
</div>
</body>

<?php blog_footer() ?>