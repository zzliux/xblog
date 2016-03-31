var articleStartNum = 5;
var ao;
var moreBox = document.getElementsByClassName('more')[0];
var postBox = document.getElementById('postBox');
var posWidth = document.getElementsByClassName('post')[0].offsetWidth;
moreBox.addEventListener('click',setMoreArticles,false);
window.addEventListener('scroll',scrollListener,false);
window.onload = function(){
	var xhr = new XMLHttpRequest();
	var lcb = document.getElementById('latestComments');
	lcb.innerHTML = '加载中 <i class="fa fa-circle-o-notch fa-spin"></i>'
	xhr.open('GET','functions/ajax.php?f=blog_commentsJson&cid=-1',true);
	xhr.send();
	xhr.onreadystatechange=function(){
		if(xhr.readyState==4 && xhr.status==200){
			var lc = eval('('+xhr.responseText+')');
			lcb.innerHTML = '<div class="sidebar-nav">近期评论:</div>';
			for(var i=0;i<lc.length;i++){
				lcb.innerHTML += '<div class="post" style="margin:5px;font-size:small;padding:1px 10px 5px 10px;"><a href="./article/'+lc[i].cid+'">' + lc[i].name + ':'+ lc[i].content +'</a><div style="float:right;color:#666">'+lc[i].date+'</div></div>';
			}
		}
	}
	var xhr2 = new XMLHttpRequest();
	var tb = document.getElementById('tagsBox');
	tb.innerHTML = '加载中 <i class="fa fa-circle-o-notch fa-spin"></i>'
	xhr2.open('GET','functions/ajax.php?f=blog_tagsJson',true);
	xhr2.send();
	xhr2.onreadystatechange=function(){
		if(xhr2.readyState==4 && xhr2.status==200){
			var tags = eval('('+xhr2.responseText+')');
			tb.innerHTML = '<div class="sidebar-nav">标签:</div>';
			for(k in tags){
				tb.innerHTML += '<div class="post" style="margin:5px;font-size:small;width:initial;display:inline-block;"><a href="./search/?tag='+k+'" target="_blank" style="margin-left:10px">'+k.replace(' ','_')+'('+tags[k]+')</a></div>';
			}
		}
	}
	var xhr3 = new XMLHttpRequest();
	var cb = document.getElementById('categoriesBox');
	cb.innerHTML = '加载中 <i class="fa fa-circle-o-notch fa-spin"></i>'
	xhr3.open('GET','functions/ajax.php?f=blog_categoriesJson',true);
	xhr3.send();
	xhr3.onreadystatechange=function(){
		if(xhr3.readyState==4 && xhr3.status==200){
			var categories = eval('('+xhr3.responseText+')');
			cb.innerHTML = '<div class="sidebar-nav">分类:</div>';
			for(k in categories){
				cb.innerHTML += '<a href="./search/?category='+k+'" target="_blank" style="margin-left:10px">'+k.replace(' ','_')+'('+categories[k]+')</a><br>';
			}
		}
	}
	setArticalPostEvent();
};

function scrollListener(){
	if(Math.abs(document.documentElement.clientHeight-document.body.clientHeight) + 18 <= (document.documentElement.scrollTop || document.body.scrollTop))
		setMoreArticles();
}
function setArticalPostEvent(){
	var mPost = document.getElementById('postBox').childNodes;
	var url;
	for(var i=1;i<mPost.length;i+=2){
		if(mPost[i].className == 'post'){
			mPost[i].addEventListener('click',function(e){
				lsn(this,e);
			},false);
		}
	}
	function lsn(a,e){
		if(e.target.tagName == 'A' || e.target.tagName=='I') return;
		url = a.childNodes[1].childNodes[1].href;
		window.location.href = url;
	}
}
function setMoreArticles(){
	moreBox.innerHTML = '加载中 <i class="fa fa-circle-o-notch fa-spin"></i>';
	moreBox.className = 'more disabled';
	moreBox.removeEventListener('click',setMoreArticles,false);
	window.removeEventListener('scroll',scrollListener,false);

	var xhr = new XMLHttpRequest();
	xhr.open('GET','functions/ajax.php?f=blog_articlesJson&s='+articleStartNum+'&l=5',true);
	xhr.send();
	xhr.onreadystatechange=function(){
		if(xhr.readyState==4 && xhr.status==200){

			if(xhr.responseText == 'NULL'){
				moreBox.innerHTML = '已无更多';
				moreBox.className = 'more disabled';
				return;
			}
			ao = eval('('+xhr.responseText+')');
			var out='';
			for(var i=0;i<ao.length;i++){
				var details = '| Author:<a href="search/?author='+ao[i].author+'">'+ ao[i].author +'</a> | Date:'+ao[i].date+' |';
				var title = ao[i].title;
				var content = ao[i].content;
				var cid = ao[i].cid;
				out += "<div class=\"post\">\n<div class=\"title\"><a id=\"title\" href=\"article/"+cid+"\"><strong>"+title+"</strong></a><a style=\"margin-left:5px;font-size:10px\" id=\"title\" href=\"article/"+cid+"\" target=\"_blank\"><i class=\"fa fa-external-link\"></i></a></div><div class=\"articleDetail\">"+details+"</div><div class=\"omittedContent\">"+content+"</div></div>\n";
			}
			postBox.innerHTML += out;
			moreBox.innerHTML = '加载更多';
			moreBox.className = 'more'
			moreBox.addEventListener('click',setMoreArticles,false);
			window.addEventListener('scroll',scrollListener,false);
			setArticalPostEvent();
		}
	}
	articleStartNum += 5;
}