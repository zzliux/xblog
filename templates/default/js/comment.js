loadComments();
document.getElementsByTagName('textarea')[0].addEventListener('input',checkContentLength,false);
var nb = document.getElementById('name');
var ub = document.getElementById('url');
nb.addEventListener('blur',function(){
	var xhrt = new XMLHttpRequest();
	xhrt.open('POST','../functions/ajax.php?f=userInfoJson',true);
	xhrt.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhrt.send('un='+nb.value);
	xhrt.onreadystatechange = function(){
		if(xhrt.readyState == 4 && xhrt.status == 200){
			var obj = eval('('+xhrt.responseText+')');
			if(obj != null){
				if(obj.url != ''){
					ub.value = obj.url;
				}
			}
		}
	}
},false);
function checkForm(){
	var cid = location.href.match(/article\/(?:\?cid=)?(\d+)/)[1];
	var name = document.getElementById('name');
	var email = document.getElementById('email');
	var url = document.getElementById('url');
	var content = document.getElementById('content');
	var parent = document.getElementById('parent');
	var msg = document.getElementsByClassName('msg')[0];
	var btnArr = document.getElementsByClassName('editor-btn-default');
	for(var i=0;i<btnArr.length;i++)
		btnArr[i].disabled = true;
	if(name.value==''||email.value==''||content.value==''){
		msg.innerHTML='<font color="red"><strong>名字,邮箱,评论内容不能为空</strong></font>';
		for(var i=0;i<btnArr.length;i++)
			btnArr[i].disabled = false;
		return false;
	}
	if(!checkEmail(email.value)){
		msg.innerHTML='<font color="red"><strong>邮箱格式错误</strong></font>';
		for(var i=0;i<btnArr.length;i++)
			btnArr[i].disabled = false;
		return false;
	}
	if(content.value.length>140){
		msg.innerHTML='<font color="red"><strong>内容过长</strong></font>';
		for(var i=0;i<btnArr.length;i++)
			btnArr[i].disabled = false;
		return false;
	}
	if(name.value.length>10){
		msg.innerHTML='<font color="red"><strong>名字过长</strong></font>';
		for(var i=0;i<btnArr.length;i++)
			btnArr[i].disabled = false;
		return false;
	}
	for(var i=0;i<btnArr.length;i++)
		btnArr[i].innerHTML = '<i class="fa fa-circle-o-notch fa-spin"></i>';
	_csrf();
	var xhr = new XMLHttpRequest();
	xhr.open('POST','../functions/ajax.php?f=blog_insertComment&cid='+cid,true);
	xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	xhr.send('name='+name.value+'&email='+email.value+'&url='+url.value+'&content='+content.value+'&parent='+parent.value);
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status ==200){
			var re = eval('('+xhr.responseText+')');
			if(re.err){
				msg.innerHTML = re.msg;

			}else{
				window.location = location.href;
			}
		}
	}
	return false;
}

