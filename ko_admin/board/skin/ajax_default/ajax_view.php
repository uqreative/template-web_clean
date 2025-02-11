<? include $_SERVER['DOCUMENT_ROOT'] . "/head.php"; ?>
<?
	$board = mysqli_fetch_array(mysqli_query($dbp, "SELECT * FROM koweb_board_config WHERE id = '$board_id' LIMIT 1"));
	include $_SERVER['DOCUMENT_ROOT'] . "/board/board_auth.php"; 

	$query = "SELECT * FROM $board_id WHERE no='$no'";
	$result = mysqli_query($dbp, $query);
	$row = mysqli_fetch_array($result);
	if($row[tag_type] != "TAG"){
		$row[comments] = nl2br($row[comments]);
	}			
	
	//관리자가 아님
	if(!$_SESSION[is_admin]){
		//비밀번호가 없음 
		if($row[secret] == "Y"){
			if($board[is_membership] != "Y"){
				if($password == "" ){
					echo "<script>do_auth('auth', '$board_id', '$keyword', '$search_key', '$start', '$cate', '$no', '', 'view');</script>";
					exit;
				} else {
					$password = hash("sha256", $password);

					if($row[password] != $password){
						alert("해당게시물은 작성자 및 관리자만 확인 가능합니다.");
						echo "<script>do_Process('list', '$board_id', '$keyword', '$search_key', '$start', '$cate', '', '');</script>";
						exit;
					}
				}
			} else {
				if($_SESSION[member_id] != $row[id]){
					alert("해당게시물은 작성자 및 관리자만 확인 가능합니다.");
					echo "<script>do_Process('list', '$board_id', '$keyword', '$search_key', '$start', '$cate', '', '');</script>";
					exit;
				}
			}
		}
	}
	mysqli_query($dbp, "UPDATE $board_id SET view_count = view_count +1 WHERE no='$no'");
	
	if($row[category]) $sub_title = "[$row[category]]";
	else unset($sub_title);
?>
<h3 class="bbsTitle"><?=$sub_title?> <?=$row[title]?></h3>
<table class="bbsView">
	<caption>게시판 상세보기</caption>
	<colgroup>
		<col data-view="th" style="width:15%"/>
		<col data-view="td" style="width:35%"/>
		<col data-view="th" style="width:15%"/>
		<col data-view="td" style="width:35%"/>
	</colgroup>
	<tbody>
		<tr>
			<th scope="row" data-view="date">작성일</th>
			<td><?=$row[reg_date]?></td>
			<th scope="row" data-view="count">조회수</th>
			<td><?=$row[view_count]+1?></td>
		</tr>
		<tr>
			<td colspan="4" class="conts">
				<div class="conts">
					<!-- 이미지 view단표시 -->
					<div class="img">
						<? 
							$file_count = 0;
							for ($i = 1; $i <= $board[file_count]; $i++){ 
								$file_title =  $row["file_".$i];
								if($file_title){ 
									$ext = end(explode(".", strtolower($file_title)));
									if($ext == "jpg" || $ext == "png" || $ext == "jpeg" || $ext == "bmp"){ 
										echo "<img src=\"/upload/$board_id/$file_title\" alt=\"\"/>";
									}
									$file_count++;
								} 
							} 
						?>
					</div>
					<!-- //이미지 view단표시 -->
					<?=$row[comments]?>
					<p style="opacity:0; visibility:visible; color:white;"><?=nl2br($row[etc])?></p>
				</div>
			</td>
		</tr>
		<!-- 첨부파일이 있는경우 -->
		<? if($file_count > 0){ ?>
			<tr>
				<th scope="row" data-view="file">첨부파일</th>
				<td colspan="3">
					<!-- 파일목록 -->
					<ul class="list_file">
						<? 
							for ($i = 1; $i <= $board[file_count]; $i++){ 
									$file_title =  $row["file_".$i];
									if($file_title){ 
										echo "<li><a href=\"/board/download.php?board_id=$board_id&amp;file=$file_title\">$file_title</a></li>";
								  } 
							} 
						?>
					</ul>
				</td>
			</tr>
		<? } ?>
		<!-- //첨부파일 -->
	</tbody>
</table>
<? 
	//코멘트 사용하는 게시판인지 확인
	if($board[use_comment] == "Y") {
		include "comment.html";
	}
?>

<!-- 이전/다음 -->
<?
	// 다음글, 이전글
	$temp_next = mysqli_fetch_array(mysqli_query($dbp, "SELECT no, title FROM $board_id WHERE  no>'$no' ORDER BY  no ASC LIMIT 1"));
	if ($temp_next) $next = "?no=$temp_next[no]&amp;mode=view&amp;board_id=$board_id&amp;start=$start&amp;search_key=$search_key&amp;keyword=$keyword&amp;category=$category"; 
	else $next = "#";
	// 이전글
	$temp_prev = mysqli_fetch_array(mysqli_query($dbp, "SELECT no, title FROM $board_id WHERE no<'$no' ORDER BY no DESC LIMIT 1"));
	if ($temp_prev) $prev = "?no=$temp_prev[no]&amp;mode=view&amp;board_id=$board_id&amp;start=$start&amp;search_key=$search_key&amp;keyword=$keyword&amp;category=$category";



	else $prev = "#";
?>
<table class="bbsView page">
	<caption>이전 다음 글보기</caption>
	<colgroup>
		<col data-view="th" style="width:15%"/>
		<col data-view="td" style="width:85%"/>
	</colgroup>
	<tbody>
		<tr>
			<th scope="row">이전글</th>
			<td>
				<a href="#" <? if($temp_prev) { ?> onclick="javascript:do_Process('view', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$temp_prev[no]?>', '')" <? } ?>><?=$temp_prev[title]?></a>
			</td>
		</tr>
		<tr>
			<th scope="row">다음글</th>
			<td><a href="#" <? if($temp_next) { ?> onclick="javascript:do_Process('view', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$temp_next[no]?>', '')" <? } ?>><?=$temp_next[title]?></a></td>
		</tr>
	</tbody>
</table>
<!-- //이전/다음 -->

<!-- 버튼 -->
<div class="btn_area">
	<? if($auth_read){ ?>
		<a href="javascript:do_Process('modify', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$row[no]?>')" class="button">수정</a>
	<? } ?>
	<? if($_SESSION[is_admin]){ ?>
		<a href="#" class="button" onclick="return confirm('삭제한 데이터는 다시 복구 할 수 없습니다. 삭제하시겠습니까?'); javascript:do_Process('delete', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$row[no]?>', '')" >삭제</a>
	<? } else if($auth_delete){ ?>
		<? if($board[is_membership] != "Y"){ ?>
			<a href="javascript:do_auth('auth', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$row[no]?>', '', 'delete')" class="button">삭제</a>
		<? } else { ?>
			<a href="javascript:do_Process('auth', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$row[no]?>', '', 'delete')" class="button">삭제</a>
		<? } ?>
	<? } ?>
	<? if($auth_reply && $row[notice] != "Y"){ ?>
		<a href="javascript:do_Process('reply', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$row[no]?>', '', '<?=$row[no]?>')" class="button">답글</a>
	<? } ?>
	<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$row[no]?>', '')" class="button">목록</a>


</div>
<!-- //버튼 -->
