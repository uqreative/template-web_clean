<?
//php ���� error
if($mode != "delete" && $mode != "comment_proc"){
	include_once  $_SERVER['DOCUMENT_ROOT'] . "/head.php";
}

// �ʵ� üũ
foreach (array_keys($_POST) as $value) {
	${$value} = sqlin($_POST[$value]);
	// ${$value} = sanitizeMySQL($_POST[$value]);
}
$comm_comments = $_POST[comm_comments];
$comments = $_POST[comments];

$board = mysqli_fetch_array(mysqli_query($dbp, "SELECT * FROM koweb_board_config WHERE id='$board_id' LIMIT 1"));
//$password = hash("sha256", $password);
$view_count = 0;
$ip = $REMOTE_ADDR;
if (!$reg_date) $reg_date = date("Y-m-d H:i:s");

$return_url = explode("?", $return_url);
$dir = $_SERVER['DOCUMENT_ROOT'] . "/upload/".$board_id."/";

// ������ ������ üũ
/*
if ($PHP_SELF ==  "/ko_admin/board/manager_board.php" || $return_url[0] == "/ko_admin/board/manager_board.php") {
	$is_admin = true;
} else {
	$is_admin = false;
}
*/
if($mode == "modify"){

	//============================== ���� ============================== //

	if (isblank($board_id)) error("�������� ����");
	if (isblank($name)) error("�̸��� �Է��� �ּ���.");
	// �����ȣ 37 = ������, �����ȣ 2 = koweb
	if($_SESSION[member_no] != "37" && $_SESSION[member_no] != "2") {
		if ($board[is_membership] != "Y"){ if(isblank($password)) error("��й�ȣ�� �Է��� �ּ���."); }
	}
	if (isblank($title)) error("������ �Է��� �ּ���.");
	if (isblank($password)) error("�������� ����");
	if ($board[spam_auth] == "Y"){
		if (isblank($_SESSION[rand_auth])) error("�������� ����");

		if($_SESSION[rand_auth] != $rand_auth_ ){
			error("���Թ�����ȣ�� �ùٸ��� �ʽ��ϴ�.");
			exit;
		}
	}

	if($board[is_membership] != "Y"){
		if($_SESSION[member_no] != "37" && $_SESSION[member_no] != "2") {
			$password = hash("sha256", $password);
		} else {
			$password = hash("sha256", $_SESSION[member_id]);
		}
	} else {
		$password = hash("sha256", $_SESSION[member_id]);
	}

	if(!$_SESSION[is_admin]){
		if($board[is_membership] == "Y"){
			$check_query = "SELECT * FROM $board_id WHERE no='$no' LIMIT 1";
			$check_result = mysqli_query($dbp, $check_query);
			$check_row = mysqli_fetch_array($check_result);

			if($_SESSION[member_id] != $check_row[id] ){
				error("�ش�Խù��� ������ �� �ۼ��ڸ� ���� �����մϴ�.");
				exit;
			}

		}
	}

	//���Ͼ��ε�
	$modify_row = mysqli_fetch_array(mysqli_query($dbp, "SELECT * FROM board_land WHERE no = '$no' LIMIT 1"));
	for($i = 1; $i <= $board[file_count]; $i++){
		if(${'del_'.$i} == "Y"){
			$add_query .= "file_".$i." =  '',";
			@unlink($dir . $modify_row['file_'.$i]);
			@unlink($dir . "thumb_".$modify_row['file_'.$i]);
		}
	}
	for($i = 1; $i <= $board[file_count]; $i++){
		if($_FILES["file_".$i][size] > 0){
			if ($_FILES["file_".$i][size] > $board[file_limit_size] * 1024 * 1024) {
				error("÷������ ".$i."�� �뷮 ������ $board[file_limit_size]M �Դϴ�.");
				exit;
			} else {
				${'file_'.$i} = upload_file($dir, $_FILES["file_".$i][tmp_name], $_FILES["file_".$i][name]);
				$add_query .= "file_".$i." =  '". ${'file_'.$i} . "',";
			}
		}
	}


	$update_query = "UPDATE $board_id SET id='$_SESSION[member_id]',
											CI='$_SESSION[CI]',
											DI='$_SESSION[DI]',
											name='$name',
											password='$password',
											phone='$phone',
											email='$email',
											zip='$zip',
											address1='$address1',
											address2='$address2',
											category='$category',
											title='$title',
											comments_type='$comments_type',
											tag_type='$tag_type',
											comments='$comments',
											etc='$etc',
											notice='$notice',
											secret='$secret',
											file_type='$file_type',
											$add_query
											ip='$ip',
											reply_state='$reply_state',
											reply_id='$reply_id',
											reply_name='$reply_name',
											reply_phone='$reply_phone',
											reply_email='$reply_email',
											reply_comments='$reply_comments',
											reply_ip='$reply_ip',
											reply_date='$reply_date',
											reply_file_1='$reply_file_1',
											reply_file_2='$reply_file_2',
											reply_file_3='$reply_file_3',
											reply_file_4='$reply_file_4',
											reply_file_5='$reply_file_5',
											hidden='$hidden',
											link='$link',
											select_date='$select_date'
										WHERE no='$no' LIMIT 1";

	$result = mysqli_query($dbp, $update_query);
	alert("�����Ǿ����ϴ�.");
	url($return_url[0]."?board_id=$board_id&amp;mode=list&amp;no=$no&amp;start=$start&year=$year&month=$month");

	//============================== ���� �� ============================== //

} else if($mode == "delete"){

	//============================== ���� ============================== //

	if(!$_SESSION[is_admin]){
		if($board[is_membership] != "Y"){
			//��й�ȣ�� ����
			if($password == "" ){
				url("$PHP_SELF?board_id=$board_id&amp;start=$start&amp;mode=check&amp;return_mode=$mode&amp;no=$no&year=$year&month=$month");
				exit;
			}
			$password = hash("sha256", $password);
			$check_query = "SELECT * FROM $board_id WHERE no='$no' AND password='$password' LIMIT 1";
			$check_result = mysqli_query($dbp, $check_query);
			$check_row = mysqli_num_rows($check_result);

			if($check_row < 1 ){
				error("�ش�Խù��� ������ �� �ۼ��ڸ� ���� �����մϴ�.");
				exit;
			}
			$delete_query = "DELETE FROM $board_id WHERE no='$no' AND password='$password'";
		} else {
			$check_query = "SELECT * FROM $board_id WHERE no='$no' LIMIT 1";
			$check_result = mysqli_query($dbp, $check_query);
			$check_row = mysqli_fetch_array($check_result);

			if($_SESSION[member_id] != $check_row[id] ){
				error("�ش�Խù��� ������ �� �ۼ��ڸ� ���� �����մϴ�.");
				exit;
			} else {
				$delete_query = "DELETE FROM $board_id WHERE no='$no' AND id='$_SESSION[member_id]'";
			}
		}
	} else {
		$delete_query = "DELETE FROM $board_id WHERE no='$no'";
	}
	boardAttachRemove($dir, $board_id, $no);
	$result = mysqli_query($dbp, $delete_query);

	alert("�����Ǿ����ϴ�.");
	url($return_url[0]."?board_id=$board_id&select_date=$select_date&year=$year&month=$month");

	//============================== ���� �� ============================== //


} else {

	//============================== ��� ============================== //

	if (isblank($board_id)) error("�������� ����");
	if (isblank($name)) error("�̸��� �Է��� �ּ���.");
	// �����ȣ 37 = ������, �����ȣ 2 = koweb
	if($_SESSION[member_no] != "37" && $_SESSION[member_no] != "2") {
		if ($board[is_membership] != "Y"){ if(isblank($password)) error("��й�ȣ�� �Է��� �ּ���."); }
	}
	if (isblank($title)) error("������ �Է��� �ּ���.");
	if ($board[spam_auth] == "Y"){
		if (isblank($_SESSION[rand_auth])) error("�������� ����");

		if($_SESSION[rand_auth] != $rand_auth_ ){
			error("���Թ�����ȣ�� �ùٸ��� �ʽ��ϴ�.");
			exit;
		}
	}

	//���� ���ε�
	for($i = 1; $i <= $board[file_count]; $i++){
		if($_FILES["file_".$i][size] > 0){
			if ($_FILES["file_".$i][size] > $board[file_limit_size] * 1024 * 1024) {
				alert("÷������ ".$i." �� �뷮 ������ $board[file_limit_size]M �Դϴ�.");
			} else {
				${'file_'.$i} = upload_file($dir, $_FILES["file_".$i][tmp_name], $_FILES["file_".$i][name]);
			}
		}
	}

	if($board[is_membership] != "Y"){
		if($_SESSION[member_no] != "37" && $_SESSION[member_no] != "2") {
			$password = hash("sha256", $password);
		} else {
			$password = hash("sha256", $_SESSION[member_id]);
		}
	} else {
		$password = hash("sha256", $_SESSION[member_id]);
	}

	$insert_query = "INSERT $board_id SET depth='$depth',
											ref_no='$ref_no',
											ref_group='$ref_group',
											id='$_SESSION[member_id]',
											CI='$_SESSION[CI]',
											DI='$_SESSION[DI]',
											name='$name',
											password='$password',
											phone='$phone',
											email='$email',
											zip='$zip',
											address1='$address1',
											address2='$address2',
											category='$category',
											title='$title',
											comments_type='$comments_type',
											tag_type='$tag_type',
											comments='$comments',
											etc='$etc',
											notice='$notice',
											secret='$secret',
											file_type='$file_type',
											file_1='$file_1',
											file_2='$file_2',
											file_3='$file_3',
											file_4='$file_4',
											file_5='$file_5',
											file_6='$file_6',
											file_7='$file_7',
											file_8='$file_8',
											file_9='$file_9',
											file_10='$file_10',
											reg_date = '$reg_date',
											ip='$ip',
											reply_state='$reply_state',
											reply_id='$reply_id',
											reply_name='$reply_name',
											reply_phone='$reply_phone',
											reply_email='$reply_email',
											reply_comments='$reply_comments',
											reply_ip='$reply_ip',
											reply_date='$reply_date',
											reply_file_1='$reply_file_1',
											reply_file_2='$reply_file_2',
											reply_file_3='$reply_file_3',
											reply_file_4='$reply_file_4',
											reply_file_5='$reply_file_5',
											view_count='$view_count',
											hidden='$hidden',
											link='$link',
											select_date='$select_date'";
	mysqli_query($dbp, $insert_query);
	$ref_no = mysqli_insert_id($dbp);

	mysqli_query($dbp, "UPDATE $board_id SET ref_no = '$ref_no', ref_group = '$ref_no' WHERE no='$ref_no' LIMIT 1");


	alert("��ϵǾ����ϴ�.");
	url($return_url[0]."?board_id=$board_id&select_date=$select_date&year=$year&month=$month");

	//============================== ��� �� ============================== //
}
