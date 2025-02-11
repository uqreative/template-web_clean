<?
//ini_set('display_errors', '1'); 
//ini_set('error_reporting', 'E_ALL' ); 
//error_reporting( E_ALL );
//ini_set( "display_errors", 1 );
@ session_start(); 
@ header('Pragma: no-cache');  
@ header('Cache-Control: max-age=1, s-maxage=1, no-cache, must-revalidate'); 
@ header('Content-type: text/html; charset=utf-8');  
 
include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
$adbgcolor = "#32A49E";
$load = 0 ;


//$referer_num = @mysqli_result(mysqli_query($dbp, "SELECT COUNT(*) FROM memberLog"), 0, 0);
$result = @mysqli_query($dbp, "SELECT COUNT(*) FROM memberLog");
$row = mysqli_fetch_array($result);
$referer_num = $row[0];

if ($referer_num > 100000) {
	$refer_sql = mysqli_query($dbp, "SELECT no FROM memberLog ORDER BY no ASC LIMIT 1000");

	while ($refer_row = mysqli_fetch_array($refer_sql)) {
		mysqli_query($dbp, "DELETE FROM memberLog WHERE no=$refer_row[no]");
	}
}
?>
