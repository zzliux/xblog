<?php require_once(dirname(__FILE__).'/../functions/common.php');?>
<?php  blog_userPage(); ?>
<?php
$typeArr = array("jpg", "png", "gif");//允许上传文件格式
$path = "images/";//上传路径

if (isset($_POST)) {
	$name = $_FILES['file']['name'];
	$size = $_FILES['file']['size'];
	$name_tmp = $_FILES['file']['tmp_name'];
	if (empty($name)) {
		echo json_encode(array("error"=>"您还未选择图片"));
		exit;
	}
	$type = strtolower(substr(strrchr($name, '.'), 1)); //获取文件类型

	if (!in_array($type, $typeArr)) {
		echo json_encode(array("error"=>"请上传jpg,png或gif类型的图片！"));
		exit;
	}
	if ($size > (3 * 1024 * 1024)) {
		echo json_encode(array("error"=>"图片大小已超过3MB！"));
		exit;
	}

	$pic_name = $name;//图片名称
	$pic_url = $path . $pic_name;//上传后图片路径+名称
	$pic_thumb_url = $path . 'thumb/' . $pic_name;
	if (move_uploaded_file($name_tmp, $pic_url)) { //临时文件转移到目标文件夹
		require_once(dirname(__FILE__).'/../class/resizeimage.class.php');
		new resizeimage($pic_url, '200', '200', '0', dirname(__FILE__).'/images/thumb/'.$pic_name);
		echo json_encode(array("error"=>"0","pic"=>((new Database())->getOption('blog_siteLink')).'uploads/'.$pic_thumb_url,"name"=>$pic_name));
	} else {
		echo json_encode(array("error"=>"上传有误，请检查服务器配置！"));
	}
}

?>