<? 
// 프로그램 ID 정리
$reg_date = date("Y-m-d H:i:s");

foreach($dept_auth as $value){
	$dept_query .= $value."|";
}
$dept_query = substr($dept_query, 0, -1);

if($mode == "write_proc"){

	// 프로그램 ID 중복 체크
	$check = mysqli_fetch_array(mysqli_query($dbp, "SELECT COUNT(*) FROM koweb_program_config WHERE id='$id'"));
	if ($check[0])	error("중복된 프로그램 ID 입니다.");

	// 관리자 폴더 생성
	mkdir($_SERVER[DOCUMENT_ROOT] . "/ko_admin/program/" . $id);
	chmod($_SERVER[DOCUMENT_ROOT] . "/ko_admin/program/" . $id, 0711);

	mkdir($_SERVER[DOCUMENT_ROOT] . "/upload/program/" . $id);
	chmod($_SERVER[DOCUMENT_ROOT] . "/upload/program/" . $id, 0711);


	$sort_ = mysqli_fetch_array(mysqli_query($dbp, "SELECT * FROM koweb_program_config ORDER BY sort DESC LIMIT 1"));
	$sort = $sort_[sort]+1;


	// 프로그램 정보 입력
	@mysqli_query($dbp, "INSERT INTO koweb_program_config VALUES('', '$id', '$title', '$table', '$user_view', '$user_list', '$admin_url', '$is_member', '$is_admin', '$dept_query', '$reg_date', '$state', '$sort')");
	
	$alert_txt = "등록";

} else if ($mode == "modify_proc"){

	@mysqli_query($dbp, "UPDATE koweb_program_config  SET title='$title', user_view='$user_view', user_list='$user_list', admin_url= '$admin_url', refer_table='$table', state='$state', dept_auth='$dept_query'  WHERE no='$no'");
	$alert_txt = "수정";
} else {
	@mysqli_query($dbp, "DELETE FROM koweb_program_config WHERE no = '$no' LIMIT 1");
	$alert_txt = "삭제";
}

/*----------------------------------------------------------------------------*/
// 마무리
/*----------------------------------------------------------------------------*/
?>
<script type="text/javascript">
alert("<?=$alert_txt?> 되었습니다.");
location.href = "<?=$PHP_SELF?>?type=<?=$type?>&core=<?=$core?>&manager_type=<?=$manager_type?>";
</script>