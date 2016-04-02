<?php
class database {
	public $ch;
	public $tablePrefix = 'xblog';
	protected $user = array();
	protected $options = array();
	private $pwdSalt = 'this is a zha zha blog';

	function __construct(){
		require_once(dirname(__FILE__).'/../config/database.php');
		require(dirname(__FILE__).'/../config/siteInfo.php');
		$this->options = $config;
		$this->ch = new mysqli(HOSTNAME,HOSTUSER,HOSTPWD,HOSTDB);
		$this->ch->query('SET NAMES utf8mb4');
	}

	function searchByAuthor($key){
		$key = $this->ch->real_escape_string($key);
		if(empty($key)) return FALSE;
		$sql = "SELECT * FROM `{$this->tablePrefix}_articles` WHERE `uid` = (SELECT `uid` FROM `{$this->tablePrefix}_userinfo` WHERE `name` = '{$key}') AND `status` = 1 ORDER BY `cid` DESC";
		$res = $this->ch->query($sql);
		$commentsCounts = $this->getCommentsCounts();
		while($row = $res->fetch_assoc()){
			$row['commentsCounts'] = isset($commentsCounts[intval($row['cid'])]) ? $commentsCounts[intval($row['cid'])] : 0;
			$row['author'] = $this->getUser($row['uid']);
			$re[] = $row;
		}
		return $re;
	}
	function searchByTag($key){
		$key = $this->ch->real_escape_string($key);
		if(empty($key)) return FALSE;
		$sql = "SELECT * FROM `{$this->tablePrefix}_articles` WHERE `tags` LIKE '%|{$key}|%' AND `status` = 1 ORDER BY `cid` DESC";
		$res = $this->ch->query($sql);
		$commentsCounts = $this->getCommentsCounts();
		while($row = $res->fetch_assoc()){
			$row['commentsCounts'] = isset($commentsCounts[intval($row['cid'])]) ? $commentsCounts[intval($row['cid'])] : 0;
			$row['author'] = $this->getUser($row['uid']);
			$re[] = $row;
		}
		return $re;
	}
	function searchByCategory($key){
		$key = $this->ch->real_escape_string($key);
		if(empty($key)) return FALSE;
		$sql = "SELECT * FROM `{$this->tablePrefix}_articles` WHERE `categories` LIKE '%|{$key}|%'  AND `status` = 1 ORDER BY `cid` DESC";
		$res = $this->ch->query($sql);
		$commentsCounts = $this->getCommentsCounts();
		while($row = $res->fetch_assoc()){
			$row['commentsCounts'] = isset($commentsCounts[intval($row['cid'])]) ? $commentsCounts[intval($row['cid'])] : 0;
			$row['author'] = $this->getUser($row['uid']);
			$re[] = $row;
		}
		return $re;
	}
	function getAllTags(){
		$sql = "SELECT `tags` FROM `{$this->tablePrefix}_articles` WHERE `status` = 1";
		$res = $this->ch->query($sql);
		while($row = $res->fetch_assoc()){
			preg_match_all('/([^|]+)\|/', $row['tags'],$t);
			if($t[1])
			foreach ($t[1] as $v) {
				if(isset($re[$v])){
					$re[$v]++;
				}else{
					$re[$v]=1;
				}
			}
		}
		return $re;
	}
	function getAllCategories(){
		$sql = "SELECT `categories` FROM `{$this->tablePrefix}_articles` WHERE `status` = 1";
		$res = $this->ch->query($sql);
		while($row = $res->fetch_assoc()){
			preg_match_all('/([^|]+)\|/', $row['categories'],$t);
			if($t[1])
			foreach ($t[1] as $v) {
				if(isset($re[$v])){
					$re[$v]++;
				}else{
					$re[$v]=1;
				}
			}
		}
		return $re;
	}
	function search($key){
		$key = $this->ch->real_escape_string($key);
		if(empty($key)) return FALSE;
		$sql = "SELECT * FROM `{$this->tablePrefix}_articles` WHERE (`title` LIKE '%{$key}%' OR `content` LIKE '%{$key}%') AND `status` = 1 ORDER BY `cid` DESC";
		$res = $this->ch->query($sql);
		while($row = $res->fetch_assoc()){
			$row['author'] = $this->getUser($row['uid']);
			$re[] = $row;
		}
		return $re;
	}

