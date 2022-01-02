<?php
/**
 * 尼亚加拉地区疫情监控 - 主页
 * Author: Krunk Zhou (https://krunk.cn/kblog1558.htm)
 * Version: 3.0 (Updated on 20/12/29)
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
	<title>尼亚加拉地区疫情实时数据 2020 - 2021</title>
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style-new.css">
	<script>
		// 更新提示
		// var level=3;
		// if(localStorage.getItem('notice')<level){
		// 	localStorage.setItem('notice', level);
		// 	location.href="?s=notice";
		// }else{
		// 	localStorage.setItem('notice', level);
		// }
	</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<div class='header'><div class="headercontainer">
<h1>尼亚加拉地区</h1><h6>Niagara Region COVID-19 统计</h6>
</div></div><div class="headerbottom"></div>
<?php

// 提示条
// if (isset($_GET['s'])){
// 	echo '<div class="alert"><span class="closebtn" onclick="location.href=\'index.php\';">&times;</span>';
// 	if ($_GET['s']=='new'){
// 		echo "已创建";
// 	}else if ($_GET['s']=='update'){
// 		echo "已更新";
// 	}else if ($_GET['s']=='error'){
// 		echo "更新失败";
// 	}else if ($_GET['s']=='nothing'){
// 		echo "已是最新";
// 	}else if ($_GET['s']=="notice"){
// 		echo "感谢催更的小伙伴们, 网站现在已经更新<br><br>11/16 至 12/28 有部分数据未能记录<br>导致图表可能有所偏差, 敬请谅解<br>PS: 终于考完啦!";
// 	}
// 	echo "</div>";
// }

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
</center><br>
<?php

//历史统计
echo "<h3>Niagara Region 疫情历史</h3><div id='container'><div id='wrap'><table class='KhistoryT'>
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
echo "</table><div id='gradient'></div></div><div id='read-more'></div>
 </div>";

?>
<br><br><br><br><br><br><br><br><br><br>
<center><a href="more-data.php"><button class="button">查看完整数据</button></a></center>
<br><br><hr><br>


<!--<center><a href="api.php"><button>了解如何调用我们的数据库</button></a></center>-->


<b><p style="text-align: center;margin: 10px;">注意:</p></b>
<p style="text-align: center;margin: 10px;">20/11/16 至 20/12/28 的部分数据未能记录, 未记录的值将显示为 0</p>
<p style="text-align: center;margin: 10px;">20/06/22 以及之前的 Death 数据来自于 Niagara Health 官网</p>
<p style="text-align: center;margin: 10px;">20/03/13 至 20/04/08  的确诊与恢复数据来自于 The Standard 新闻</p>
<p style="text-align: center;margin: 10px;">数据大约在每日中午 (多伦多时间) 更新</p>
<p style="text-align: center;margin: 10px;">建议在电脑端查看图表信息以获得更好的体验</p>

<br>

<center><a href="feedback.php"><button class="button">反馈</button></a></center>
<br><br>

<hr>

<br><p style="text-align: center;margin: 10px;">数据提供：Niagara Region，Niagara Health</p>
<p style="text-align: center;margin: 10px;">数据来源：Integrated Public Health Information System</p>

<br>

<p style="text-align: center;margin: 10px;">数据定义:</p>
<p style="text-align: left;margin: 20px;">Confirmed cases are laboratory-confirmed positive cases of COVID-19. This number does not include probable cases.</p>
<p style="text-align: left;margin: 20px;">Resolved cases are individuals who are no longer isolating or hospitalized due to COVID-19.</p>
<p style="text-align: left;margin: 20px;">Deceased cases are individuals who died while infected with COVID-19. This does not mean that COVID-19 was the cause of death. Death data is updated Monday, Wednesday and Friday.</p>
<p style="text-align: left;margin: 20px;">Active cases are individuals who are currently isolating or hospitalized due to COVID-19.</p>

<br><br>
<div class="footer"><div class="footercontainer">
	Stay Home and Be Safe. 2020 - 2021<br><a href="https://krunk.cn/kblog1558.html">KRUNK DESIGN</a><!--<div class="tooltip">数据来源(?)<span class="tooltiptext">Niagara Region<br>Niagara Health</span></div>-->
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

<button class="mdc-fab like" id="like" >加油 +1</button>

<?php
$date = new DateTime();
$timestamp = $date->getTimestamp();
?>

<script>
	// 实时更新已禁用
	// req=new XMLHttpRequest();
	// req.onreadystatechange=function(){
	// 	if (this.readyState==4 & this.status==200) {
	// 		$data=this.response.split("\n");
	// 		console.log("Update Request Complete: "+$data[1]);
	// 		if ($data[1]==1){
	// 			location.reload();
	// 		}
	// 	}
	// };
	// req.open("GET","update.php?api=t");
	// req.send();

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

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-156200375-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-156200375-2');
</script>

<script type="text/javascript">
//折叠
$(function(){
 	var slideHeight = 515; // px
	var defHeight = $('#wrap').height();
 	if(defHeight >= slideHeight){
  		$('#wrap').css('height' , slideHeight + 'px');
  		$('#read-more').append('<center><a class="button" href="#">点击展开</a></center>');
  		$('#read-more a').click(function(){
	   		var curHeight = $('#wrap').height();
	   		if(curHeight == slideHeight){
	    		$('#wrap').animate({
	     			height: defHeight
	    		}, "normal");
	    		$('#read-more a').html('点击隐藏');
	    		$('#gradient').fadeOut();
	   		}else{
	    		$('#wrap').animate({
	    			 height: slideHeight
	    		}, "normal");
	    		$('#read-more a').html('点击展开');
	    		$('#gradient').fadeIn();
	   		}
	   		return false;
  		});  
 	}
});
</script>

</html>