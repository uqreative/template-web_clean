<?
		include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";
		include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";

		if($core_id){
			$online_id = $core_id;
		}
		//online 기본정보 불러오기
		$online_query = "SELECT * FROM koweb_online_config WHERE id = '$online_id'";
		$online_result = mysqli_query($dbp, $online_query);
		$online = mysqli_fetch_array($online_result);
		$online_table = $online[id];


		//online 기본 변수
		$http_host = $_SERVER['HTTP_HOST'];
		$request_uri = $_SERVER['REQUEST_URI'];
		$url = "http://" . $http_host . $request_uri;

		include_once $_SERVER['DOCUMENT_ROOT'] . "/online/online_auth.php";
		echo "<script type=\"text/javascript\" src=\"/js/online.js\"></script>";


		if(!$online_id || $online[title] == ""){
			error("정상적인 경로로 접근하여 주세요.");
			exit;
		}
?> 

	<div id="online_title_area" style="margin-top:5px; margin-bottom:25px;">
	<h2 class="mt0"><?=$online[title]?></h2>
	</div>
<?
	switch ($mode) {
		case "write" :
		case "modify" :
			//에디터 js 로 따로 
			//include_once $_SERVER['DOCUMENT_ROOT'] . "/js/online.js";
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $online[title]'; </script>";
			include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/$online_id/form.html";
			break;

		case "write_proc" :
		case "modify_proc" :
			//에디터 js 로 따로 
			//include_once $_SERVER['DOCUMENT_ROOT'] . "/js/online.js";
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $online[title]'; </script>";
			include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/$online_id/proc.php";
			break;

		case "view" :
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(상세보기) | $online[title]'; </script>";
			include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/$online_id/view.html";
			break;

		case "delete" : // 삭제
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(삭제) | $online[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/$online_id/proc.php";
			break;

		case "check" :
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(인증) | $online[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/$online_id/auth_check.html";
			break;

		case "comment_proc" :
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(코멘트작성) | $online[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/$online_id/proc.php";
			break;

		default:
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(목록) | $online[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/$online_id/list.html";
			break;
		}
	?>
