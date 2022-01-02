<?php
/**
 * 尼亚加拉地区疫情监控 - 加油
 * Author: Krunk Zhou (https://krunk.cn/kblog1558.htm)
 * Licence: MIT Licences
 */

header("Access-Control-Allow-Origin: *");

// DDOS保护
session_start();
function microtime_float(){
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
$t = microtime_float();
$uri = md5($_SERVER['REQUEST_URI']);
$exp = 0.2; // seconds
$hash = $uri .'|'. $t;
if(isset($_SESSION['ddos'])){
	list($_uri, $_exp) = explode('|', $_SESSION['ddos']);
	if ($_uri == $uri && $t - $_exp < $exp) {
	    header('HTTP/1.1 503 Service Unavailable');
	    die;
	}
}
$_SESSION['ddos'] = $hash;

include('kdb.class.php');
$db = new kdb();

$date = new DateTime();
$timestamp = $date->getTimestamp();

// 获取数量
if (isset($_POST['add'])){
	$token=$_POST['add']/14*3/15;
	if ($_POST['add']==0){
		$likes = $db->find_one('covid-19-like',array('name' => 'like'));
		if ($likes){
			echo $likes[key($likes)]['like'];
		}else{
			echo "0";
		}
		exit(0);
	}else if($token <= $timestamp+60 && $token > $timestamp-300*12*24){
		//前24小时 后60秒内的请求被认为合法请求
	}else{
		echo "-1";
		exit(0);
	}
}else{
	header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
	exit("403 Forbidden");
}

$likes = $db->find_one('covid-19-like',array('name' => 'like'));

if ($likes){
	//加1
	$like_c=$likes[key($likes)]['like']+1;
	$data = array(
		'date' => date("Y/m/d"),
		'like' => $like_c
	);
	$db->update('covid-19-like',$data,key($likes));
	echo $like_c;
}else{
	//初始化
	$data = array(
		'name' => 'like',
		'like' => 1
	);
	$db->insert('covid-19-like',$data);
	echo "1";
}


?>