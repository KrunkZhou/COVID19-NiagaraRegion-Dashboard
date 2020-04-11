<?php
/**
 * KRUNK.CN KDB My Admin
 * @ Version: 1.4
 * @ Date: 2020/03/08
 * @ Website: https://api.krunk.cn/kdb
 * @ Dev Website: https://kblog.krunk.cn/view.php?post=kdb
 */

$kdbmyadmin_version = "1.4";

// User Config
$username = "demo"; //Login Username (Default: demo)
$password = md5("demo"); // Login Password MD5 Hash (Default: demo)
$kapi_pass = ""; // KAPI Pass on api.krunk.cn

// System Config
$kdbmyadmin_config_dir = "../kdb/"; // Default Dir
$kdbmyadmin_config_ex = "kdb"; // Default Extension
$kdbmyadmin_config_en = "false"; // Encrypted KDB is not supported for now
$kdbmyadmin_config_lock = true; // Lock config on login

// Customization
$kdbmyadmin_custom_title = "COVID-19 Niagara Data"; // Title
$kdbmyadmin_custom_notice = ""; // Notice on dashboard (Leave blank to disable)
$kdbmyadmin_custom_login_notice = ""; // Notice on login page (Leave blank to disable)
$kdbmyadmin_custom_footer = "Thanks for using KDB My Admin<br>"; // Footer

// Experimental Options
// $kdbmyadmin_config_dir_more = array("../kdb/kdb/"); // KDB Dir if need more

?>