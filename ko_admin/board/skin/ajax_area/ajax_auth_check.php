<? include $_SERVER['DOCUMENT_ROOT'] . "/head.php"; ?>
<?
	$board = mysqli_fetch_array(mysqli_query($dbp, "SELECT * FROM koweb_board_config WHERE id = '$board_id' LIMIT 1"));
?>
<!-- 글작성확인시 패스워드 별로도 빼서 사용하시면 됩니다 -->
<form action="<?=$PHP_SELF?>" method="get" name="auth_form" />
<input type="hidden" name="board_id" value="<?=$board_id?>" />
<input type="hidden" name="start" value="<?=$start?>" />
<input type="hidden" name="mode" value="<?=$return_mode?>" />
<input type="hidden" name="no" value="<?=$no?>" />
<input type="hidden" name="start" value="<?=$start?>" />
<div class="secret_area">
	<p>글을 작성할 때 설정한 비밀번호를 입력해주세요.</p>
	<input type="password" id="passwd" name="password" maxlength="30" placeholder="비밀번호">
	<a href="#" onclick="javascript:do_Process('<?=$return_mode?>', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$no?>', $('#passwd').val())" class="button">확인</a>
	<a href="#" onclick="history.go(-1);" class="button gray">취소</a>
</div>
</form>
<!-- //글작성확인시 패스워드 -->
