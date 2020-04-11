<?php
/**
 * 尼亚加拉地区疫情监控 - 主页
 * Author: Krunk Zhou (https://krunk.cn/kblog1558.htm)
 * Version: 1.0
 * Web: https://niagara.krunk.cn/
 * GitHub: https://github.com/KrunkZhou/COVID19-NiagaraRegion-Dashboard
 * Licence: MIT Licences
 */

include('kdb.class.php');
$db = new kdb();

$cases = $db->find('covid-19-niagara'); //查询数据库
asort($cases); //排序 图表
$cases_r=array_reverse($cases); //排序 历史

$chartLables=""; //Date
$chartData1=""; //Cases
$chartData2=""; //Recovered
$chartData3=""; //Death
$chartData4=""; //Active
$chartData5=""; //new
$case_holder=0;
$new_cases=array();
if ($cases){
	foreach ($cases as $case) {
		$chartLables=$chartLables."'".substr($case['date'],5)."',";
		$chartData1=$chartData1.$case['strCaseNumbers'].",";
		$chartData2=$chartData2.$case['spnResolvedCases'].",";
		$chartData3=$chartData3.$case['death'].",";
		$chartData4=$chartData4.($case['strCaseNumbers']-$case['spnResolvedCases']-$case['death']).",";
		$chartData5=$chartData5.($case['strCaseNumbers']-$case_holder).",";
		array_push($new_cases, ($case['strCaseNumbers']-$case_holder));
		$case_holder=$case['strCaseNumbers'];
	}
}
$new_cases=array_reverse($new_cases); //新增确诊

?>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>尼亚加拉地区疫情实时数据</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<div class='header'><div class="headercontainer">
<h1>尼亚加拉地区</h1><h6>Niagara Region COVID-19 统计</h6>
</div></div><div class="headerbottom"></div>
<?php

// 提示条
if (isset($_GET['s'])){
	echo '<div class="alert"><span class="closebtn" onclick="location.href=\'index.php\';">&times;</span>';
	if ($_GET['s']=='new'){
		echo "已创建";
	}else if ($_GET['s']=='update'){
		echo "已更新";
	}else if ($_GET['s']=='error'){
		echo "更新失败";
	}else if ($_GET['s']=='nothing'){
		echo "已是最新";
	}
	echo "</div>";
}

//今日统计
echo "<table class='KhistoryT'>
<tr><th>累计确诊</th><th>现存</th><th>痊愈</th><th>死亡</th><th>新增</th></tr>";
$counter=0;
if ($cases_r){
	foreach ($cases_r as $case) {
		$c=$case['strCaseNumbers']-$case['spnResolvedCases']-$case['death'];
		echo "<tr>";
		echo '<th style="color: rgb(174, 33, 44);">' . '' . $case['strCaseNumbers'] . '</th>';
		echo '<th style="color: rgb(247, 76, 49);">' . '' . $c . '</th>';
		echo '<th style="color: rgb(40, 183, 163);">' . '' . $case['spnResolvedCases'] . '</th>';
		echo '<th style="color: rgb(93, 112, 146);">' . '' . $case['death'] . '</th>';
		echo '<th style="color: rgb(247, 130, 7);">' . '' . $new_cases[$counter] . '</th>';
		$counter++;
		echo '</tr>';
		$update_time_latest=$case['update_time'];
		break;
	}
}
echo "</table><center><small>更新时间 ".$update_time_latest."<small></center><br>";

?>
<center>
<div class="chart-container" >
	<canvas id="krunkChart"></canvas><br>
</div>
</center>
<?php

