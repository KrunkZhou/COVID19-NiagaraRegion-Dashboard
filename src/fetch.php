<?php
/**
 * 尼亚加拉地区疫情监控 - js抓取
 * Author: Krunk Zhou (https://krunk.cn/kblog1558.htm)
 * Licence: MIT Licences
 */
?>
<html>
<head>
	<title>请勿关闭此页面</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style>
		html,body{
			height: 100%;
			width: 100%;
			margin:0;
		}
		ul {
		  list-style-type: none;
		  margin: 0;
		  padding: 0;
		  overflow: hidden;
		  background-color: #333;
		}

		li {
		  float: left;
		  border-right:1px solid #bbb;
		}

		li:last-child {
		  border-right: none;
		}

		li a {
		  display: block;
		  color: white;
		  text-align: center;
		  padding: 14px 16px;
		  text-decoration: none !important;
		}

		li a:hover:not(.active) {
		  background-color: #111;
		}

		.active {
		  background-color: #c00;
		}
	</style>
</head>
<body>

<ul>
  <li><a class="active">数据正在全自动抓取，请不要关闭页面</a></li>
  <li><a class="">请确保插件已经正确安装并运行</a></li>
</ul>
<?php

$html=@file_get_contents("https://niagararegion.ca/health/covid-19/statistics.aspx");

if ($html){
	libxml_use_internal_errors(true);
	$html=str_replace('&nbsp;','',$html);
	$html=str_replace('<br/>','-kbr-',$html);
	$dom = new DOMDocument();
	$dom->loadHTML($html);
	$xpath = new DOMXPath($dom);
	$pbiFrame = $xpath->query('//iframe[@id="pbiFrame"]/@src')->item(0)->nodeValue;

	echo "<iframe style='width:100%;height:100%;' TITLE='Data and Information Assessment' ID='pbiFrame' src='".$pbiFrame."' frameborder='0' allowFullScreen='true' sandbox='allow-scripts allow-same-origin'></iframe></DIV>";
}

?>
</html>