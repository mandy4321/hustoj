<?php
	$cache_time=10;
	$OJ_CACHE_SHARE=false;
	require_once('./include/cache_start.php');
    require_once('./include/db_info.inc.php');
	require_once('./include/setlang.php');
	$view_title= "Welcome To Online Judge";
	
require_once("./include/const.inc.php");
if (!isset($_GET['sid'])){
	echo "No such code!\n";
	require_once("oj-footer.php");
	exit(0);
}
function is_valid($str2){
    $n=strlen($str2);
    $str=str_split($str2);
    $m=1;
    for($i=0;$i<$n;$i++){
    	if(is_numeric($str[$i])) $m++;
    }
    return $n/$m>3;
}


$ok=false;
$id=strval(intval($_GET['sid']));
$sql="SELECT * FROM `solution` WHERE `solution_id`='".$id."'";
$result=mysql_query($sql);
$row=mysql_fetch_object($result);
if ($row && $row->user_id==$_SESSION['user_id']) $ok=true;
if (isset($_SESSION['source_browser'])) $ok=true;
$view_reinfo="";
if ($ok==true){
	if($row->user_id!=$_SESSION['user_id'])
		$view_mail_link= "<a href='mail.php?to_user=$row->user_id&title=$MSG_SUBMIT $id'>Mail the auther</a>";
	mysql_free_result($result);
	$sql="SELECT `error` FROM `runtimeinfo` WHERE `solution_id`='".$id."'";
	$result=mysql_query($sql);
	$row=mysql_fetch_object($result);
	if($row&&is_valid($row->error))	
		$view_reinfo= htmlspecialchars(str_replace("\n\r","\n",$row->error));
	
        
	mysql_free_result($result);
}else{
	mysql_free_result($result);
	$view_errors= "I am sorry, You could not view this message!";
	require("template/".$OJ_TEMPLATE."/error.html");
	exit(0);
	
}

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/reinfo.html");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
	require_once('./include/cache_end.php');
?>