//历史统计
echo "<h3>Niagara Region 疫情历史</h3><table class='KhistoryT'>
<tr><th>历史</th><th style=\"color: rgb(174, 33, 44);\">确诊</th><th style=\"color: rgb(40, 183, 163);\">痊愈</th><th style=\"color: rgb(93, 112, 146);\">死亡</th><th style=\"color: rgb(247, 130, 7);\">新增</th></tr>";
$counter=0;
if ($cases_r){
	foreach ($cases_r as $case) {
		echo "<tr>";
		echo '<th><div class="tooltip">' . substr($case['date'],5) . '<span class="tooltiptext">更新时间<br>'.$case['update_time'].'</span></div></th>';
		echo '<th>' . '' . $case['strCaseNumbers'] . '</th>';
		echo '<th>' . '' . $case['spnResolvedCases'] . '</th>';
		echo '<th>' . '' . $case['death'] . '</th>';
		echo '<th>' . '' . $new_cases[$counter] . '</th>';
		$counter++;
		echo '</tr>';
	}
}
echo "</table>";

?>
<br>
<center><a href="update.php"><button class="button">刷新</button></a></center>
<!--<center><a href="api.php"><button>了解如何调用我们的数据库</button></a></center>-->
<br><br><p style="text-align: center;">数据来源：Niagara Region，Niagara Health</p><br><br>
<div class="footer"><div class="footercontainer">
	Stay Home and Be Safe. <br><a href="https://krunk.cn/kblog1558.html">KRUNK DESIGN</a><!--<div class="tooltip">数据来源(?)<span class="tooltiptext">Niagara Region<br>Niagara Health</span></div>-->
</div></div>

<script>
var krunkChart = new Chart(document.getElementById('krunkChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: [<?php echo $chartLables; ?>],
        datasets: [{
            label: '新增',
            data: [<?php echo $chartData5; ?>],
            backgroundColor: [
                'rgba(10, 10, 255, 0.0)'
            ],
            borderColor: [
                'rgba(10, 10, 255, 1)'
            ],
            borderWidth: 1
        },{
            label: '累计确诊',
            data: [<?php echo $chartData1; ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        },{
            label: '痊愈',
            data: [<?php echo $chartData2; ?>],
            backgroundColor: [
                'rgba(255, 155, 10, 0.4)'
            ],
            borderColor: [
                'rgba(255, 155, 10, 1)'
            ],
            borderWidth: 1
        },{
            label: '死亡',
            data: [<?php echo $chartData3; ?>],
            backgroundColor: [
                'rgba(0, 0, 0, 0.9)'
            ],
            borderColor: [
                'rgba(0, 0, 0, 0.9)'
            ],
            borderWidth: 1
        },{
            label: '现存确诊',
            data: [<?php echo $chartData4; ?>],
            backgroundColor: [
                'rgba(10, 255, 255, 0.0)'
            ],
            borderColor: [
                'rgba(10, 255, 255, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>
<script>
	req=new XMLHttpRequest();
	req.onreadystatechange=function(){
		if (this.readyState==4 & this.status==200) {
			$data=this.response.split("\n");
			console.log("Update Request Complete: "+$data[1]);
			if ($data[1]==1){
				location.reload();
			}
		}
	};
	//console.log(localWishlist);
	req.open("GET","update.php?api=t");
	req.send();

	req=new XMLHttpRequest();
	req.onreadystatechange=function(){
		if (this.readyState==4 & this.status==200) {
			$data=this.response.split("\n");
			console.log("Like: "+$data[1]);
			document.getElementById("like").innerHTML = "加油 +1 | "+$data[1]+"次";
		}
	};
	//console.log(localWishlist);
	req.open("GET","like.php?add=0");
	req.send();

	function like(){
		req=new XMLHttpRequest();
		req.onreadystatechange=function(){
			if (this.readyState==4 & this.status==200) {
				$data=this.response.split("\n");
				console.log("Like: "+$data[1]);
				document.getElementById("like").innerHTML = "感谢您的加油 | "+$data[1]+"次";
			}
		};
		//console.log(localWishlist);
		req.open("GET","like.php?add=1");
		req.send();
	}
</script>

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-156200375-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-156200375-2');
</script>

<button class="mdc-fab like" id="like" onclick="like();">加油 +1</button>
</html>