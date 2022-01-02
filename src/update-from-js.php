<?php
/**
 * 尼亚加拉地区疫情监控 - 数据爬虫js
 * Author: Krunk Zhou (https://krunk.cn/kblog1558.htm)
 * Licence: MIT Licences
 */

header("Access-Control-Allow-Origin: *");

include('kdb.class.php');
$db = new kdb();

if($_GET['strCaseNumbers']!=""&&$_GET['spnResolvedCases']!=""&&$_GET['dNum']!=""){
	// Confirmed Cases in Niagara
	$strCaseNumbers = $_GET['strCaseNumbers'];
	// resolved (have recovered)
	$spnResolvedCases = $_GET['spnResolvedCases'];
	// Time
	$spnUpdateTime = date("Y/m/d")." - Updated from JSapi";
	// Death
	$dNum=$_GET['dNum'];
}else{
	die("3");
}

if (isset($_GET['api'])){
	if ($_GET['api']=="t"){
		$response=true;
	}else{
		die("3");
	}
}

$admin = $db->find_one('covid-19-admin',array('name' => 'admin'));
if ($admin){
	if ($admin[key($admin)]['stop-js']=="true"){
		if ($response){
			echo "3";
			exit();
		}
	}
}

if (true){

	$data = array(
		'date' => date("Y/m/d"),
		'strCaseNumbers' => $strCaseNumbers,
		'spnResolvedCases'	=> $spnResolvedCases,
		'update_time' => $spnUpdateTime,
		'death' => $dNum
	);

	$cases = $db->find_one('covid-19-niagara',array('date' => date("Y/m/d")));
	if ($cases){
		if (($cases[key($cases)]['strCaseNumbers']==$data['strCaseNumbers']&&$cases[key($cases)]['spnResolvedCases']==$data['spnResolvedCases']&&$cases[key($cases)]['death']==$data['death'])&&$cases[key($cases)]['update_time']==$data['update_time']){
			if ($response){
				echo "2";
			}
		}else{
			$db->update('covid-19-niagara',$data,key($cases));
			if ($response){
				echo "1";
			}
		}
	}else{
		$db->insert('covid-19-niagara',$data);
		if ($response){
			echo "1";
		}
	}
}

?>