<?php
/**
 * KRUNK.CN KDB My Admin
 * @ Website: https://api.krunk.cn/kdb
 * @ Dev Website: https://kblog.krunk.cn/view.php?post=kdb
 */
session_start();
include('kdb.class.php');
include('kdbmyadmin_config.php');

function kdbmyadmin_login($un,$pw){
	global $username,$password;
	if ($un==$username){
		if(md5($pw)==$password){
			return true;
		}
	}
	return false;
}

function kdbmyadmin_check_login(){
	global $username;
	if (isset($_SESSION['un'])&&$_SESSION['un']==$username&&$_SESSION['server']==$_SERVER["SERVER_NAME"]){
		return true;
	}
	return false;
}

function kdb_set_db(){
	if (isset($_SESSION['dir'])&&isset($_SESSION['ex'])&&isset($_SESSION['en'])){
		$db = new kdb([
		    'dir'      => $_SESSION['dir'],
		    'extension' => $_SESSION['ex'],
		    'encrypt'   => ($_SESSION['en']=="true") ? true : false,
		]);
		return $db;
	}else{
		$db = new kdb([
		    'dir'      => 'kdb/',
		    'extension' => 'kdb',
		    'encrypt'   => false,
		]);
		return $db;
	}
}

function kdbmyadmin_logout(){
	$_SESSION['un']="";
	session_unset();
	session_destroy();
}

function get_server_ip(){
	$ip=@file_get_contents("https://api.krunk.cn/get-ip/");
	return $ip;
}

?>