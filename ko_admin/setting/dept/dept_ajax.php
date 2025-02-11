<? 
	include $_SERVER['DOCUMENT_ROOT'] . "/head.php";

	$cate_limit = "6";

	if($variable != "new"){
		$query = "SELECT * FROM koweb_dept WHERE state='Y' AND ref_no='$variable[0]' AND depth='$depth'";
		$result = mysqli_query($dbp, $query);
		$total = mysqli_num_rows($result);
		$depth = $depth+1;
		if($depth < $cate_limit){
			$onchange = "onchange=\"javascript:action_ajax($(this).val(), '$depth');\"";
		} else {
			$onchange = "";
		}
?>
	<? if($total > 0) { ?>
		<select name="category[]" id="category" data-action-ajax="category" data-depth-no="<?=$depth?>" <?=$onchange?> multiple style="width:150px; height:200px; overflow-y:hidden;">
			<option value="new">신규 부서생성</option>
		<? while($row = mysqli_fetch_array($result)){ ?>
			<option value="<?=$row[no]?>"><?=$row[dept]?></option>
		<? } ?>
		</select>
	<?	} else { ?>
		<select name="category[]" id="category" data-action-ajax="category" data-depth-no="<?=$depth?>" <?=$onchange?> multiple style="width:150px; height:200px; overflow-y:hidden;">
			<option value="new">신규 부서생성</option>
		</select>
	<? } ?>
<? } ?>