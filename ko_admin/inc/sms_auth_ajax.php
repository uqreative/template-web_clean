<?
/*----------------------------------------------------------------------------*/
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";
//include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";
/*----------------------------------------------------------------------------*/
$result = array();

$check_tmp = mysqli_num_rows(mysqli_query($dbp, "SELECT * FROM koweb_auth_sms WHERE check_phone = '$data_send' AND check_code='$sms_auth'"));
if($check_tmp <= 0){
	$result = array( "result" => "false", "message" => "인증번호를 다시 확인해주세요.", "tag" => "<a href=\"#\" onclick=\"sms_auth();\" class=\"button\">확인</a>" );

} else {
	$_SESSION['auth_phone'] = $data_send;
	$_SESSION['auth_sms'] = $sms_auth;
	$del_query = "DELETE FROM koweb_auth_sms WHERE check_phone = '$data_send' AND check_code='$sms_auth'";
	$todo = mysqli_query($dbp, $del_query);
	$result = array( "result" => "true", "message" => "인증에 성공하였습니다.", "tag" => "인증성공" );
}

echo json_encode($result);
?>