	function checkPassword($email, $password, $pswIsMD5=FALSE){
		$email = $this->ch->real_escape_string($email);
		if(!$pswIsMD5)
			$password = md5($this->ch->real_escape_string($password).$this->pwdSalt);
		else
			$password = $this->ch->real_escape_string($password);
		$sql = "SELECT `uid` FROM `{$this->tablePrefix}_userinfo` WHERE `email` = '{$email}' AND `password` = '{$password}';";
		$res = $this->ch->query($sql);
		$res = $res->fetch_assoc();
		if($res){
			return $res['uid'];
		}else{
			return FALSE;
		}
	}

	function insertUser($name, $email, $password, $url = NULL){
		$name	  = $this->ch->real_escape_string($name);
		$email 	  = $this->ch->real_escape_string($email);
		$url	  = $this->ch->real_escape_string($url);
		$password = md5($this->ch->real_escape_string($password).$this->pwdSalt);
		$time	  = time();
		if($this->isSetUserName($name) || $this->isSetUserEmail($email) || empty($name) || empty($email)){
			return FALSE;
		}else{
			$sql = "INSERT INTO `{$this->tablePrefix}_userinfo` (`name`,`email`,`password`,`url`,`registered`) VALUES ('{$name}','{$email}','{$password}','{$url}','{$time}');";
			$this->ch->query($sql);
			return TRUE;
		}

	}

