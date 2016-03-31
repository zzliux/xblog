hljs.initHighlightingOnLoad();
showLinenumbers('display:inline-block;vertical-align:top;text-align:right;margin-right:10px;color:#e6e1dc;font-size:100%;-webkit-user-select: none;');
 addExternlIcon();
function showLinenumbers(style){
	var preArr = document.getElementsByTagName('pre');
	for(var i=0;i<preArr.length;i++){
		if(preArr[i].innerHTML.match(/<code[^>]*>/)){
			var code = preArr[i].innerHTML.match(/<code[^>]*>([\s\S]+)<\/code>/);
			var line = code[1].length - replaceAll(code[1],"\n",'').length + 1;
			var lineBar = '<div style="'+style+'">';
			for(var j=1;j<=line;j++){
				lineBar += '<span>'+ j + "|</span>\n";
			}
			lineBar += '</div>'
			preArr[i].innerHTML = lineBar + preArr[i].innerHTML;
		}
	}
}
function replaceAll(str, l, r){
	if(str.indexOf(l)>=0){
		return replaceAll(str.replace(l,r), l, r);
	}
	return str;
}
function addExternlIcon(){
	var articleTag = document.getElementsByTagName('article')[0];
	add(articleTag);
	function add(parent){
		if(!parent) return;
		var nodes = parent.childNodes;
		for(var i=0;i<nodes.length;i++){
			if(nodes[i].tagName == 'a' || nodes[i].tagName == 'A'){
				nodes[i].innerHTML = nodes[i].innerHTML + '<sup><i class="fa fa-external-link"></i></sup>';
			}else if(nodes[i].tagName == 'pre' || nodes[i].tagName == 'PRE'){
				return;
			}else{
				add(nodes[i]);
			}
		}
	}
}
/* 百度主动推送 */
(function(){
	var bp = document.createElement('script');
	bp.src = '//push.zhanzhang.baidu.com/push.js';
	var s = document.getElementsByTagName("script")[0];
	s.parentNode.insertBefore(bp, s);
})();