function loadComments(){
	var commentBox = document.getElementsByClassName('comment-box')[0];
	var reg = /article\/(?:\?cid=)?(\d+)/;
	var cid = reg.exec(location.href)[1];
	commentBox.innerHTML = '<div class="post">加载中 <i class="fa fa-circle-o-notch fa-spin"></i></div>';
	var xhr = new XMLHttpRequest();
	xhr.open('GET','../functions/ajax.php?f=blog_commentsJson&cid='+cid,true);
	xhr.send();
	xhr.onreadystatechange = function(){
		if(xhr.readyState==4 && xhr.status==200){
			commentBox.innerHTML = '';
			if(xhr.responseText == 'null'){
				commentBox.innerHTML += '<div class="post">　　还没有评论哦~沙发就在这里等着~</div>';
			}else{
				var re = eval('('+xhr.responseText+')');
				for(var i=0;i<re.length;i++){
					if(re[i]['url']) re[i]['name'] = "<a href=\""+re[i]['url']+"\" target=\"_blank\">"+re[i]['name']+"</a>";
					var detail = "| "+re[i]['name']+" | "+re[i]['date']+" |";
					var content = re[i]['content'];
					if(re[i]['parent']){
						document.getElementById('replyInReply'+re[i]['parent']).innerHTML += "<div class=\"post\"><div class=\"comment-detail\">"+detail+"</div><div class=\"comment-content\"><div class=\"replyInReply\" id=\"replyInReply"+re[i]['coid']+"\"><div class=\"cont\">"+content+"</div></div></div><div class=\"replyBox "+re[i]['coid']+"\"><a onclick=\"setReplyBox("+re[i]['coid']+")\">回复ta</a></div></div>";

					}else{
						commentBox.innerHTML += "<div class=\"post\"><div class=\"comment-detail\">"+detail+"</div><div class=\"comment-content\"><div class=\"replyInReply\" id=\"replyInReply"+re[i]['coid']+"\"><div class=\"cont\">"+content+"</div></div></div><div class=\"replyBox "+re[i]['coid']+"\"><a onclick=\"setReplyBox("+re[i]['coid']+")\">回复ta</a></div></div>";
					}
				}
			}
			blockReplyBox();
		}
	}
}
function setReplyBox(coid){
	var out = '<div class="comment-editor-box" style="max-width:500px;margin-top:20px;text-align:left;"><form method="post" action="" onsubmit="return checkForm()" id="fm"><input type="hidden" name="parent" id="parent" value="'+coid+'"><input type="text" name="name" id="name" placeholder="名字" style="max-width:150px" value="'+uName+'"><input type="text" name="email" id="email" placeholder="Email" value="'+uEmail+'"><input type="text" name="url" id="url" placeholder="站点(选填)" value="'+uUrl+'"><textarea type="text" placeholder="评论内容" id="content" name="content"></textarea><div class="msg"><?php if($out) echo $out; ?></div><button type="submit" name="sub" class="editor-btn-default">提交评论</button><a style="margin-left:30px;" onclick="undoReplyBox('+coid+')">取消</a><div id="lb"><span style="color:#44AF00">0</span>/140</div></form></div>';
	document.getElementsByClassName('replyBox '+coid)[0].innerHTML = out;
	document.getElementsByTagName('textarea')[0].addEventListener('input',checkContentLength,false);
}
function undoReplyBox(coid){
	var out = "<a onclick=\"setReplyBox("+coid+")\">回复ta</a>";
	document.getElementsByClassName('replyBox '+coid)[0].innerHTML = out;
}
function checkEmail(email){
	var reg = /[\w.-_]+@\w+\.\w+/;
	return reg.test(email);
}
function blockReplyBox(){
	var postArr = document.getElementsByClassName('comment-box')[0].childNodes;
	for(var i=0;i<postArr.length;i++){
		f(postArr[i],0);
	}
	function f(node,depth){
		if(typeof(node) == "undefined" || node.className != 'post') return;
 		var pArr = node.childNodes[1].childNodes;
 		for(var i=0;i<pArr.length;i++){
			for(var j=0;j<pArr[i].childNodes.length;j++){
				if(pArr[i].childNodes[j].className=='post' && pArr[i].childNodes[j].childNodes[2].innerHTML !==''){
					if(depth>2){
						pArr[i].childNodes[j].childNodes[2].innerHTML = '';
					}
					f(pArr[i].childNodes[j],depth+1);
				}
			}
 		}
	}
}
function checkContentLength(){
	var a = document.getElementsByTagName('textarea')[0];
	var b = document.getElementById('lb');
	var c = a.value.length;
	if(c>140) c = '<span style="color:#f00">'+ c +'</span>';
	else c = '<span style="color:#44AF00">' + c + '</span>';
	b.innerHTML = c + '/140';
}
function _csrf(){
	var exp = new Date();
	exp.setTime(exp.getTime()+1000);
	document.cookie="postTime="+(new Date()).getTime().toString().substr(0,10)+";expires="+exp.toGMTString()+";path=/";
}