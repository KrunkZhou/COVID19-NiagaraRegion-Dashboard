<?php
/**
 * 尼亚加拉地区疫情监控 - 数据爬虫
 * Author: Krunk Zhou (https://krunk.cn/kblog1558.htm)
 * Licence: MIT Licences
 */

if (isset($_GET['api'])){
	if ($_GET['api']=="t"){
		$response=true;
	}
}

//获取
$html=@file_get_contents("https://niagararegion.ca/health/covid-19/default.aspx");
$html2=@file_get_contents("https://www.niagarahealth.on.ca/site/covid19casereporting");

if ($html&$html2){

	libxml_use_internal_errors(true);
	$html=str_replace('&nbsp;','',$html);
	$html=str_replace('<br/>','-kbr-',$html);
	$dom = new DOMDocument();
	$dom->loadHTML($html);
	$xpath = new DOMXPath($dom);

	$html2=str_replace('&nbsp;','',$html2);
	$html2=str_replace('<br/>','-kbr-',$html2);
	$dom2 = new DOMDocument();
	$dom2->loadHTML($html2);
	$xpath2 = new DOMXPath($dom2);

	// Confirmed Cases in Niagara
	$strCaseNumbers = $xpath->query('//strong[@id="strCaseNumbers"]')->item(0)->nodeValue;
	//var_dump($strCaseNumbers);

	// resolved (have recovered)
	$spnResolvedCases = $xpath->query('//strong[@id="spnResolvedCases"]')->item(0)->nodeValue;
	//var_dump($spnResolvedCases);

	// Time
	$spnUpdateTime = $xpath->query('//span[@id="spnUpdateTime"]')->item(0)->nodeValue;
	//var_dump($spnUpdateTime);

	// Death
	$dNum=trim($xpath2->query('//table/tbody/tr[6]/td[2]')->item(0)->nodeValue);
	//var_dump($dNum);

	include('kdb.class.php');
	$db = new kdb();

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
			}else{
				header("Location: index.php?s=nothing");
			}
		}else{
			//unset($data['death']);
			$db->update('covid-19-niagara',$data,key($cases));
			if ($response){
				echo "1";
			}else{
				header("Location: index.php?s=update");
			}
		}
	}else{
		$db->insert('covid-19-niagara',$data);
		if ($response){
			echo "1";
		}else{
			header("Location: index.php?s=new");
		}
	}
}else{
	$db->insert('covid-19-niagara',$data);
		if ($response){
			echo "3";
		}else{
			header("Location: index.php?s=error");
		}
}

//备用插入
// $date="2020/03/25";
// $data = array(
// 		'date' => $date,
// 		'strCaseNumbers' => "9",
// 		'spnResolvedCases'	=> "1",
// 		'update_time' => "12 p.m., March 25, 2020"
// 	);

// 	$auser = $db->find_one('covid-19-niagara',array('date' => $date));
// 	if ($auser){
// 		$db->update('covid-19-niagara',$data,key($auser));
// 	}else{
// 		$db->insert('covid-19-niagara',$data);
// 	}

?>