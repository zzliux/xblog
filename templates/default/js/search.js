searchArticles();
var t;
var sb = document.getElementById('searchBox');
if(t=get('tag')){
	sb.value = 'tag:'+decodeURIComponent(t);
}else if(t=get('category')){
	sb.value = 'category:'+decodeURIComponent(t);
}else if(t=get('author')){
	sb.value = 'author:'+decodeURIComponent(t);
}else if(t=get('s')){
	sb.value = decodeURIComponent(t);
}
function searchArticles(){
	var postBox = document.getElementById('postBox');
	var xhr = new XMLHttpRequest();
	var getUrl = '../functions/ajax.php';
	var t;
	if(t=get('tag')){
		getUrl = getUrl + '?f=blog_searchByTagJson&tag='+t;
	}else if(t=get('category')){
		getUrl = getUrl + '?f=blog_searchByCategoryJson&category='+t;
	}else if(t=get('author')){
		getUrl = getUrl + '?f=blog_searchByAuthorJson&author='+t;
	}else if(t=get('s')){
		getUrl = getUrl + '?f=blog_searchJson&s='+t;
	}else{
		postBox.innerHTML = '<div class="post">什么也没有哦~</div>';
		return;
	}
	xhr.open('GET',getUrl,true);
	xhr.send();
	xhr.onreadystatechange=function(){
		if(xhr.readyState==4 && xhr.status==200){
			if(xhr.responseText=='NULL'){
				postBox.innerHTML = '<div class="post">什么也没有哦~</div>';
				return;
			}
			ao = eval('('+xhr.responseText+')');
			var out='';
			for(var i=0;i<ao.length;i++){
				var details = '| Author:'+ ao[i].author +' | Date:'+ao[i].date+' |';
				var title = ao[i].title;
				var content = ao[i].content;
				var cid = ao[i].cid;
				out += "<div class=\"post\"><div class=\"title\"><a id=\"title\" href=\"../article/"+cid+"\"><strong>"+title+"</strong></a><a style=\"margin-left:5px;font-size:10px\" id=\"title\" href=\"../article/"+cid+"\" target=\"_blank\"><i class=\"fa fa-external-link\"></i></a></div><div class=\"articleDetail\">"+details+"</div><div class=\"omittedContent\">"+content+"</div></div>\n";
			}
			postBox.innerHTML = out;
			var posWidth = document.getElementsByClassName('post')[0].offsetWidth;
			var omt = document.getElementsByClassName('omittedContent');
			for(var i=0;i<omt.length;i++){
				omt[i].style.width=posWidth*0.95+'px';
			}
			setArticalPostEvent();
		}
	}
}
function get(key){
	var url = location.href;
	var reg = new RegExp('\\?'+key+'=(.+)','gi');
	var re = reg.exec(url);
	if(re) return re[1];
	else return false;
}
function setArticalPostEvent(){
	var mPost = document.getElementById('postBox').childNodes;
//	console.log(mPost);
	var url;
	for(var i=0;i<mPost.length;i++){
		if(mPost[i].className == 'post'){
			mPost[i].addEventListener('click',function(e){
				console.log('test');
				lsn(this,e);
			},false);
		}
	}
	function lsn(a,e){
		if(e.target.tagName == 'A' || e.target.tagName=='I') return;
		url = a.childNodes[0].childNodes[0].href;
		window.location.href = url;
	}
}