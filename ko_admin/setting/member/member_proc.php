<?
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";

if(!$_SESSION[member_id]){
	error("비정상");
	exit;
}

foreach (array_keys($_POST) as $value) {
	${$value} = sanitizeString($_POST[$value]);
	// ${$value} = sanitizeMySQL($_POST[$value]);
	${$value} = sqlin(${$value});
	
}

//  ID 정리
$reg_date = date("Y-m-d H:i:s");
$phone = $phone1."-".$phone2."-".$phone3;
if($email2) $email3 = $email2;
$email = $email1."@".$email3;
//$birthday = $birthday1."-".$birthday2."-".$birthday3;


if($mode == "write_proc"){
	if(isblank($password)) error("비밀번호를 입력해주세요");
	if(isblank($id)) error("아이디를 입력해주세요");

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

	$id_enc = hash("sha256", $id);
	$auth_token = hash("sha256", $id.$password);
	$password = hash("sha256", $password);

	//  ID 중복 체크
	$check = mysqli_fetch_array(mysqli_query($dbp, "SELECT COUNT(*) FROM koweb_member WHERE id='$id'"));
	if ($check[0])	error("중복된  ID 입니다.");

	// 정보 입력
	@mysqli_query($dbp, "INSERT INTO koweb_member VALUES('', '', '$name', '$dept', '', '$dept', '$id', '$password', '$id_enc', '$auth_token', '$level', '$phone', '$tel', '$email', '$birthday', '$gender', '$zip', '$address1', '$address2', '$is_admin', '$company', '$company_tel', '$company_zip', '$company_address1', '$company_address2', '$reg_date', 'Y', '$ip'  )");

	$alert_txt = "등록";

	setHistory("관리자 회원관리", $id, "$id 회원정보 신규등록");


} else if ($mode == "modify_proc"){

//	if(isblank($password)) error("비밀번호를 입력해주세요");
	if(isblank($id)) error("아이디를 입력해주세요");

	$query = "SELECT * FROM koweb_member WHERE no='$no'";
	$result = mysqli_query($dbp, $query);
	$row = mysqli_fetch_array($result);
	if(!$row[0]) {
		error("비정상적인 접근입니다.");
		exit;
	}

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
	
		$id_enc = hash("sha256", $id);
		$password = hash("sha256", $password);
		$auth_token = hash("sha256", $id.$password);
		$add_query = "id_enc = '$id_enc', password='$password', auth_token='$auth_token',";
	}

	@mysqli_query($dbp, "UPDATE koweb_member  SET name='$name', dept='$dept', $add_query auth_level='$level', phone='$phone', tel='$tel', email='$email', birthday='$birthday', gender= '$gender' , zip='$zip', address1='$address1', address2='$address2', is_admin='$is_admin', company='$company', company_tel='$company_tel', company_zip='$company_zip', company_address1 = '$company_address1', company_address2 = '$company_address2', state='$state', reg_date='$reg_date' WHERE no='$no'");

	$alert_txt = "수정";

	setHistory("관리자 회원관리", $row[id], "$row[id] 회원정보 수정");

} else if ($mode == "apply_proc"){

	foreach($check_apply as $value){

		@mysqli_query($dbp, "UPDATE koweb_member  SET auth_level = '$site[member_level]' WHERE no='$value'");
	} 

	$alert_txt = "승인";

} else if ($mode == "delete") {
	if($_SESSION[auth_level] != "1"){
		error("비정상적인 접근입니다. 관리자만 회원삭제가 가능합니다.");
		exit;
	} 

	
	$query = "SELECT * FROM koweb_member WHERE no='$no'";
	$result = mysqli_query($dbp, $query);
	$row = mysqli_fetch_array($result);
	if(!$row[0]) {
		error("비정상적인 접근입니다.");
		exit;
	}

	setHistory("관리자 회원관리", $row[id], "$row[id] 회원정보 삭제");

	//@mysqli_query($dbp, "UPDATE koweb_member SET state = 'N' WHERE no='$no'");
	@mysqli_query($dbp, "DELETE FROM koweb_member WHERE no = '$no' LIMIT 1");
	$alert_txt = "삭제";
}

/*----------------------------------------------------------------------------*/
// 마무리
/*----------------------------------------------------------------------------*/
if($return_no != ""){
?>
	<script type="text/javascript">
	alert("<?=$alert_txt?> 되었습니다.");
	location.href = "?type=setting&core_id=setting&core=manager_setting&manager_type=dept&mode=view&no=<?=$return_no?>";
	</script>
<? } else { ?>
	<script type="text/javascript">
	alert("<?=$alert_txt?> 되었습니다.");
	location.href = "<?=$PHP_SELF?>?type=<?=$type?>&core=<?=$core?>&manager_type=<?=$manager_type?>";
	</script>
<? } ?>