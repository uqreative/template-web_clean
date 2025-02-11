<?
include $_SERVER['DOCUMENT_ROOT'] . "/head.php";
$board = mysqli_fetch_array(mysqli_query($dbp, "SELECT * FROM koweb_board_config WHERE id = '$board_id' LIMIT 1"));
include $_SERVER['DOCUMENT_ROOT'] . "/board/board_auth.php"; 

$mode_title = "글쓰기";
$row[name] = $_SESSION['member_name'];

if($mode == "modify"){
	$query = "SELECT * FROM $board_id WHERE no='$no'";
	$result = mysqli_query($dbp, $query);
	$row = mysqli_fetch_array($result);
	$mode_title = "글수정";

	//관리자가 아님
	if(!$_SESSION[is_admin]){
		if($board[is_membership] != "Y"){
		//비밀번호가 없음
			if($password == "" ){
				echo "<script>do_auth('auth', '$board_id', '$keyword', '$search_key', '$start', '$cate', '$row[no]', '', 'modify');</script>";
				exit;
			}

			$password = hash("sha256", $password);

			//비밀번호가 틀림
			if($row[password] != $password){
				alert("해당게시물은 작성자 및 관리자만 확인 가능합니다.");
				echo "<script>do_Process('list', '$board_id', '$keyword', '$search_key', '$start', '$cate', '', '');</script>";
				exit;
			}
		} else {
			if($_SESSION[member_id] != $row[id]){
				alert("해당게시물은 작성자 및 관리자만 수정 가능합니다.");
				echo "<script>do_Process('list', '$board_id', '$keyword', '$search_key', '$start', '$cate', '', '');</script>";
				exit;
			}
		}
	}
}
if($mode == "reply"){
	$query = "SELECT name, comments, title, secret FROM $board_id WHERE no='$ref_no'";
	$result = mysqli_query($dbp, $query);
	$row = mysqli_fetch_array($result);
	$comm_first_text = "<p>&nbsp;</p><hr> ".$row[name]." 님이 작성하신 글입니다.<br>";
	$comm_last_text= "<p>&nbsp;</p><hr><p></p><br />";
	$row[comments] = $comm_first_text . $row[comments] . $comm_last_text;

	$title_first_text = "$row[title] 의 답변입니다.";
	$row[title] = $title_first_text;
}
?>
<form action="/ko_admin/board/skin/<?=$skin?>/proc.php" method="post" name="form" enctype="multipart/form-data" id="default_ajax_form">
<input type="hidden" name="board_id" value="<?=$board_id?>" />
<input type="hidden" name="mode" value="<?=$mode?>" />
<input type="hidden" name="return_url" value="<?=$url?>" />
<input type="hidden" name="no" value="<?=$row[no]?>" />
<input type="hidden" name="ref_no" value="<?=$ref_no?>" />
<input type="hidden" name="tag_type" value="<?=$tag_type?>" />
<table class="bbsView">
	<caption>게시판 <?=$mode_title?></caption>
	<colgroup>
		<col data-write="th" style="width:15%"/>
		<col data-write="td" style="width:85%"/>
	</colgroup>
	<tbody>
		<tr>
			<th scope="row"><span class="marking">필수항목</span><label for="title">제목</label></th>
			<td><input type="text" name="title" id="title" class="inputFull required" value="<?=$row[title]?>" title="제목"/></td>
		</tr>
		<tr>
			<th scope="row"><span class="marking">필수항목</span><label for="name">작성자</label></th>
			<td><input type="text" name="name" id="name" value="<?=$row[name]?>" class="required" <?=$mem_option?> title="작성자"/></td>
		</tr>
		<? if($board[use_category] == "Y") { ?>
			<tr>
				<th scope="row"><span class="marking">필수항목</span><label for="category">지역</label></th>
				<td>
					<? $category = explode("|", $board[category_detail]); ?>
					<select name="category" id="category">
						<? for($i = 0; $i < count($category); $i++){ ?>
							<option value="<?=$category[$i]?>"><?=$category[$i]?></option>
						<? } ?>
					</select>
				</td>
			</tr>
		<? } ?>
		<tr>
			<th scope="row">연락처</th>
			<td class="tel">
				<input type="text" name="phone1" id="phone1" class="input100" value="<?=$phone[0]?>" title="연락처 첫번째 자릿수번호" /> -
				<input type="text" name="phone2" id="phone2" class="input100" value="<?=$phone[1]?>" title="연락처 두번째 자릿수번호" /> - 
				<input type="text" name="phone3" id="phone3" class="input100" value="<?=$phone[2]?>" title="연락처 세번째 자릿수번호" />
			</td>
		</tr>
		<tr>		
			<th scope="row" rowspan="3"><label for="zip">주소</label></th>
			<td class="address">
				<input type="text" class="input200" name="zip" id="zip" value="<?=$row[zip]?>" />	
				<a href="javascript:execDaumPostcode()" class="button white">우편번호검색</a>

				<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
				<script type="text/javascript">
					function execDaumPostcode()
					{
						new daum.Postcode(
						{
							oncomplete: function(data)
							{
								// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
								// 각 주소의 노출 규칙에 따라 주소를 조합한다.
								// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
								var fullAddr  = "";					// 최종 주소 변수
								var extraAddr = "";					// 조합형 주소 변수

								// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
								if (data.userSelectedType === "R")
								{
									// 사용자가 도로명 주소를 선택했을 경우
									fullAddr = data.roadAddress;
								}
								else
								{
									// 사용자가 지번 주소를 선택했을 경우(J)
									fullAddr = data.jibunAddress;
								}

								// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
								if (data.userSelectedType === "R")
								{
									// 법정동명이 있을 경우 추가한다.
									if (data.bname !== "")
									{
										extraAddr += data.bname;
									}

									// 건물명이 있을 경우 추가한다.
									if (data.buildingName !== "")
									{
										extraAddr += (extraAddr !== "" ? ", " + data.buildingName : data.buildingName);
									}

									// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
									fullAddr += (extraAddr !== "" ? " (" + extraAddr + ")" : "");
								}

								// 우편번호와 주소 정보를 해당 필드에 넣는다.
								document.getElementById("zip").value      = data.zonecode;			// 5자리 새우편번호 사용
								document.getElementById("address1").value = fullAddr;

								// 커서를 상세주소 필드로 이동한다.
								document.getElementById("address2").focus();
							}
						}).open();
					}
				</script>
			</td>
		</tr>
		<tr>
			<td>
				<input type="text" class="inputFull" name="address1" id="address1" title="상세주소1" value="<?=$row[address1]?>" />
			</td>
		</tr>	
		<tr>
			<td>
				<input type="text" class="inputFull" name="address2" id="address2" title="상세주소2" value="<?=$row[address2]?>"/>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="smart_content">내용</label></th>
			<td>
				<textarea name="comments" id="smart_content" rows="2" cols="2" style="height:412px;" class="inputFull"><?=$row[comments]?></textarea>
			</td>
		</tr>
		<? if($board[file_count] != 0){ ?>
		<tr>
			<th scope="row">이미지</th>
				<td>
				<?
					for($i = 1; $i <= $board[file_count]; $i++) {
							$file_title = $row["file_".$i];
							$del_ = "파일을 첨부해주세요.";
							$del_2 = "";
							if($file_title){
								 $del_2 = "<label for='file_$i' class='button white'><span>기존 첨부파일 : <a href='/upload/$board_id/$file_title' target='_blank'>$file_title</a></label>";
							}
							echo "<div><div class='designFile'><em>첨부파일</em><input type='text' readonly='readonly' value=''/><input type='file' name='file_$i' id='file_$i'/><label for='file_$i' class='btn'> $del_</label></div>$del_2 </div>";
					 }
				?>
				<em class="tip">첨부파일 가능 용량은 <?=$board[file_limit_size]?>MB 입니다</em>
			</td>
		</tr>
		<? } ?>
		<? if($board[spam_auth] == "Y"){ ?>
		<? $_SESSION[rand_auth] = rand(0000,9999); ?>
			<tr>
				<th scope="row"><span class="marking">필수항목</span><label for="rand_auth_">스팸방지</label></th>
				<td class="spam"><span><?=$_SESSION[rand_auth]?></span><input type="text" name="rand_auth_" id="rand_auth_" placeholder="좌측 번호를 입력해주세요." class="input200 required" title="스팸방지번호"/></td>
			</tr>
		<? } ?>
		<? if($board[is_membership] != "Y"){ ?>
		<tr>
			<th scope="row"><span class="marking">필수항목</span><label for="pin">비밀번호</label></th>
			<td>
				<input type="password" name="password" id="pin" value="" class="required" title="비밀번호"/>
				<em class="tip">※ 글 수정, 삭제시 필요합니다.</em>
			</td>
		</tr>
		<? } ?>
			
	</tbody>
</table>
<script type="text/javascript">
	$(":checkbox[name='notice'][value='<?=$row['notice']?>']").prop("checked", true);
	$(":checkbox[name='secret'][value='<?=$row['secret']?>']").prop("checked", true);
	$("select[name='category'] option[value='<?=$row[category]?>']").attr("selected", true);
</script>
<!-- //글쓰기 -->
<!-- 버튼 -->
<div class="btn_area custom">
	<?
	echo "<a href=\"javascript:do_Process('list', '".$board_id."', '".$keyword."', '".$search_key."','".$start."', '".$cate."', '', '');\" class=\"button lg\">취소</a>";
	echo "<input type=\"button\" class=\"button lg\" onclick=\"javascript:do_Proc('".$mode."', '".$board_id."', '".$keyword."', '".$search_key."','".$start."');\" value=\"저장\">";	
	?>
</div>
</form>

<!-- //버튼 -->

