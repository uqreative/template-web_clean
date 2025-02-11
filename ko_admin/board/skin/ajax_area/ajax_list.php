<? include $_SERVER['DOCUMENT_ROOT'] . "/head.php"; ?>
<?
$board = mysqli_fetch_array(mysqli_query($dbp, "SELECT * FROM koweb_board_config WHERE id = '$board_id' LIMIT 1"));
include $_SERVER['DOCUMENT_ROOT'] . "/board/board_auth.php"; 
//페이징 변수
if (!$start) $start = 0;
$scale = 1; // 리스트 수
$page_scale	= 10; // 페이징 수

//검색
if($keyword){
	$WHERE .= " AND (category LIKE '%$keyword%' OR title LIKE '%$keyword%')";
}

if($cate){
	$WHERE .= " AND category = '$cate'";
}

//리스트
$total_query = "SELECT * FROM $board_id WHERE 1=1 $WHERE ORDER BY notice DESC, reg_date DESC, no DESC";
$total_result = mysqli_query($dbp, $total_query);
$total = mysqli_num_rows($total_result);


$query = "SELECT * FROM $board_id WHERE 1=1  $WHERE ORDER BY notice DESC, ref_group DESC, depth ASC, no DESC LIMIT $start, $scale";
$result = mysqli_query($dbp, $query);
?>
<!-- 좌우배치 -->
<div class="box_double shop">
	<!-- map -->
	<div class="map">
		<div class="nation">
			<!-- 지도에서 지역선택하고 검색시 a태그 class on 추가 -->
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '서울', '<?=$row[no]?>', '')" data-map="01" data-map-name="서울">서울</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '인천', '<?=$row[no]?>', '')" data-map="02" data-map-name="인천">인천</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '경기', '<?=$row[no]?>', '')" data-map="03" data-map-name="경기">경기</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '대전', '<?=$row[no]?>', '')" data-map="04" data-map-name="대전">대전</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '충남', '<?=$row[no]?>', '')" data-map="05" data-map-name="충남">충남</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '충북', '<?=$row[no]?>', '')" data-map="06" data-map-name="충북">충북</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '강원', '<?=$row[no]?>', '')" data-map="07" data-map-name="강원">강원</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '광주', '<?=$row[no]?>', '')" data-map="08" data-map-name="광주">광주</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '전남', '<?=$row[no]?>', '')" data-map="09" data-map-name="전남">전남</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '전북', '<?=$row[no]?>', '')" data-map="10" data-map-name="전북">전북</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '대구', '<?=$row[no]?>', '')" data-map="11" data-map-name="대구">대구</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '경북', '<?=$row[no]?>', '')" data-map="12" data-map-name="경북">경북</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '경남', '<?=$row[no]?>', '')" data-map="13" data-map-name="경남">경남</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '울산', '<?=$row[no]?>', '')" data-map="14" data-map-name="울산">울산</a>
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '부산', '<?=$row[no]?>', '')" data-map="15" data-map-name="부산">부산</a>
		</div>
		<div class="jeju">
			<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '제주', '<?=$row[no]?>', '')" data-map="16" data-map-name="제주">제주</a>
		</div>
	</div>
	<!-- //tree -->
	
	<!-- 검색 -->
	<div class="search">
		<em><i>전국 매장</i>을 검색해 보실 수 있습니다.</em>
		<p>지도에 해당 지역을 클릭하시거나 지역명 또는 매장명을 입력해 주세요.</p>
		
		<div class="form">
			<form action="<?=$_SERVER['REQUEST_URL']?>" method="get" />
			<input type="hidden" name="board_id" value="<?=$board_id?>" />
			<input type="hidden" name="start" value="<?=$start?>" />
				<input type="text" name="keyword" id="keyword" placeholder="검색어를 입력하세요." value="<?=$keyword?>"/>
				<input type="button" name="" id="search_btn_on" class="search-btn" value="검색"/>
				<script type="text/javascript">
				$(function(){
					$("#search_btn_on").click(function(){
						var board_id = "<?=$board[id]?>";
						var keyword = $("#keyword").val();
						var start = "<?=$start?>";
						var cate = "<?=$cate?>";

						do_Process('list', board_id, keyword, '', '0', cate, '', '');
					});
				});
				</script>
			</form>
		</div>
	</div>
	<!-- //검색 -->
</div>

<? if($cate){ ?>
<script type="text/javascript">
	$("[data-map-name]").removeClass("on");
	$("[data-map-name="+"<?=$cate?>"+"]").addClass("on");
</script>
<? } ?>


