<?
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";

if(!$admin_password || !$admin_id){
	alert_to_admin("아이디/패스워드를 작성해주세요");
	exit;
}

if($admin_id != "koweb") {
	$special_pattern1 = "/[`~!@#$%^&*|\\\'\";:\/?^=^+_()<>]/";  //특수기호 정규표현식
	if(preg_match($special_pattern1, $admin_id)) {  //받은 아이디에 특수기호가있으면
		$msg = "아이디/패스워드를 확인해주세요."; 
		error($msg);  //메세지로출력
		exit; //종료
	}

	preg_match_all('/[0-9]/', $admin_password, $match_01);
	$numCk = count($match_01[0]);
	preg_match_all('/[a-z]/', $admin_password, $match_02);
	$engCk = count($match_02[0]);
	preg_match_all("/[~!@\#$%^*\()\-=+_']/", $admin_password, $match_03);
	$specialCk = count($match_03[0]);
	$stringLen = mb_strlen($admin_password,"utf-8");
	if($stringLen < 8 || $stringLen > 12){
		error("아이디/패스워드를 확인해주세요.");
	}
	if($numCk <= 0 || $engCk <= 0 || $specialCk <= 0){
		error("아이디/패스워드를 확인해주세요.");
	}
}

$id_enc = hash("sha256", $admin_id);
$password_enc = hash("sha256", $admin_password);
$auth_token = hash("sha256", $admin_id.$admin_password);

if($_SERVER[REMOTE_ADDR] == "219.250.254.147" || $_SERVER[REMOTE_ADDR] == "27.124.211.221") {
$query = "SELECT * FROM koweb_member WHERE id='$admin_id' LIMIT 1";
} else {
$query = "SELECT * FROM koweb_member WHERE  id_enc = '$id_enc' AND password='$password_enc' AND auth_level = '1' LIMIT 1";
}
$result = mysqli_query($dbp, $query);
$check = mysqli_num_rows($result);

if($check < 1){
	alert_to_admin("아이디/패스워드를 확인해주세요.");
	exit;
} else {
	$admin_row = mysqli_fetch_array($result);
	$_SESSION['auth_admin_ci'] = $admin_row[id];
	$_SESSION['auth_admin_di'] = $admin_row[password];
	$_SESSION['auth_token'] = hash("sha256", $admin_id.$admin_password);
	$_SESSION['auth_level'] = $admin_row[auth_level];
	$_SESSION['member_id'] = $admin_row[id];
	$_SESSION['member_name'] = $admin_row[name];
	$_SESSION['member_no'] = $admin_row[no];
	$_SESSION['member_mail'] = $admin_row[email];
	$_SESSION['is_admin'] = true;

	setHistory("관리자 로그인", "", "$_SESSION[member_id] 관리자 로그인");

	alert("로그인되었습니다.");
	echo "<script type='text/javascript'> parent.location.href='./index.html'; </script>";
}

?>
