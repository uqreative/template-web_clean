<?
//  ID 정리
$reg_date = date("Y-m-d H:i:s");
$phone = $phone1."-".$phone2."-".$phone3;
if($email2) $email3 = $email2;
$email = $email1."@".$email3;
$email = preg_replace("/\s+/", "", $email);
$id_enc = hash("sha256", $id);

if($password){
	$password_enc = hash("sha256", $password);
	$auth_token = hash("sha256", $id.$password);
}

//회원가입
if($mode == "join_proc"){
	if($site[use_member_okey] == "Y"){
		$level = "99";
	} else {
		$level = $site[member_level];
	}
	if($_SESSION[rand_auth] != $rand_auth_){
		error("스팸방지번호가 일치하지 않습니다.");
	}
	//  ID 중복 체크
	$check = mysqli_fetch_array(mysqli_query($dbp, "SELECT COUNT(*) FROM koweb_member WHERE id='$id'"));
	if ($check[0])	error("중복된  ID 입니다.");

	$check2 = mysqli_fetch_array(mysqli_query($dbp, "SELECT COUNT(*) FROM koweb_member WHERE email='$email'"));
	if ($check2[0])	error("중복된 EMAIL 입니다.");

	$check3 = mysqli_fetch_array(mysqli_query($dbp, "SELECT COUNT(*) FROM koweb_member WHERE phone='$phone'"));
	if ($check3[0])	error("중복된 연락처 입니다.");

	$type = "web";

	$special_pattern1 = "/[`~!@#$%^&*|\\\'\";:\/?^=^+_()<>]/";  //특수기호 정규표현식
	if(preg_match($special_pattern1, $id)) {  //받은 아이디에 특수기호가있으면
		$msg = "아이디에 특수문자는 사용할 수 없습니다."; 
		error($msg);  //메세지로출력
		exit; //종료
	}

	preg_match_all('/[0-9]/', $password, $match_01);
	$numCk = count($match_01[0]);
	preg_match_all('/[a-z]/', $password, $match_02);
	$engCk = count($match_02[0]);
	preg_match_all("/[~!@\#$%^*\()\-=+_']/", $password, $match_03);
	$specialCk = count($match_03[0]);
	$stringLen = mb_strlen($password,"utf-8");
	if($stringLen < 8 || $stringLen > 12){
		error("비밀번호는 8~12자 사이로 입력해 주세요.");
	}
	if($numCk <= 0 || $engCk <= 0 || $specialCk <= 0){
		error("비밀번호는 영문,특수문자,숫자 조합 형태로 입력해 주세요.");
	}

	// 정보 입력
	@mysqli_query($dbp, "INSERT INTO koweb_member VALUES('','$type', '$name', '$dept', '$_SESSION[CI]', '$_SESSION[DI]', '$id', '$password_enc', '$id_enc', '$auth_token', '$level', '$phone', '$tel', '$email', '$birthday', '$gender', '$zip', '$address1', '$address2', '$is_admin', '$company', '$company_tel', '$company_zip', '$company_address1', '$company_address2', '$reg_date', 'Y', '$ip'  )");

	$alert_txt = "등록";

} else if ($mode == "modify_proc"){

	if($password){
		preg_match_all('/[0-9]/', $password, $match_01);
		$numCk = count($match_01[0]);
		preg_match_all('/[a-z]/', $password, $match_02);
		$engCk = count($match_02[0]);
		preg_match_all("/[~!@\#$%^*\()\-=+_']/", $password, $match_03);
		$specialCk = count($match_03[0]);
		$stringLen = mb_strlen($password,"utf-8");
		if($stringLen < 8 || $stringLen > 12){
			error("비밀번호는 8~12자 사이로 입력해 주세요.");
		}
		if($numCk <= 0 || $engCk <= 0 || $specialCk <= 0){
			error("비밀번호는 영문,특수문자,숫자 조합 형태로 입력해 주세요.");
		}

		$auth_token = hash("sha256", $_SESSION[member_id].$password);
		$add_query = "password='$password_enc', auth_token='$auth_token',";
	}

	if($change_mail == "Y"){
		$check2 = mysqli_fetch_array(mysqli_query($dbp, "SELECT COUNT(*) FROM koweb_member WHERE email='$email',"));
		if ($check2[0])	error("중복된 EMAIL 입니다.");
		else $add_query_email = "email='$email',";
	}
	if($change_phone == "Y"){
		$check3 = mysqli_fetch_array(mysqli_query($dbp, "SELECT COUNT(*) FROM koweb_member WHERE phone='$phone',"));
		if ($check3[0])	error("중복된 연락처 입니다.");
		else $add_query_phone = "phone='$phone',";
	}


	@mysqli_query($dbp, "UPDATE koweb_member  SET name='$name', dept='$dept', $add_query $add_query_phone tel='$tel', $add_query_email birthday='$birthday', gender= '$gender' , zip='$zip', address1='$address1', address2='$address2', company='$company', company_tel='$company_tel', company_zip='$company_zip', company_address1 = '$company_address1', company_address2 = '$company_address2', reg_date='$reg_date' WHERE id='$_SESSION[member_id]'");
	$alert_txt = "수정";

//로그인
} else if ($mode == "login_proc") {
	if(!$password|| !$id){
		error("아이디/패스워드를 작성해주세요");
		exit;
	}

	if($id != "koweb") {
		$special_pattern1 = "/[`~!@#$%^&*|\\\'\";:\/?^=^+_()<>]/";  //특수기호 정규표현식
		if(preg_match($special_pattern1, $id)) {  //받은 아이디에 특수기호가있으면
			$msg = "아이디/패스워드를 확인해주세요."; 
			error($msg);  //메세지로출력
			exit; //종료
		}

		preg_match_all('/[0-9]/', $password, $match_01);
		$numCk = count($match_01[0]);
		preg_match_all('/[a-z]/', $password, $match_02);
		$engCk = count($match_02[0]);
		preg_match_all("/[~!@\#$%^*\()\-=+_']/", $password, $match_03);
		$specialCk = count($match_03[0]);
		$stringLen = mb_strlen($password,"utf-8");
		if($stringLen < 8 || $stringLen > 12){
			error("아이디/패스워드를 확인해주세요.");
		}
		if($numCk <= 0 || $engCk <= 0 || $specialCk <= 0){
			error("아이디/패스워드를 확인해주세요.");
		}
	}

	$query = "SELECT * FROM koweb_member WHERE id_enc = '$id_enc' AND password='$password_enc' LIMIT 1";
	//echo $query;
	//exit;
	$result = mysqli_query($dbp, $query);
	$check = mysqli_num_rows($result);

	if($check < 1){
		error("아이디/패스워드를 확인해주세요.");
		exit;
	} else {
		$row = mysqli_fetch_array($result);
		if($row[state] != "Y"){
			error("사용이 정지 된 아이디입니다. 관리자에게 문의하세요.");
			exit;
		}
		$_SESSION['auth_admin_ci'] = $row[id];
		$_SESSION['auth_admin_di'] = $row[password];
		$_SESSION['auth_token'] = hash("sha256", $id.$password);
		$_SESSION['auth_level'] = $row[auth_level];
		$_SESSION['member_id'] = $row[id];
		$_SESSION['member_name'] = $row[name];
		$_SESSION['member_no'] = $row[no];

		// 관리자 계정일 경우에 사용자단에서 비밀글 보기위해 설정
		if($row[no] == "2" || $row[no] == "37"){
			$_SESSION['is_admin'] = true;
		}

		$_SESSION['CI'] = $row[CI];
		$_SESSION['DI'] = $row[DI];

	}
	$alert_txt = "로그인";
	?>
		<script type="text/javascript">
			alert("<?=$alert_txt?> 되었습니다.");
			location.href = "/";
		</script>
	<?
//회원탈퇴
} else if($mode == "secession") {

	if(isblank($_SESSION[member_id])) error("로그인 해주세요.");
	$query = "SELECT * FROM koweb_member WHERE id = '$_SESSION[member_id]'";
	$result = mysqli_query($dbp, $query);
	$row = mysqli_fetch_array($result);
	if(!$row[0]) {
		error("비정상적인 접근입니다.");
		exit;
	}
	//@mysqli_query($dbp, "UPDATE koweb_member SET state='N' WHERE id='$_SESSION[member_id]' LIMIT 1");
	@mysqli_query($dbp, "DELETE FROM koweb_member WHERE id='$_SESSION[member_id]' LIMIT 1");

	setHistory("회원탈퇴", "", "$_SESSION[member_id] 회원 탈퇴");

	$alert_txt = "탈퇴";
	@session_destroy();


} else if($mode == "naver" || $mode == "kakao") {
	include_once $_SERVER['DOCUMENT_ROOT']."/member/{$mode}_callback.php";

	$alert_txt = "로그인";

	//한번더 가져오기
	$result_ = mysqli_query($dbp, $check_);
	$row = mysqli_fetch_array($result_);
	$_SESSION['auth_admin_ci'] = $row[id];
	$_SESSION['auth_admin_di'] = $row[password];
	$_SESSION['auth_token'] = hash("sha256", $id.$password);
	$_SESSION['auth_level'] = $row[auth_level];
	$_SESSION['member_id'] = $row[id];
	$_SESSION['member_name'] = $row[name];
	$_SESSION['CI'] = $row[CI];
	$_SESSION['DI'] = $row[DI];
	$_SESSION['login_type'] = $mode;

	if($return_url){
		$return_url = rawurldecode($return_url);
	} else {
		$return_url = "/";
	}

	?>
		<script type="text/javascript">
			alert("<?=$alert_txt?> 되었습니다.");
			location.href = "<?=$return_url?>";
		</script>
	<?

} else {
	error("올바른 접속경로를 이용해주시기 바랍니다.");
	exit;
}

/*----------------------------------------------------------------------------*/
// 마무리
/*----------------------------------------------------------------------------*/
?>
<script type="text/javascript">
alert("<?=$alert_txt?> 되었습니다.");
location.href = "<?=$PHP_SELF?>?mode=login";
</script>
