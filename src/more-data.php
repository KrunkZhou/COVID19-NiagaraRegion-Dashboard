<?php
/**
 * 尼亚加拉地区疫情监控 - 更多扩展
 * Author: Krunk Zhou (https://krunk.cn/kblog1558.htm)
 * Licence: MIT Licences
 */
?>
 <html>
<head>
	<title>尼亚加拉地区疫情实时数据 - 更多数据</title>
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
  <li><a class="active" href="index.php">Niagara Region 疫情统计</a></li>
  <!--<li><a href="feedback.php">反馈</a></li>-->
  <li style="float:right"><a href="index.php">返回</a></li>
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
<p style="text-align: center;">尼亚加拉地区疫情实时数据 2020</p>
<p style="text-align: center;">数据来源：Niagara Region，Niagara Health</p>


<script async src="https://www.googletagmanager.com/gtag/js?id=UA-156200375-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-156200375-2');
</script>

<button class="mdc-fab like" id="like" >加油 +1</button>

<?php
$date = new DateTime();
$timestamp = $date->getTimestamp();
?>

<script>
	req=new XMLHttpRequest();
	req.onreadystatechange=function(){
		if (this.readyState==4 & this.status==200) {
			$data=this.response.split("\n");
			console.log("Like: "+$data[1]);
			document.getElementById("like").innerHTML = decodeURI("%E5%8A%A0%E6%B2%B9%20")+"+1 | "+$data[1]+decodeURI("%E6%AC%A1");
		}
	};
	reqLike=new XMLHttpRequest();
	reqLike.onreadystatechange=function(){
		if (this.readyState==4 & this.status==200) {
			$data=this.response.split("\n");
			if($data[1]==-1){
				location.reload();
			}else{
				console.log("Like: "+$data[1]);
			document.getElementById("like").innerHTML = decodeURI("%E6%84%9F%E8%B0%A2%E6%82%A8%E7%9A%84%E5%8A%A0%E6%B2%B9%20%7C%20")+$data[1]+decodeURI("%E6%AC%A1");
			}
		}
	};
	req.open("POST","like.php");
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	req.send("add=0");

	document.getElementById("like").addEventListener("click", function(){
		reqLike.open("POST","like.php");
		reqLike.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		reqLike.send("add=<?php echo $timestamp*14/3*15 ?>");
	});
</script>

</body>
</html>