	function isSetUserName($name){
		$name = $this->ch->real_escape_string($name);
		$sql = "SELECT `uid` FROM `{$this->tablePrefix}_userinfo` WHERE `name` = '{$name}';";
		$res = $this->ch->query($sql);
		$res = $res->fetch_assoc();
		if($res){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	function isSetUserEmail($email){
		$email = $this->ch->real_escape_string($email);
		$sql = "SELECT `uid` FROM `{$this->tablePrefix}_userinfo` WHERE `email` = '{$email}';";
		$res = $this->ch->query($sql);
		$res = $res->fetch_assoc();
		if($res){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	function getOption($key){
		$key = $this->ch->real_escape_string($key);
		if($this->options[$key]){
			return $this->options[$key];
		}
		$sql = "SELECT `value` FROM `{$this->tablePrefix}_options` WHERE `key` = '{$key}'";
		$res = $this->ch->query($sql);
		$res = $res->fetch_assoc();
		if($res){
			$this->options[$key] = $res['value'];
			return $res['value'];
		}else{
			return NULL;
		}
	}
	/* 暂时不用了 */
	function setOption($key,$value){
		$key = $this->ch->real_escape_string($key);
		$value = $this->ch->real_escape_string($value);
		$sql = "UPDATE `{$this->tablePrefix}_options` SET `value` = '{$value}' WHERE `key` = '{$key}'";
		$res = $this->ch->query($sql);
		if(!$res){
			$sql = "INSERT INTO `{$this->tablePrefix}_options` (`key`,`value`) VALUES ('{$key}','{$value}')";
			$this->ch->query($sql);
		}
	}

	function insertArticle($uid,$title,$content,$tags,$categories,$status){
		$title		 = $this->ch->real_escape_string($title);
		$uid		 = $this->ch->real_escape_string($uid);
		$content	 = $this->ch->real_escape_string($content);
		$tags 		 = $this->ch->real_escape_string($tags);
		$categories  = $this->ch->real_escape_string($categories);
		$status		 = $this->ch->real_escape_string($status);
		$date		 = time();
		if(empty($categories)) $categories = '|未分类|';
		if($tags[0]!='|') $tags = '|' . $tags;
		if($tags[strlen($tags)-1]!='|') $tags = $tags . '|';
		if($categories[0]!='|') $categories = '|' . $categories;
		if($categories[strlen($categories)-1]!='|') $categories = $categories . '|';
		if(empty($title) || empty($content) || empty($uid)) return FALSE;
		$sql = "INSERT INTO `{$this->tablePrefix}_articles` (`uid`,`title`,`content`,`tags`,`categories`,`date`,`status`) VALUES ('{$uid}','{$title}','{$content}','{$tags}','{$categories}','{$date}','{$status}')";
		$this->ch->query($sql);
		echo $sql;
		return $this->ch->insert_id;
	}

	function updateArticle($cid,$title,$content,$tags,$categories,$status){
		$title		 = $this->ch->real_escape_string($title);
		$cid		 = $this->ch->real_escape_string($cid);
		$content	 = $this->ch->real_escape_string($content);
		$tags 		 = $this->ch->real_escape_string($tags);
		$categories  = $this->ch->real_escape_string($categories);
		$status		 = $this->ch->real_escape_string($status);
		if(empty($categories)) $categories = '|未分类|';
		if($tags[0]!='|') $tags = '|' . $tags;
		if($tags[strlen($tags)-1]!='|') $tags = $tags . '|';
		if($categories[0]!='|') $categories = '|' . $categories;
		if($categories[strlen($categories)-1]!='|') $categories = $categories . '|';
		$sql = "UPDATE `{$this->tablePrefix}_articles` SET `title` = '{$title}' , `content` = '{$content}' , `tags` = '{$tags}' , `categories` = '{$categories}' , `status` = '{$status}' WHERE `cid` = '{$cid}'";
		$this->ch->query($sql);
	}

	function publishArticle($cid){
		$this->setArticleStauts($cid, 1);
	}

	function setArticleStauts($cid, $status){
		$cid = $this->ch->real_escape_string($cid);
		$cid = $this->ch->real_escape_string($cid);
		$sql = "UPDATE `{$this->tablePrefix}_articles` SET `status` = 1 WHERE `cid` = {$cid}";
		$this->ch->query($sql);
	}

	function getArticles($start = 0, $length = 0){
		if($length === 0){
			$sql = "SELECT * FROM `{$this->tablePrefix}_articles` WHERE `status` = 1 ORDER BY `cid` DESC";
		}else{
			$start  = $this->ch->real_escape_string($start);
			$length = $this->ch->real_escape_string($length);
			$sql = "SELECT * FROM `{$this->tablePrefix}_articles` WHERE `status` = 1  ORDER BY `cid` DESC LIMIT {$start},{$length}";
		}
		$res = $this->ch->query($sql);
		$commentsCounts = $this->getCommentsCounts();
		while($row = $res->fetch_assoc()){
			$row['author'] = $this->getUser($row['uid']);
			$row['commentsCounts'] = isset($commentsCounts[intval($row['cid'])]) ? $commentsCounts[intval($row['cid'])] : 0;
			$re[] = $row;
		}
		return $re;
	}

	function getArticle($cid, $status = 1){
		$cid = $this->ch->real_escape_string($cid);
		$status = $this->ch->real_escape_string($status);
		if($status)
			$sql = "SELECT * FROM `{$this->tablePrefix}_articles` WHERE `cid` = '{$cid}' AND `status` > 0";
		else
			$sql = "SELECT * FROM `{$this->tablePrefix}_articles` WHERE `cid` = '{$cid}'";
		$res = $this->ch->query($sql);
		$res = $res->fetch_assoc();
		$commentsCounts = $this->getCommentsCounts();
		$res['commentsCounts'] = isset($commentsCounts[intval($res['cid'])]) ? $commentsCounts[intval($res['cid'])] : 0;
		if($res){
			return $res;
		}else{
			return FALSE;
		}
	}
	function getArticleTitle($cid){
		$cid = $this->ch->real_escape_string($cid);
		$sql = "SELECT `title` FROM `{$this->tablePrefix}_articles` WHERE `cid` = '{$cid}'";
		$res = $this->ch->query($sql);
		$res = $res->fetch_assoc();
		if($res){
			return $res['title'];
		}else{
			return false;
		}
	}
	function getArticleKeywords($cid){
		$cid = $this->ch->real_escape_string($cid);
		$sql = "SELECT `tags` FROM `{$this->tablePrefix}_articles` WHERE `cid` = '{$cid}'";
		$res = $this->ch->query($sql);
		$res = $res->fetch_assoc();
		if($res){
			$t = str_replace('|', ',', $res['tags']);
			return substr($t,1,strlen($t)-2);
		}else{
			return false;
		}
	}
	function getCommentsCounts(){
		$sql = 'SELECT `cid`,count(1) as `counts` FROM `xblog_comments` GROUP BY `cid`';
		$res = $this->ch->query($sql);
		$ret = array();
		while ($row = $res->fetch_assoc()) {
			$ret[intval($row['cid'])] = intval($row['counts']);
		}
		return $ret;
	}

	function isSetArticle($cid){
		$cid = $this->ch->real_escape_string($cid);
		$sql = "SELECT 1 FROM `{$this->tablePrefix}_articles` WHERE `cid` = '{$cid}' AND `status` > 0 LIMIT 1";
		$res = $this->ch->query($sql);
		if($res->fetch_assoc())
			return TRUE;
		else
			return FALSE;
	}

	function getArticlesByUid($uid, $status = 1){
		$uid= $this->ch->real_escape_string($uid);
		$status = $this->ch->real_escape_string($status);
		if($status)
			$sql = "SELECT * FROM `{$this->tablePrefix}_articles` WHERE `uid` = '{$uid}' AND `status` > 0";
		else
			$sql = "SELECT * FROM `{$this->tablePrefix}_articles` WHERE `uid` = '{$uid}'";
		$res = $this->ch->query($sql);
		$commentsCounts = $this->getCommentsCounts();
		while($row = $res->fetch_assoc()){
			$row['commentsCounts'] = isset($commentsCounts[intval($row['cid'])]) ? $commentsCounts[intval($row['cid'])] : 0;
			$re[] = $row;
		}
		return $re;
	}

	function deleteArticle($cid){
		$cid = $this->ch->real_escape_string($cid);
		$sql = "DELETE FROM `{$this->tablePrefix}_articles` WHERE `cid` = '{$cid}'";
		$this->ch->query($sql);
		$sql = "DELETE FROM `{$this->tablePrefix}_comments` WHERE `cid` = '{$cid}'";
		$this->ch->query($sql);
	}

	function insertComment($cid, $name, $uid, $email, $url, $ip, $ua, $content, $status, $parent=0){
		$content = trim($content);
		$name    = trim($name);
		$url     = trim($url);
		$name    = $this->ch->real_escape_string($name);
		$ip 	 = $this->ch->real_escape_string($ip);
		$content = $this->ch->real_escape_string($content);
		if(empty($name)||empty($content)||!$this->checkEmail($email)||
			empty($ip) ||mb_strlen($content,'UTF8')>140||mb_strlen($name,'UTF8')>10) return FALSE;
		$url = $this->getFullUrl($url);
		$content = htmlentities($content);
		$name	= htmlentities($name);
		$url	= htmlentities($url);
		$cid 	= $this->ch->real_escape_string($cid);
		$date 	= time();
		$uid 	= $this->ch->real_escape_string($uid);
		$email 	= $this->ch->real_escape_string($email);
		$url 	= $this->ch->real_escape_string($url);
		$ua 	= $this->ch->real_escape_string($ua);
		$status = $this->ch->real_escape_string($status);
		$parent = $this->ch->real_escape_string($parent);
		$sql = "INSERT INTO `{$this->tablePrefix}_comments` (`cid`,`date`,`name`,`uid`,`email`,`url`,`ip`,`ua`,`content`,`status`,`parent`) values ('{$cid}','{$date}','{$name}','{$uid}','{$email}','{$url}','{$ip}','{$ua}','{$content}','{$status}','{$parent}')";
		$this->ch->query($sql);
		return TRUE;
	}

	function getComment($cid, $desc=false){
		$cid = $this->ch->real_escape_string($cid);
		if($cid == -1){
			if($desc)
				$sql = "SELECT * FROM {$this->tablePrefix}_comments WHERE (SELECT `status` FROM `xblog_articles` WHERE `cid`=`{$this->tablePrefix}_comments`.`cid`) = 1 ORDER BY `coid` DESC LIMIT 5";
			else
				$sql = "SELECT * FROM {$this->tablePrefix}_comments WHERE (SELECT `status` FROM `xblog_articles` WHERE `cid`=`{$this->tablePrefix}_comments`.`cid`) = 1 ORDER BY `coid` ASC LIMIT 5";
		}else{
			if($desc)
				$sql = "SELECT * FROM {$this->tablePrefix}_comments WHERE (SELECT `status` FROM `xblog_articles` WHERE `cid`=`{$this->tablePrefix}_comments`.`cid`) = 1 AND `cid` = '{$cid}' ORDER BY `coid` DESC";
			else
				$sql = "SELECT * FROM {$this->tablePrefix}_comments WHERE (SELECT `status` FROM `xblog_articles` WHERE `cid`=`{$this->tablePrefix}_comments`.`cid`) = 1 AND `cid` = '{$cid}' ORDER BY `coid` ASC";
		}
		$res = $this->ch->query($sql);
		while($row = $res->fetch_assoc()){
			$out[] = $row;
		}
		return $out;
	}

	function checkEmail($email){
		$reg = '/[\w.-_]+@\w+\.\w+/';
		if(preg_match($reg,$email))
			return TRUE;
		return FALSE;
	}

	function getFullUrl($url){
		if(empty($url)) return;
		if(!preg_match('/https?/',$url))
			$url = 'http://'.$url;
		return $url;
	}

	function getUser($uid){
		if(!isset($this->user[$uid])){
			$sql = "SELECT `name` FROM `{$this->tablePrefix}_userinfo` WHERE `uid` = {$uid}";
			if($res = $this->ch->query($sql)){
				$res = $res->fetch_assoc();
				$this->user[$uid] = $res['name'];
			}else{
				return FALSE;
			}
		}
		return $this->user[$uid];
	}

	function getCssLink(){
		$themeName = $this->getThemeName();
		$sql = "SELECT `value` FROM `{$this->tablePrefix}_options` WHERE `key` = 'blog_theme_{$themeName}_link_css'";
		$res = $this->ch->query($sql);
		$res = $res->fetch_assoc();
		if($res){
			return $res['value'];
		}else{
			return FALSE;
		}
	}

	function getJsLink(){
		$themeName = $this->getThemeName();
		$sql = "SELECT `value` FROM `{$this->tablePrefix}_options` WHERE `key` = 'blog_theme_{$themeName}_link_js'";
		$res = $this->ch->query($sql);
		$res = $res->fetch_assoc();
		if($res){
			return $res['value'];
		}else{
			return FALSE;
		}
	}

	function getThemeName(){
		return $this->getOption('blog_theme');
	}

	/* 百度主动推送 */
	function baiduPush($cid){
		$urls = array(
			$this->options['blog_siteLink'].'article/'.$cid
		);
		$api = $this->options['baiduPushUrlApi'];
		$curlCh = curl_init();
		$options =  array(
			CURLOPT_URL => $api,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => implode("\n", $urls),
			CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
		);
		curl_setopt_array($curlCh, $options);
		return curl_exec($curlCh);
	}

	function __destruct(){
		$this->ch->close();
	}
}