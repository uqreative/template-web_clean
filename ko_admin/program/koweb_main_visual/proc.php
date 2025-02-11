<? 
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";

$view_count = 0;
$ip = $REMOTE_ADDR;
if (!$reg_date) $reg_date = date("Y-m-d H:i:s");
$return_url = explode("?", $return_url);
$dir = $_SERVER['DOCUMENT_ROOT'] . "/upload/program/".$program_id."/";

/*
// 관리자 페이지 체크
if ($PHP_SELF ==  "/koweb_manager/site/manager_site.php" || $return_url[0] == "/koweb_manager/site/manager_site.php") {
	$is_admin = true;
} else {
	$is_admin = false;
}
*/

if($mode == "modify_proc"){

	//============================== 수정 ============================== //

	if (isblank($program_id)) error("비정상적 접근");
	/* if (isblank($name)) error("이름을 입력해 주세요.");
	if (isblank($password)) error("비밀번호를 입력해 주세요.");
	if (isblank($title)) error("제목을 입력해 주세요.");
	*/

	//파일 업로드
	$modify_row = mysqli_fetch_array(mysqli_query($dbp, "SELECT * FROM $program_id WHERE no = '$no' LIMIT 1"));
	if($_FILES[main_img][size] > 0){ 
			if($_FILES[main_img][size] > 2 * 1024 * 1024){ 
				error("2MB 이상 파일을 첨부 할 수 없습니다.");
				exit;
			}
		$main_img =  upload_file($dir, $_FILES[main_img][tmp_name], $_FILES[main_img][name]); 
		$add_query = "main_img='$main_img', ";

		@unlink($dir . $modify_row['main_img']);
		@unlink($dir . "thumb_".$modify_row['main_img']);
	}

	$update_query = "UPDATE $program_table SET $add_query 
											reg_date='$reg_date'
										WHERE no='$no' LIMIT 1";


	$result = mysqli_query($dbp, $update_query);

	alert("수정되었습니다.");
	url($return_url[0]."?program_id=$program_id&amp;mode=list&amp;no=$no&amp;start=$start");

	//============================== 수정 끝 ============================== //

} else if($mode == "delete"){
	
	//============================== 삭제 ============================== //


	$tmp_sort = mysqli_fetch_array(mysqli_query($dbp, "SELECT * FROM $program_table WHERE no = '$no'"));
	$update_query = "UPDATE $program_table SET sort = sort -1 WHERE sort > '$tmp_sort[sort]'";
	mysqli_query($dbp, $update_query);
	
	@unlink($dir.$tmp_sort[main_img]);
	@unlink($dir."thumb_".$tmp_sort[main_img]);

	$delete_query = "DELETE FROM $program_table WHERE no='$no'";
	$result = mysqli_query($dbp, $delete_query);

	alert("삭제되었습니다.");
	url($return_url[0]."?program_id=$program_id");

	//============================== 삭제 끝 ============================== //

} else if($mode == "sort"){
	
	$default = mysqli_fetch_array(mysqli_query($dbp, "SELECT * FROM $program_table WHERE no='$no'"));



	if($sort_mode == "up"){
		$sort_WHERE = "AND sort < '$default[sort]' ORDER BY sort DESC";
	} else {
		$sort_WHERE = "AND sort > '$default[sort]' ORDER BY sort ASC";
	}

	$query = "SELECT * FROM $program_table WHERE 1=1 $sort_WHERE LIMIT 1";
	$result = mysqli_query($dbp, $query);
	$result2 = mysqli_query($dbp, $query);
	$check_row = mysqli_fetch_array($result);	

	if($check_row[0]){
		$prev_data = mysqli_fetch_array($result2);
		$tmp_sort = "";
		$tmp_sort = $default[sort];
		$default[sort] = $prev_data[sort];
		$prev_data[sort] = $tmp_sort;

		if(!$prev_data[sort]) $prev_data[sort] = 1;
		$prev_update = mysqli_query($dbp, "UPDATE $program_table SET sort='$prev_data[sort]' WHERE no='$prev_data[no]'");
		$default = mysqli_query($dbp, "UPDATE $program_table SET sort='$default[sort]' WHERE no='$default[no]'");
	}

	alert("처리되었습니다.");
	url($return_url[0]."?program_id=$program_id");

	//============================== 삭제 끝 ============================== //

}  else {

	//============================== 등록 ============================== //
	if (isblank($program_id)) error("비정상적 접근");
	//if (isblank($title)) error("팝업 제목을 입력해 주세요.");
	//if (isblank($category)) error("구분을 하나이상 작성해주세요.");
	//if (isblank($link_url)) error("팝업 URL을 입력해 주세요.");
	//if (isblank($_FILES[title_img][size] <= 0)) error("대표이미지를 등록해 주세요.");

	if($_FILES[main_img][size] > 0){
		if($_FILES[main_img][size] > 2 * 1024 * 1024){ 
			error("2MB 이상 파일을 첨부 할 수 없습니다.");
			exit;
		}
		$main_img =  upload_file($dir, $_FILES[main_img][tmp_name], $_FILES[main_img][name]);
	}

	$tmp_sort = mysqli_fetch_array(mysqli_query($dbp, "SELECT * FROM $program_table ORDER BY sort DESC LIMIT 1"));
	$sort = $tmp_sort[sort]+1;

	mysqli_query($dbp, "INSERT INTO $program_table VALUES ('', '$main_img', '$sort', '$reg_date')");
	alert("등록되었습니다.");
	url($return_url[0]."?program_id=$program_id");

	//============================== 등록 끝 ============================== //
}

