<?php
/**
 * 尼亚加拉地区疫情监控 - 加油
 * Author: Krunk Zhou (https://krunk.cn/kblog1558.htm)
 * Licence: MIT Licences
 */

include('kdb.class.php');
$db = new kdb();

// 获取数量
if (isset($_GET['add'])){
	if ($_GET['add']!="1"){
		$likes = $db->find_one('covid-19-like',array('name' => 'like'));
		if ($likes){
			echo $likes[key($likes)]['like'];
		}else{
			echo "0";
		}
		exit(0);
	}
}else{
	exit(0);
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