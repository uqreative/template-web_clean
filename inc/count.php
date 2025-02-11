<?
// include  "../head.php";

#########################################################################
# 접속관련 쿠키정보가 없다면 쿠키 생성 및 통계 데이터 추가
#########################################################################

# 테이블정보
$cookie_domain = "check";
$table_counter = "koweb_statistics";
//$table_referer = "cms_referer";
//$table_referer_path = "cms_referer_path";

if (!isset($_COOKIE['check'])) {
	### 쿠키 생성
	setcookie("check", "check_ok", 0, "/");

	### 기본 설정
	$c_date = date("Y-m-d");
	$c_time = "h_".date("H");

	### 접속통계 입력
	# 오늘 통계데이터가 존재하지 않는다면 추가
	$result = mysqli_query($dbp, "SELECT COUNT(*) as count FROM $table_counter WHERE c_date='$c_date'");
	if ($result) {
		$row = mysqli_fetch_array($result);
		$date_exists = $row['count'];
	} else {
		$date_exists = 0;
	}
	if (!$date_exists) mysqli_query($dbp, "INSERT INTO $table_counter SET c_date='$c_date'");

	# 접속 브라우져 확인
	$HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
	if (preg_match("/MSIE/", $HTTP_USER_AGENT)) {
		if (preg_match("/11.0/", $HTTP_USER_AGENT)) $browser = "bs_ie110";
		else if (preg_match("/10.0/", $HTTP_USER_AGENT)) $browser = "bs_ie100";
		else if (preg_match("/9.0/", $HTTP_USER_AGENT)) $browser = "bs_ie90";
		else if (preg_match("/8.0/", $HTTP_USER_AGENT)) $browser = "bs_ie80";
		else if (preg_match("/7.0/", $HTTP_USER_AGENT)) $browser = "bs_ie70";
		else if (preg_match("/6.0/", $HTTP_USER_AGENT)) $browser = "bs_ie60";
		else if (preg_match("/5.5/", $HTTP_USER_AGENT)) $browser = "bs_ie55";
		else if (preg_match("/5.0/", $HTTP_USER_AGENT)) $browser = "bs_ie50";
		else $browser = "bs_etc";
	}
	else if (preg_match("/SamsungBrowser/", $HTTP_USER_AGENT)) $browser = "bs_samsung";
	else if (preg_match("/Chrome/", $HTTP_USER_AGENT)) $browser = "bs_chrome";
	else if (preg_match("/Safari/", $HTTP_USER_AGENT)) $browser = "bs_safari";
	else if (preg_match("/Firefox/", $HTTP_USER_AGENT)) $browser = "bs_firefox";
	else if (preg_match("/Opera/", $HTTP_USER_AGENT)) $browser = "bs_opera";
	else if (preg_match("/Android/", $HTTP_USER_AGENT)) $browser = "bs_android";
	else if (preg_match("/iPhone/", $HTTP_USER_AGENT)) $browser = "bs_iphone";
	else $browser = "bs_etc";

	# 접속 운영체제 확인
	if (preg_match("/95/", $HTTP_USER_AGENT)) $os = "os_win95";
	else if (preg_match("/98/", $HTTP_USER_AGENT)) $os = "os_win98";
	else if (preg_match("/ME/", $HTTP_USER_AGENT)) $os = "os_winme";
	else if (preg_match("/NT 4./", $HTTP_USER_AGENT)) $os = "os_winnt";
	else if (preg_match("/NT 5.0/", $HTTP_USER_AGENT)) $os = "os_win2000";
	else if (preg_match("/NT 5.1/", $HTTP_USER_AGENT)) $os = "os_winxp";
	else if (preg_match("/NT 5.2/", $HTTP_USER_AGENT)) $os = "os_win2003";
	else if (preg_match("/NT 6.0/", $HTTP_USER_AGENT)) $os = "os_vista";
	else if (preg_match("/NT 6.1/", $HTTP_USER_AGENT)) $os = "os_win7";
	else if (preg_match("/NT 6.2/", $HTTP_USER_AGENT)) $os = "os_win8";
	else if (preg_match("/NT 10/", $HTTP_USER_AGENT)) $os = "os_win10";
	else if (preg_match("/Mac/", $HTTP_USER_AGENT)) $os = "os_mac";
	else if (preg_match("/Linux/", $HTTP_USER_AGENT)) $os = "os_linux";
	else if (preg_match("/sunOS/", $HTTP_USER_AGENT)) $os = "os_sun";
	else $os = "os_etc";

	$table_referer = "koweb_statistics_refer";
	$table_referer_path = "cms_referer_path";

	### 접속경로 입력
	# 10,000개 이상일 경우 1,000개 단위로 삭제
	$referer_num_result = mysqli_query($dbp, "SELECT COUNT(*) FROM $table_referer");
	$referer_num_row = mysqli_fetch_array($referer_num_result);
	$referer_num = $referer_num_row[0];

	if ($referer_num > 10000) {
		$refer_sql = mysqli_query($dbp, "SELECT r_idx FROM $table_referer ORDER BY r_idx asc LIMIT 1000");
		while ($refer_row = mysqli_fetch_array($refer_sql)) {
			mysqli_query($dbp, "DELETE FROM $table_referer WHERE r_idx=$refer_row[r_idx]");
		}
	}

	# 경로값 추가
	$r_date = date("Y-m-d H:i:s");
	$r_year = date("Y");
	$r_month = date("m");
	$r_day = date("d");
	$r_hour = date("H");
	$r_min = date("i");
	$r_sec = date("s");
	$r_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	$r_ip = $_SERVER['REMOTE_ADDR'];
	$r_url_domain = getDomainName($r_url);
	
	// IP 중복 방지
	$search_query = "SELECT COUNT(*) AS total_visitor FROM $table_referer WHERE r_ip = '$r_ip' AND year = '$r_year' AND month = '$r_month' AND day = '$r_day'";
	$search_result = mysqli_query($dbp, $search_query);
	$total_visitor = mysqli_fetch_array($search_result);
	if ($total_visitor['total_visitor'] > 0) {
		// 해당 아이피가 이미 접속한 이력이 있음
	} else {
		// 접속한 이력이 없으면 INSERT
		# 통계값 추가
		mysqli_query($dbp,"UPDATE $table_counter SET $c_time=$c_time+1, day_total=day_total+1, $browser=$browser+1, $os=$os+1 WHERE c_date='$c_date'");

		if(!$r_url) $r_url = "직접 접속 및 즐겨찾기";
		if(!$r_url_domain) $r_url_domain = "직접 접속 및 즐겨찾기";
		mysqli_query($dbp,"INSERT INTO $table_referer SET r_date='$r_date', year = '$r_year', month='$r_month', day = '$r_day', hour='$r_hour', min='$r_min', sec='$r_sec', r_url='$r_url', r_url_domain='$r_url_domain', r_ip='$r_ip'");
	}

/*
	if(!$r_url){
			$r_url = "직접 접속 및 즐겨찾기";
	}
	if(!$r_url_domain){
			$r_url_domain = "직접 접속 및 즐겨찾기";
	}
	mysql_query("INSERT INTO $table_referer SET r_date='$r_date', year = '$r_year', month='$r_month', day = '$r_day', hour='$r_hour', min='$r_min', sec='$r_sec', r_url='$r_url', r_url_domain='$r_url_domain', r_ip='$r_ip'");
*/
}
?>
