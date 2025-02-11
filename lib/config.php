<?
define(__INCLUDE_CONFIG_PHP,"TRUE");	

 
$site_userid = $_SESSION["site_userid"]; 
$site_username = $_SESSION["site_username"];  
$site_level = $_SESSION["site_level"];   
$board_passwd = $_SESSION["board_passwd"];  
$site_part =  ($_SESSION["site_part"]);  
$root_pass  =$_SERVER['DOCUMENT_ROOT'] ; //  dirname(__FILE__)  
require_once    $root_pass . "/../db/db.php";
 

$SENDMAIL_PATH    = "/usr/lib/sendmail";		
 
if(!defined(__root_pass)){
	define(__root_pass,"true");
	$temp_root_pass=realpath(__FILE__);
	if($temp_root_pass) $root_pass=str_ireplace("/lib/config.php","",$temp_root_pass);
	else $root_pass="";
} 

/*------------------------데이타베이스 접속, 함수 ---------------------------------*/

/*$dbp	=	@mysql_connect($host,$user,$passwd)or die("db connect err");;  //데이타베이스 접속
mysql_select_db($dataname,$dbp) or die("db select err");
 
mysql_query("set names utf8"); */
$dbp = @mysqli_connect($host,$user,$passwd, $dataname) or die("db connect err");  //데이타베이스 접속
mysqli_select_db($dbp,$dataname) or die("db select err");
mysqli_set_charset($dbp,"utf8");

function dbquery($qry)
{
	global $dbp;
	return mysqli_query($dbp,$qry);
}

function manydbquery($qry)
{
	global $dbp;
	$qry = explode(";\n",$qry);	 	
	for($j=0;$j<count($qry) -1 ;$j++)  {$ckreturn =  mysqli_query($dbp,$qry[$j]);}
	return $ckreturn ; 
}


function dbtotal($qry)
{
	global $dbp;
	$result = mysqli_query($dbp,$qry) or die("qry err. : $qry");
	$resulta = mysqli_fetch_array($result);
	return $resulta["total"];
}



function dbfield($qry)
{
	global $dbp;
	$result = mysqli_query($dbp,$qry) or die("qry err. : $qry");
	$resulta = mysqli_fetch_array($result);
	return $resulta["field"];
}






function dbfiledisnum($qry)
{
	global $dbp;
	$result = mysqli_query($dbp,$qry) or die("qry err. : $qry");
	$resulta = mysqli_fetch_array($result);
	return isnum($resulta["filed"]);
}



function dbfiled($qry)
{
	global $dbp;
	$result = mysqli_query($dbp,$qry) or die("qry err. : $qry");
	$resulta = mysqli_fetch_array($result);
	return $resulta["filed"];
}

 
function dbarray($qry)
{
	global $dbp; 
	$result = @mysqli_query($dbp,$qry) or die("qry err. : $qry"  );
	return mysqli_fetch_array($result);
}


function dbarray0($qry)
{
	global $dbp; 
	$result = @mysqli_query($dbp,$qry) or die("qry err. : $qry"  );
	$row =  mysqli_fetch_array($result);
	return $row[0] ; 
}






// mysql_affected_rows function
function dbaffected($qry)
{
	global $dbp; 
	$result = @mysqli_query($dbp,$qry) or die("qry err. : $qry");
	return mysqli_affected_rows( );
}
 
 
 if ($site_level =='' || !$site_level  ) {
	$site_level	= 10; 
	 $_SESSION["site_level"] =10 ; 
	//@session_unregister("site_level") or die("session_unregister err");	
	unset($_SESSION["site_level"]);
}
$site_gg = $_SESSION["site_gg"];   
if (!$site_gg){
		$site_gg  =  chr(rand(65, 90)).chr(rand(65, 90)).chr(rand(65, 90)).time() ; 
		
	 $_SESSION["site_gg"] =$site_gg ; 

		//@session_register("site_gg") or die("session_register err");

}
?>