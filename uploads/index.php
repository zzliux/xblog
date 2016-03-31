<?php require_once(dirname(__FILE__).'/../functions/common.php');?>
<?php  blog_userPage(); ?>
<?php require_once(dirname(__FILE__).'/../templates/default/component/editorHeader.php'); ?>
<style>
	.ul_pics li .img{display:inline-block;width:160px;height:160px;border:1px solid #ddd;padding:2px;text-align: center;margin:0 5px 5px 0;}
	.ul_pics li {display:inline-block;width:160px;height:160px;text-align: center;font-size: x-small}
	.ul_pics li img{max-width: 160px;max-height: 140px;vertical-align: middle;}
	.progress{position:relative;padding: 1px; border-radius:3px; margin:60px 0 0 0;}
	.bar {background-color: green; display:block; width:0%; height:30px; border-radius:3px; }
	.percent{position:absolute; height:20px; display:inline-block;top:3px; left:2%; color:#fff }
</style>
<body>
	<div class="head">
		<div class="head_inner">
		</a>
	</div>

</div>
<div class="container">
	<h2 class="title">图片上传</h2>
	<div class="post">
		<button class="editor-btn-default" id="uploadBtn">上传图片</button>
		最大3MB，支持jpg，gif，png格式。
		<ul id="ul_pics" class="ul_pics clearfix"></ul>
		<div class="imgContainner" id="imgc"></div>
	</div>
</div>
<script type="text/javascript" src="plupload/plupload.full.min.js"></script>
<script type="text/javascript">
var uploader = new plupload.Uploader({//创建实例的构造方法
	runtimes: 'html5,flash,silverlight,html4', //上传插件初始化选用那种方式的优先级顺序
	browse_button: 'uploadBtn', // 上传按钮
	url: "ajax.php", //远程上传地址
	flash_swf_url: 'plupload/Moxie.swf', //flash文件地址
	silverlight_xap_url: 'plupload/Moxie.xap', //silverlight文件地址
	filters: {
		max_file_size: '3mb', //最大上传文件大小（格式100b, 10kb, 10mb, 1gb）
		mime_types: [//允许文件上传类型
			{title: "files", extensions: "jpg,png,gif"}
		]
	},
	multi_selection: true, //true:ctrl多文件上传, false 单文件上传
	init: {
		FilesAdded: function(up, files) { //文件上传前
			if ($("#ul_pics").children("li").length > 30) {
				alert("您上传的图片太多了！");
				uploader.destroy();
			} else {
				var li = '';
				plupload.each(files, function(file) { //遍历文件
					li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>";
				});
				$("#ul_pics").append(li);
				uploader.start();
			}
		},
		UploadProgress: function(up, file) { //上传中，显示进度条
			var percent = file.percent;
			$("#" + file.id).find('.bar').css({"width": percent + "%"});
			$("#" + file.id).find(".percent").text(percent + "%");
		},
		FileUploaded: function(up, file, info) { //文件上传成功的时候触发
			var data = eval("(" + info.response + ")");
			$("#" + file.id).html("<div class='img'><img src='" + data.pic + "'/></div><input style=\"max-width:139px;\" type=\"text\"value=\"" + data.pic + "\">");
		},
		Error: function(up, err) { //上传出错的时候触发
			alert(err.message);
		}
	}
});
uploader.init();
var imgObj = <?php blog_listImageJson() ?>;
var outImg = '';
for(var k in imgObj){
	outImg += '<div class="imgs" style="margin:10px 10px 0px 10px;display:inline-block;"><img src="' + imgObj[k].thumbUrl + '" style="cursor:pointer;"><input value="' + imgObj[k].thumbUrl + '"></div>'
}
document.getElementById('imgc').innerHTML = outImg;
</script>
<script type="text/javascript" src="../templates/default/js/thumb2full.js"></script>
</body>
</html>
