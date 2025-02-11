<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php
	@session_start();
	header('Content-Type: text/html; charset=utf-8');
	include_once  $_SERVER['DOCUMENT_ROOT'] . "/head.php";

	/**************************************************************************
		���ϸ� : hs_cnfrm_popup3.php
		
		����Ȯ�μ��� ��� ȭ��(return url)
	**************************************************************************/
	
	/* ���� ���� �׸� */
	$rqstSiteNm	=	$_POST["rqst_site_nm"];			// ���ӵ�����	
	$rqstCausCd	=	$_POST["hs_cert_rqst_caus_cd"];	// ������û�����ڵ� 2byte  (00:ȸ������, 01:��������, 02:ȸ����������, 03:��й�ȣã��, 04:��ǰ����, 99:��Ÿ)

	/**************************************************************************
	 * ��� ȣ��	; ����Ȯ�μ��� ��� �����͸� ��ȣȭ�Ѵ�.
	 **************************************************************************/

	// ������� ��ȣȭ ������
	$encInfo = $_POST["encInfo"];
	//KCB���� ����Ű
	$WEBPUBKEY = trim($_POST["WEBPUBKEY"]);
	//KCB���� ����
	$WEBSIGNATURE = trim($_POST["WEBSIGNATURE"]);

	/**************************************************************************
	 * �Ķ���Ϳ� ���� ��ȿ�����θ� �����Ѵ�.
	 **************************************************************************/
	if(preg_match('~[^0-9a-zA-Z+/=]~', $encInfo, $match)) {echo "�Է� �� Ȯ���� �ʿ��մϴ�"; exit;}
	if(preg_match('~[^0-9a-zA-Z+/=]~', $WEBPUBKEY, $match)) {echo "�Է� �� Ȯ���� �ʿ��մϴ�"; exit;}
	if(preg_match('~[^0-9a-zA-Z+/=]~', $WEBSIGNATURE, $match)) {echo "�Է� �� Ȯ���� �ʿ��մϴ�"; exit;}

	// ########################################################################
	// # KCB�κ��� �ο����� ȸ�����ڵ�(���̵�) ���� (12�ڸ�)
	// ########################################################################
	//$memId = "V12960000000";										// ȸ�����ڵ�(���̵�)
	$memId = $site[namecheck_key];										// ȸ�����ڵ�(���̵�)

	// ########################################################################
	// # ���ȯ�� ���� �ʿ�
	// ########################################################################
	//$endPointUrl = "http://tsafe.ok-name.co.kr:29080/KcbWebService/OkNameService";//EndPointURL, �׽�Ʈ ����
	$endPointUrl = "http://safe.ok-name.co.kr/KcbWebService/OkNameService";// � ����
		  
	// ########################################################################
	// # ��ȣȭŰ ���� ���� (������) - ������ �־��� ���ϸ����� �ڵ� �����Ǹ� �������� ������ S211������ �߻���
	// # ������ �ſ��ʿ� ���ŵǸ� ���� ������ ���ŵ��� ������ ��ȭȭ�����Ͱ� ������ ������ �߻���.
	// ########################################################################
	//$keyPath = "/okname/safecert_".$memId."_test.key";	// �׽�Ʈ  Ű����
	$keyPath = $_SERVER[DOCUMENT_ROOT] . "/okname/safecert_".$memId.".key";	// �  Ű����

	// ########################################################################
	// # �α� ��� ���� �� ���� �ο� (hs_cnfrm_popup2.asp���� ������ ���� �����ϰ� ����)
	// ########################################################################
	$logPath = "/okname/log";

	// ########################################################################
	// # �ɼǰ��� 'D','L'�� �߰��ϴ� ��� �α�(logPath������ ������)�� ������.
	// # �ý���(ȯ�溯�� LANG����)�� UTF-8�� ��� 'U'�ɼ� �߰� ex)$option='SLU'
	// ########################################################################
	$options = "SU";	// S:���������ȣȭ
		
	// ��ɾ�
	$cmd = array($keyPath, $memId, $endPointUrl, $WEBPUBKEY, $WEBSIGNATURE, $encInfo, $logPath, $options);
	
	/**************************************************************************
	okname ����
	**************************************************************************/
	$output = NULL;
	// ����
	$ret = okname($cmd, $output);
    
	$retcode = "";

	if($ret == 0) {
		$result = explode("\n", $output);
		$retcode = $result[0];
	}
	else {
		if($ret <=200)
			$retcode=sprintf("B%03d", $ret);
		else
			$retcode=sprintf("S%03d", $ret);
	}
?>
<title>KCB ����Ȯ�μ���</title>
<script language="javascript" type="text/javascript" >
	function fncOpenerSubmit() {
		window.opener.location.href = window.opener.document.URL;
		self.close();
	}	
</script>
</head>
<body>

<?php
	//**************************************************************************
	// ��ȣȭ�� ����Ȯ�� ��� ������ 
	// ���߽� Ȯ�� �뵵�� ����ϸ� ��� �ּ� �Ǵ� ���� ó�� �ʿ�
	//**************************************************************************
?>
	<input type="hidden" name="ȸ�����ڵ�"			value="<?=$memId?>"/>
	<input type="hidden" name="������û�����ڵ�"	value="<?=$rqstCausCd?>"/>
	<input type="hidden" name="���ӵ�����"			value="<?=$rqstSiteNm?>"/>
	<input type="hidden" name="ó������ڵ�"		value="<?=$retcode?>"/>		 
	
<?php
 	if ($ret == 0) {
	$_SESSION['DI']		= $result[4];				// DI
	$_SESSION['CI']		= $result[5];				// CI
?>
	<input type="hidden" name="ó������޽���"		value="<?=$result[1] ?>"/>		 
	<input type="hidden" name="�ŷ��Ϸù�ȣ"		value="<?=$result[2] ?>"/>		 
	<input type="hidden" name="�����Ͻ�"			value="<?=$result[3] ?>"/>		 
	<input type="hidden" name="DI"					value="<?=$result[4] ?>"/>		 
	<input type="hidden" name="CI"					value="<?=$result[5] ?>"/>		 
	<input type="hidden" name="����"				value="<?=$result[7] ?>"/>		 
	<input type="hidden" name="�������"			value="<?=$result[8] ?>"/>		 
	<input type="hidden" name="����"				value="<?=$result[9] ?>"/>		 
	<input type="hidden" name="���ܱ��α���"		value="<?=$result[10]?>"/>	 
	<input type="hidden" name="��Ż��ڵ�"			value="<?=$result[11]?>"/>	 
	<input type="hidden" name="�޴�����ȣ"			value="<?=$result[12]?>"/>	 
	<input type="hidden" name="���ϸ޽���"			value="<?=$result[16]?>"/>	 
<?php
	}
?>	
</body>
<?php
	if($ret == 0) {
		//������� ��ȣȭ ����
		// ��������� Ȯ���Ͽ� �������б���� ó���� �����ؾ��Ѵ�.
	 	if ($retcode == "B000") {
			echo ("<script>fncOpenerSubmit();</script>");
		}
		else {
			echo ("<script>alert('������������ : ".$retcode."'); fncOpenerSubmit();</script>");
		}
	} else {
		//������� ��ȣȭ ����
		echo ("<script>alert('���������ȣȭ ���� : ".$ret."'); </script>");
	}
?>
</html>