<!-- 검색결과 -->
<table class="bbsList">
	<caption>매장찾기 목록</caption>
	<colgroup>
		<col data-shop="area" style="width:15%"/>
		<col data-shop="name"/>
		<col data-shop="address" style="width:25%"/>
		<col data-shop="tel" style="width:20%"/>
	</colgroup>
	<thead>
		<tr>
			<th scope="col" data-shop="number">지역</th>
			<th scope="col" data-shop="name">매장명</th>
			<th scope="col" data-shop="address">주소</th>
			<th scope="col" data-shop="tel">전화번호</th>
			<th scope="col" data-shop="view">View</th>
		</tr>
	</thead>
	<tbody>
	<? if($total > 0) { ?>
		<? $f_no = $total - $start; ?>
		<? while($row = mysqli_fetch_array($result)){ ?>
		<? 
			$date_array = explode("-",  $row[reg_date]); 
			if($row[notice] == "Y") { 
				$no_title = "<em class='notice'>공지</em>";
				$f_no--;
			} else {
				$no_title = $f_no--;
			}
			$title_reply = "";

			//현재글 
			if($no == $row[no]){ 
				$total_txt = "<img src=\"/img/now_gul.gif\" />";
			}

			//7일 new 아이콘 
			if ($row[reg_date] >= date("Y-m-d", strtotime("-7 day"))) {
				 $title_reply = "<em class=\"new\">새글</em>";	
			} else unset($title_reply);

			//depth 가 1이상이면 답변 아이콘
			if($row[depth] > 0){
				//$padding_style = $row[depth] * 40;
				$title_reply = "<em class='reply'>답변</em>";
			}

			$reply_query_count = "SELECT * FROM board_comment WHERE ref_board_no = '$row[no]' AND board_id = '$board_id'";
			$reply_count_result = mysqli_query($dbp, $reply_query_count);
			$reply_count = mysqli_num_rows($reply_count_result);

			if($reply_count > 0){
				$reply_query = "SELECT * FROM board_comment ref_board_no = '$row[no]' AND board_id = '$board_id ORDER BY reg_date DESC";
				$reply_result = mysqli_query($dbp, $reply_query);
			}

		?>
			<!-- 반복 -->
			<tr>
				<td data-shop="area"><?=$row[category]?></td>
				<td data-shop="name"><?=$row[title]?></td>
				<td data-shop="address"><?=$row[address1]?> <?=$row[address2]?></td>
				<td data-shop="tel"><?=$row[phone]?></td>
				<td data-shop="view"><a href="javascript:do_Process('view', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$row[no]?>', '<?=$password?>')" class="button sm white">자세히보기</a></td>
			</tr>
			<!-- //반복 -->
		<? } ?>
	<? } else { ?>
		<tr>
			<td colspan="5" class="none">등록된 매장이 없습니다.</td>
		</tr>
	<? } ?>
	</tbody>
</table>



<div class="box-pagin-flex <? if($auth_write){?>col<? } ?>">
	<div class="pagination">
		<?
		if ($total == 0) $total = 1;
		$page_url = $PHP_SELF."?program_id=$program_id&amp;search_key=$search_key&amp;keyword=$keyword&search2=$search2";

		// 처음
		echo "<a href=\"javascript:do_Process('list', '".$board_id."', '".$keyword."', '".$search_key."','0', '".$cate."', '', '');\" class=\"btn_first\"><span>맨처음</span></a>";

		$page = floor($start / ($scale * $page_scale));
		if ($start + $scale >  $scale * $page_scale) {
			$pre_start = $start - $scale * $page_scale ;
			echo "<a href=\"javascript:do_Process('list', '".$board_id."', '".$keyword."', '".$search_key."','".$pre_start."', '".$cate."', '', '');\" class=\"btn_prev\"><span>이전</span></a>";
		}

		for ($vj = 0; $vj < $page_scale ; $vj++) {
			$ln = ($page * $page_scale + $vj) * $scale;
			$vk = $page * $page_scale + $vj + 1 ;
			$pageing = $vk - 1;
			if ($ln < $total) {
				if ($ln != $start) echo "<a href=\"javascript:do_Process('list', '".$board_id."', '".$keyword."', '".$search_key."','".$ln."', '".$cate."', '', '');\">$vk</a>";
				else echo "<span>$vk</span>";
			}
		}

		// 마지막
		$end_page = floor($total - $scale) + 1;
		if ($end_page <= 0)	$end_page = 0;

		if ($total > (($page + 1) * $scale * $page_scale)) {
			$n_start = ($page + 1) * $scale * $page_scale ;
			echo "<a href=\"javascript:do_Process('list', '".$board_id."', '".$keyword."', '".$search_key."','".$n_start."', '".$cate."', '', '');\" class=\"btn_next\"><span>다음</span></a>";
		}

		$end_page = ceil($total / $scale);
		if ($total) $end_start = ($end_page -1) * $scale;
		else $end_start = $end_page;

		echo "<a href=\"javascript:do_Process('list', '".$board_id."', '".$keyword."', '".$search_key."','".$end_start."', '".$cate."', '', '');\" class=\"btn_last\"><span>맨마지막</span></a>";
		?>
	</div>

<? if($auth_write){?>
<!-- 버튼 -->
	<div class="btn">
		<a href="javascript:do_Process('write', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '', '')" class="button"><span>글쓰기</span></a>
	</div>
<? } ?>
</div>