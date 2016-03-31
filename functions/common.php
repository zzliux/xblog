<?php
date_default_timezone_set('Asia/Shanghai');
session_start();
include_once(dirname(__FILE__).'/../class/database.class.php');

function blog_title(){
	$db = new database();
	echo $db->getOption('blog_title');
}

function blog_description(){
	$db = new database();
	echo $db->getOption('blog_description');
}

function blog_header(){
	$db = new database();
	$themeName = $db->getThemeName();
	include_once(dirname(__FILE__)."/../templates/{$themeName}/component/header.php");
}

function blog_body(){
	$db = new database();
	$themeName = $db->getThemeName();
	include_once(dirname(__FILE__)."/../templates/{$themeName}/component/body.php");
}

function blog_sidebar(){
	$db = new database();
	$themeName = $db->getThemeName();
	include_once(dirname(__FILE__)."/../templates/{$themeName}/component/sidebar.php");
}

function blog_footer(){
	$db = new database();
	$themeName = $db->getThemeName();
	include_once(dirname(__FILE__)."/../templates/{$themeName}/component/footer.php");
}

function blog_listArticles(){
	$db = new database();
	$articlesList = $db->getArticles(0,5);
	foreach($articlesList as $v){
		if(mb_strlen($v['content'],'UTF-8')>50){
			$omittedContent = mb_substr($v['content'],0,50,'UTF-8') . '...';
		}else{
			$omittedContent = $v['content'];
		}
		$omittedContent = htmlentities($omittedContent);
		$detail = '| Author:<a href="search/?author='.($db->getUser($v['uid'])).'">'.($db->getUser($v['uid'])).'</a> | '.'Date:'.date("Y-m-d",$v['date']).' |';
		$out .= "
			<div class=\"post\">
				<div class=\"title\">
					<a id=\"title\" href=\"article/{$v['cid']}\"><strong>{$v['title']}</strong></a><a style=\"margin-left:5px;font-size:10px\" id=\"title\" href=\"article/{$v['cid']}\" target=\"_blank\"><i class=\"fa fa-external-link\"></i></a>
				</div>
				<div class=\"articleDetail\">
					{$detail}
				</div>
				<div class=\"omittedContent\">
					{$omittedContent}
				</div>
			</div>\n";
	}
	echo $out;
}

function blog_siteLink(){
	$db = new database();
	echo $db->getOption('blog_siteLink');
}

function blog_articlesJson($start,$len){
	$re = (new database())->getArticles($start,$len);
	if(!is_array($re)) { echo 'NULL'; return;}
	foreach($re as $v){
		if(mb_strlen($v['content'],'UTF-8')>50){
			$v['content'] = mb_substr($v['content'],0,50,'UTF-8') . '...';
		}
		$v['date'] = date('Y-m-d',$v['date']);
		$out[] = $v;
	}
	echo json_encode($out);
}

function blog_commentsJson($cid=-1){
	if($cid == -1)
		$re = (new database())->getComment($cid, true);
	else
		$re = (new database())->getComment($cid);
	$len = count($re);
	for($i=0;$i<$len;$i++){
		$re[$i]['date'] = date('Y-m-d',$re[$i]['date']);
		unset($re[$i]['status']);
		unset($re[$i]['ip']);
		unset($re[$i]['email']);
		if($cid != -1)
			unset($re[$i]['cid']);
		unset($re[$i]['ua']);
		unset($re[$i]['uid']);
		if(empty($re[$i]['url']))
			unset($re[$i]['url']);
		if($re[$i]['parent']==0)
			unset($re[$i]['parent']);
		if($cid == -1){
			if(mb_strlen($re[$i]['content'],'UTF-8')>15)
				$re[$i]['content'] = mb_substr($re[$i]['content'],0,15,'UTF-8') . '...';
		}
	}
	echo json_encode($re);
}

