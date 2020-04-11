<?php
/**
 * KRUNK.CN KDB My Admin
 * @ Website: https://api.krunk.cn/kdb
 * @ Dev Website: https://kblog.krunk.cn/view.php?post=kdb
 */
include('function.php');

if (!kdbmyadmin_check_login()){
	header("Location: index.php");
	die("Premission Denined");
}
$db=kdb_set_db();

echo '<html><head>
<link rel="stylesheet" type="text/css" href="css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>'.$kdbmyadmin_custom_title.'</title>
</head>';
echo "<div class='header'><h1>".$kdbmyadmin_custom_title."</h1>";
echo "<a href='kdbmyadmin.php'><button>Home</button></a><a href='index.php?logout=true'><button class='delete-button'>Signout</button></a></div><br>";

echo "<div class='main-container'>";

$dir=$_SESSION['dir'];
$ex=$_SESSION['ex'];
$en=$_SESSION['en'];
$all_database=glob($dir."/*.".$ex);

// Mutliple Database Test
// $more_database=array();
// foreach ($kdbmyadmin_config_dir_more as $value) {
// 	$database=glob($value."/*.".$ex);
// 	$more_database=array_merge($more_database, $database);
// }
// $all_database=array_merge($all_database,$more_database);

$table_name_array=array();
foreach ($all_database as $table) {
	$table_name=str_replace("/", "", $table);
	$dir_n=str_replace("/", "", $dir);
	$table_name=str_replace($dir_n, "", $table_name);
	$table_name=str_replace($ex, "", $table_name);
	$table_name=str_replace(".", "", $table_name);
	array_push($table_name_array, $table_name);
}

//Delete Whole Table
if (isset($_GET['dltable'])){
	$db->delete_all($_GET['dltable']);
	header("Location: kdbmyadmin.php");
}

//Table Name List
echo "<div class='table-name-container'><h2>Tables:</h2><div class='table-name'>";
echo "<form action='?insert=true'><input type='text' name='table' required><br><button>Create New Table</button></form>";
foreach ($table_name_array as $table) {
	echo "<a href='?table=".$table."'><button class='table-name-button'>".$table."</button></a> <button class='delete-button' onclick=\"delete_table('".$table."');\">-</button><br>";
}
echo "<script>
function delete_table(table){
	if (confirm('Delete This Table ( '+table+' )?')) {
		window.location.href = '?dltable='+table;
	}
}
</script>";
echo "</div></div>";

echo "<div class='table-action-container'>";

