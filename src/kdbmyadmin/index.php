<?php
/**
 * KRUNK.CN KDB My Admin
 * @ Website: https://api.krunk.cn/kdb
 * @ Dev Website: https://kblog.krunk.cn/view.php?post=kdb
 */
include('function.php');

echo '<html><head>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>'.$kdbmyadmin_custom_title.'</title>
</head>';

if (isset($_POST['un'])&&isset($_POST['pw'])){
	$un=$_POST['un'];
	$pw=$_POST['pw'];
	$server=$_SERVER["SERVER_NAME"];
	if (!$kdbmyadmin_config_lock&&isset($_POST['dir'])&&isset($_POST['ex'])&&isset($_POST['en'])){
		$dir=$_POST['dir'];
		$ex=$_POST['ex'];
		$en=$_POST['en'];
	}else{
		$dir=$kdbmyadmin_config_dir;
		$ex=$kdbmyadmin_config_ex;
		$en=$kdbmyadmin_config_en;
	}
	if (kdbmyadmin_login($un,$pw)){
		$_SESSION['un']=$un;
		$_SESSION['dir']=$dir;
		$_SESSION['ex']=$ex;
		$_SESSION['en']=$en;
		$_SESSION['server']=$server;
	}else{
		header("Location: index.php?s=error");
	}
}else if(isset($_GET['logout'])){
	kdbmyadmin_logout();
	header("Location: index.php?s=logedout");
}

if (!kdbmyadmin_check_login()){
?>

<center>
<div class='header'>
<h1><?php echo $kdbmyadmin_custom_title ?></h1>
</div>
<?php
if ($kdbmyadmin_custom_login_notice){
	echo "<br>".$kdbmyadmin_custom_login_notice;
}
?>
<br>
<form class="kdbmyadmin_login_form" method="POST">
Username:<br>
<input type="text" name="un"><br>
Password:<br>
<input type="password" name="pw"><br>
<?php 
if (!$kdbmyadmin_config_lock){ 
	echo '<button type="button" class="collapsible">Advanced</button>
<div class="collapsible-content"><br>
DIR:<br>
<input type="text" name="dir" value="'. $kdbmyadmin_config_dir .'" required><br>
Extension:<br>
<input type="text" name="ex" value="'. $kdbmyadmin_config_ex .'" required><br>
Encrypt:<br>
<input type="text" name="en" value="'. $kdbmyadmin_config_en .'" readonly><br>
</div><br>';
}
?>
<br>
<input type="submit" class="btn" value="Submit">
</form>
<?php
if (isset($_GET['s'])){
	if ($_GET['s']=='error'){
		echo "<div class='notice nerror login-notice'>Username or Password Error</div>";
	}else if ($_GET['s']=='logedout'){
		echo "<div class='notice login-notice'>You have been loged out</div>";
	}
}
?>
</center>

<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>

<?php
}else{
	header("Location: kdbmyadmin.php");
}
?>
</html>