function blog_article($cid,$status = 1){
	require_once(dirname(__FILE__).'/../plugins/parsedown/Parsedown.php');
	$pd = new Parsedown();
	$db = new database();
	$res = $db->getArticle($cid,$status);
	if(!$res) return FALSE;
	$res['content'] = '<article class="markdown-body">'.$pd->text($res['content']).'</article>';
	$reg = '/([^|]+)\|/';
	preg_match_all($reg, $res['tags'], $tem);
	$sl = $db->getOption('blog_siteLink');
	$outTag = '<a href="'.$sl.'search/?tag='.$tem[1][0].'" target="_blank">'.$tem[1][0].'</a>';
	for($l=count($tem[1]),$i=1;$i<$l;$i++){
		$outTag = $outTag .',<a href="'.$sl.'search/?tag='.$tem[1][$i].'" target="_blank">'.$tem[1][$i].'</a>';
	}
	preg_match_all($reg, $res['categories'], $tem);
	$outCategory = '<a href="'.$sl.'search/?category='.$tem[1][0].'" target="_blank">'.$tem[1][0].'</a>';
	for($l=count($tem[1]),$i=1;$i<$l;$i++){
		$outCategory = $outCategory .',<a href="'.$sl.'search/?category='.$tem[1][$i].'" target="_blank">'.$tem[1][$i].'</a>';
	}
	$res['footerDetails'] = '| Tags:'.$outTag.' | Categories:'.$outCategory.' | Author:<a href="../search/?author='.$db->getUser($res['uid']).'" target="_blank">'.$db->getUser($res['uid']).'</a> | Date:'.date('Y-m-d H:i:s',$res['date']);

	$out = "<div class=\"post\">
			<div class=\"title article\">
				<h1><p><strong>{$res['title']}</strong></p></h1>
			</div>
			<hr style=\"margin-bottom:50px;\">
			<div>
				{$res['content']}
			</div>
			<hr>
			<div class=\"footer-details\">
				{$res['footerDetails']}
			</div>
		</div>\n";
	echo $out;
}

function blog_articleTitle($cid){
	echo (new database())->getArticleTitle($cid);
}

function blog_articleKeywords($cid){
	echo (new database())->getArticleKeywords($cid);
}

function blog_userPage(){
	session_start();
	if(!$_SESSION['uid']){
		header('Location:'.(new database)->getOption('blog_siteLink').'user/login.php');
	}
}

function blog_listImageJson(){
	$thumbFoldName = dirname(__FILE__).'/../uploads/images/thumb/';
	$db = new database();
	$r = scandir($thumbFoldName);
	foreach($r as $v){
		if($v === '.' || $v === '..') continue;
		$out[] = array(
			'thumbUrl' => $db->getOption('blog_siteLink').'uploads/images/thumb/'.$v,
			'fullUrl' => $db->getOption('blog_siteLink').'uploads/images/'.$v
		);
	}
	echo json_encode($out);
}

function blog_listUserArticles($uid){
	$db = new database();
	$re = $db->getArticlesByUid($uid,0);
	$out = '<table><tr><th>#</th><th>标题</th><th>标签</th><th>分类</th><th>操作</th><th>状态</th></tr>';
	foreach($re as $v){
		if(preg_match_all('/([^|]+)\|/', $v['tags'],$t)){
			$v['tags'] = "<a href=\"../search/?tag={$t[1][0]}\" target=\"_blank\">{$t[1][0]}</a>";
			for($i=1,$l=count($t[1]);$i<$l;$i++){
				$v['tags'] .= ",<a href=\"../search/?tag={$t[1][$i]}\" target=\"_blank\">{$t[1][$i]}</a>";
			}
		}else{
			$v['tags'] = '';
		}
		if(preg_match_all('/([^|]+)\|/', $v['categories'],$t)){
			$v['categories'] = "<a href=\"../search/?category={$t[1][0]}\" target=\"_blank\">{$t[1][0]}</a>";
			for($i=1,$l=count($t[1]);$i<$l;$i++){
				$v['categories'] .= ",<a href=\"../search/?category={$t[1][$i]}\" target=\"_blank\">{$t[1][$i]}</a>";
			}
		}else{
			$v['categories'] = '';
		}
		$out .= "<tr><td>{$v['cid']}</td><td><a href=\"?editor=1&cid={$v['cid']}\">{$v['title']}</a></td><td>{$v['tags']}</td><td>{$v['categories']}</td><td><a href=\"../article/{$v['cid']}&viewDraft=1\" target=\"_blank\">查看文章</a></td><td>".($v['status']?"已发布":"草稿")."</td></tr>";
	}
	$out.='</table>';
	echo $out;
}

