<? include  "../head.php"; ?>
<?
$total = mysqli_fetch_array(mysqli_query($dbp, "SELECT COUNT(*) FROM koweb_member WHERE id='$variable_data'"));

if(!$total[0]){
	echo "사용 가능한 ID 입니다.";
} else {
	echo "사용 불가능한 ID 입니다.";
}
?>