//Table Action
if (isset($_GET['table'])){
	if (isset($_GET['edit'])){ //Edit
		if (isset($_POST['kdbmyadmin_edit_12345678'])){
			$data=array();
			foreach ($_POST as $key => $value) {
				if ($value!="kdbmyadmin_insert_12345678"&&$key!="kdbmyadmin_insert_12345678"){
					if (substr($value, 0,10)=="kdb_array:"){
						$value_n=json_decode(stripslashes(substr($value, 10)),true);
					}else{
						$value_n=htmlspecialchars($value);
					}
					//var_dump(json_decode(substr($value, 10),true));
				    $data[htmlspecialchars($key)]=$value_n;
				}
			}
			$data_n=array();
			foreach ($data as $key1 => $value1) {
				if ($key1[0]=="k"&&$key1[1]=="_"&&$value1!=""){
					foreach ($data as $key2 => $value2){
						if ($key2[0]=="d"&&$key2[1]=="_"&&$key1[2]==$key2[2]){
							$data_n[$value1]=$value2;
						}
					}
				}
			}
			//$db->delete($_GET['table'],$_GET['edit']);
			$db->reset($_GET['table'],$data_n,$_GET['edit']);
			echo "<div class='notice'>Done</div>";
			//var_dump($data_n) ;
			//header("Location: ".$_SERVER['HTTP_REFERER']);
		}
		echo "<h2>Edit Table: <a href='kdbmyadmin.php?table=".$_GET['table']."'>".$_GET['table']."</a></h2><h3>Key: <a href='kdbmyadmin.php?table=".$_GET['table']."#".$_GET['edit']."'>".$_GET['edit']."</a></h3>";
		$item =  $db->find_one($_GET['table'],$_GET['edit']);
		echo "<form method='POST'><input type='hidden' name='kdbmyadmin_edit_12345678' value='kdbmyadmin_edit_12345678'><div id='insert_form_container'>";
		$table_id=0;
		foreach ($item as $value_key => $value) {
			if (is_array($value)){
	 			$value_n="kdb_array:".json_encode($value);
	 		}else{
	 			$value_n=$value;
	 		}
	 		echo "<input type='text' name='k_".$table_id."' value='".$value_key."'> => <input type='text' name='d_".$table_id."' value='".$value_n."'><br>";
	 		$table_id++;
	 	}
	 	echo "</div><br><button type='button' onclick='newinput()'>+</button><br><br><input type='submit'></form>";
	 	echo "
<script>
var id = ".$table_id.";
function newinput() {
  	var node = document.createElement('input');
	node.setAttribute('type', 'text');
	node.setAttribute('name', 'k_'+id);
  	document.getElementById('insert_form_container').appendChild(node);
  	var node = document.createElement('label');
	node.innerHTML=' => ';
  	document.getElementById('insert_form_container').appendChild(node);
  	var node = document.createElement('input');
	node.setAttribute('type', 'text');
	node.setAttribute('name', 'd_'+id);
  	document.getElementById('insert_form_container').appendChild(node);
  	document.getElementById('insert_form_container').appendChild(document.createElement('br'));
  	id += 1;
}
</script>";
		exit(0);
	}else if (isset($_GET['delete'])){ //Delete
		$db->delete($_GET['table'],$_GET['delete']);
		header("Location: ".$_SERVER['HTTP_REFERER']);
		exit(0);
	}else if (isset($_GET['insert'])){ //Insert
		if ($_GET['insert']=="true"&&isset($_POST['kdbmyadmin_insert_12345678'])){
			$data=array();
			foreach ($_POST as $key => $value) {
				if (substr($value, 0,10)=="kdb_array:"){
					$value_n=json_decode(stripslashes(substr($value, 10)),true);
				}else{
					$value_n=htmlspecialchars($value);
				}
				//var_dump(json_decode(substr($value, 10),true));
				$data[htmlspecialchars($key)]=$value_n;
			}
			$data_n=array();
			foreach ($data as $key1 => $value1) {
				if ($key1[0]=="k"&&$key1[1]=="_"&&$value1!=""){
					foreach ($data as $key2 => $value2){
						if ($key2[0]=="d"&&$key2[1]=="_"&&$key1[2]==$key2[2]){
							$data_n[$value1]=$value2;
						}
					}
				}
			}
			$id=$db->insert($_GET['table'],$data_n,$_GET['edit']);
			if (!empty($id)){
				header("Location: ?table=".$_GET['table']."&edit=".$id."&n=done");
			}else{
				header("Location: ?table=".$_GET['table']."&insert=true&n=empty");
			}
		}else if (isset($_GET['insert'])&&$_GET['n']=="done"){
			echo "<div class='notice'>Done</div>";
		}else if (isset($_GET['insert'])&&$_GET['n']=="empty"){
			echo "<div class='notice nerror'>Empty Item</div>";
		}
		$table_id=2;
		echo "<h2>Insert to table: ".$_GET['table']."</h2>";
		echo "<form class='insert_form' method='POST'><input type='hidden' name='kdbmyadmin_insert_12345678' value='kdbmyadmin_insert_12345678'><!--<br><input type='submit'><br><br>--><div id='insert_form_container'>";
	 	echo "<input type='text' name='k_1'><label> => </label><input type='text' name='d_1'><br>";
	 	echo "</div><br><button type='button' onclick='newinput()'>+</button><br>";
	 	echo "<br><input type='submit'></form>";
	 	echo "
<script>
var id = ".$table_id.";
function newinput() {
  	var node = document.createElement('input');
	node.setAttribute('type', 'text');
	node.setAttribute('name', 'k_'+id);
  	document.getElementById('insert_form_container').appendChild(node);
  	var node = document.createElement('label');
	node.innerHTML=' => ';
  	document.getElementById('insert_form_container').appendChild(node);
  	var node = document.createElement('input');
	node.setAttribute('type', 'text');
	node.setAttribute('name', 'd_'+id);
  	document.getElementById('insert_form_container').appendChild(node);
  	document.getElementById('insert_form_container').appendChild(document.createElement('br'));
  	id += 1;
}
</script>";
		exit(0);
	}
}

// Selected Table
if (isset($_GET['table'])){
	echo "<h2>Selected Table: ".$_GET['table']."</h2><a href='?table=".$_GET['table']."&insert=true'><button>Insert</button></a><br><br><div class='table-content'>";
	$items =  $db->find($_GET['table']);

	 foreach($items as $key => $item){
	 	echo "<div id='".$key."'><small><b>Key: </b>".$key."</small><br><table class='table-content-table'>";
	 	foreach ($item as $value_key => $value) {
	 		if (is_array($value)){
	 			$value_n="kdb_array:".json_encode($value);
	 		}else{
	 			$value_n=$value;
	 		}
	 		if (filter_var($value, FILTER_VALIDATE_IP)){
	 			$value_n="<a href='".$db->kapi_get_geoip_address($value)."'>".$value."</a>";
	 		}
	 		echo "<tr class='table-content-table'><th class='table-content-table'>".$value_key."</th><th class='table-content-table'> => </th><th class='table-content-table'>".$value_n."</th></tr>";
	 	}
	    echo "</table><a href='?table=".$_GET['table']."&edit=".$key."'><button>Update</button></a><a href='?table=".$_GET['table']."&delete=".$key."'><button class='delete-button'>Delete</button></a></div><br><hr><br>";
	 }
	echo "</div>";
}else{
	//Database Main Page
	echo "<center><h2>Welcome to KDB My Admin</h2><h3>Select a table from the left</h3>";
	$kdb_version=$db->get_kdb_version();
	echo "<p>Server IP: <a href='".$db->kapi_get_geoip_address(get_server_ip())."'>".get_server_ip()."</a></p>";
	echo "<p>KDBMyAdmin Ver: ".$kdbmyadmin_version."<p>";
	echo "<p>KDB Ver: ".$kdb_version."<p>";
	echo "<hr>";
	echo "<p>Database: ".count($all_database)."<p>";
	echo "<p>Dir: ".$dir."<p>";
	echo "<p>Extension: ".$ex."<p>";
	echo "<p>Encrypt: ".$en."<p>";
	if ($kdbmyadmin_custom_notice!=''){
		echo "<hr>";
		echo $kdbmyadmin_custom_notice;
	}
	echo "</center>";
}

echo "</div>";

echo "</div>
<div class='footer'>".$kdbmyadmin_custom_footer."KDB &copy 2019 - ".date("Y")."<br>Powered by KRUNK.CN</div></html>";

?>