function blog_listArticleComment($cid){
	$db = new database();
	$re = $db->getComment($cid);
	if(!is_array($re)) return;
	foreach($re as $v){
		if($v['url']) $v['name'] = "<a href=\"{$v['url']}\" target=\"_blank\">{$v['name']}</a>";
		$v['detail'] = "| Author:{$v['name']} | Date:".date('Y-m-d',$v['date'])." |";
		$out .= "
		<div class=\"post\">
			<div class=\"comment-detail\">
				{$v['detail']}
			</div>
			<div class=\"comment-content\">
				{$v['content']}
			</div>
		</div>
		";
	}
	echo $out;
}

function blog_isSetArticle($cid){
	$db = new database();
	return $db->isSetArticle($cid);
}

function theme_cssLink(){
	echo (new database())->getCssLink();
}

function theme_jsLink(){
	echo (new database())->getJsLink();
}

function blog_insertComment(){
	if(isset($_POST['name'])){
		$db = new database();
		$_SESSION['name'] = $_POST['name'];
		$_SESSION['email'] = $_POST['email'];
		$_SESSION['url'] = $_POST['url'];
		if($db->insertComment($_GET['cid'],$_POST['name'], 0,$_POST['email'],$_POST['url'],getIP(),$_SERVER['HTTP_USER_AGENT'],$_POST['content'], 1, $_POST['parent'])){
			$out['err'] = 0;
			$out['msg'] = '<font color="green"><strong>评论成功!</strong></font>';
		}else{
			$out['err'] = 1;
			$out['msg'] = '<font color="red"><strong>请重新检查您提交的内容是否有误!</strong></font>';
		}
		return json_encode($out);
	}
}

/* 搜索部分 */
function blog_searchByTagJson($key){
	$re = (new database)->searchByTag($key);
	if(!$re[0]) { echo 'NULL'; return;}
	foreach($re as $v){
		if(empty($v)) continue;
		$v['content'] = htmlentities($v['content']);
		if(mb_strlen($v['content'],'UTF-8')>50){
			$v['content'] = mb_substr($v['content'],0,50,'UTF-8') . '...';
		}
		$v['date'] = date('Y-m-d',$v['date']);
		$out[] = $v;
	}
	echo json_encode($out);
}
function blog_searchByCategoryJson($key){
	$re = (new database)->searchByCategory($key);
	if(!$re[0]) { echo 'NULL'; return;}
	foreach($re as $v){
		if(empty($v)) continue;
		$v['content'] = htmlentities($v['content']);
		if(mb_strlen($v['content'],'UTF-8')>50){
			$v['content'] = mb_substr($v['content'],0,50,'UTF-8') . '...';
		}
		$v['date'] = date('Y-m-d',$v['date']);
		$out[] = $v;
	}
	echo json_encode($out);
}
function blog_searchByAuthorJson($key){
	$re = (new database)->searchByAuthor($key);
	if(!$re[0]) { echo 'NULL'; return;}
	foreach($re as $v){
		if(empty($v)) continue;
		$v['content'] = htmlentities($v['content']);
		if(mb_strlen($v['content'],'UTF-8')>50){
			$v['content'] = mb_substr($v['content'],0,50,'UTF-8') . '...';
		}
		$v['date'] = date('Y-m-d',$v['date']);
		$out[] = $v;
	}
	echo json_encode($out);
}
function blog_searchJson($key){
	$re = (new database)->search($key);
	if(!$re[0]) { echo 'NULL'; return;}
	foreach($re as $v){
		if(empty($v)) continue;
		if(mb_strlen($v['content'],'UTF-8')>50){
			$v['content'] = mb_substr($v['content'],0,50,'UTF-8') . '...';
		}
		$v['date'] = date('Y-m-d',$v['date']);
		$out[] = $v;
	}
	echo json_encode($out);
}

/* 获取tags和categories相关信息 */
function blog_tagsJson(){
	echo json_encode((new database)->getAllTags($key));
}
function blog_categoriesJson(){
	echo json_encode((new database)->getAllCategories($key));
}

function userInfoJson($un){
	$db = new database();
	$un = $db->ch->real_escape_string($un);
	$res = $db->ch->query("SELECT `name`,`url` FROM {$db->tablePrefix}_comments WHERE `name` = '{$un}' LIMIT 1");
	$row = $res->fetch_assoc();
	echo json_encode($row);
}


/* other */
function getIP(){
	if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
	else
		$ip = "Unknow";
	return $ip;
}