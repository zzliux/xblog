console.log(" ____\n|____|\n|____|\n/   .|");
if(location.href.match(/(search)|(article)/)&&window.history.length>1){
	document.getElementsByClassName('float-menu')[0].innerHTML+='<div class="getBackBtn"><i class="fa fa-reply"></i></div>';
	document.getElementsByClassName('getBackBtn')[0].addEventListener('click',function(){
		window.history.go(-1);
	},false);
}
window.addEventListener('scroll',topBtn,false);
var topBtnStyle = document.getElementsByClassName('getTopBtn')[0].style;
document.getElementsByClassName('getTopBtn')[0].addEventListener('click',function(){getTop(15)},false);

function topBtn(){
	if(document.body.scrollTop===0){
		topBtnStyle.display = 'none';
	}else if(topBtnStyle.display==='none'){
		topBtnStyle.display = 'block';
	}
}
function getTop(l){
	setTimeout(function(){
		var a = document.body.scrollTop-l;
		if(a>0){
			document.body.scrollTop-=l;
			getTop(l+1);
		}else if(document.body.scrollTop>0){
			document.body.scrollTop=0;
		}
	},1);
}