<?	
	class StringEncrypter
	{
		const STRENCRYPTER_BLOCK_SIZE		= 16 ;	// 16 bytes

		private $key ;
		private $initialVector ;

		function __construct ($key, $initialVector)
		{
			if ( !is_string ($key) or $key == "" )
				throw new Exception ("The key must be a non-empty string.") ;

			if ( !is_string ($initialVector) or $initialVector == "" )
				throw new Exception ("The initial vector must be a non-empty string.") ;

			// Initialize an encryption key and an initial vector(md5).
			//$this->key = md5 ($key, TRUE) ;
			//$this->initialVector = md5 ($initialVector, TRUE) ;

			// Initialize an encryption key and an initial vector(sha256).
			$this->key = hash("sha256", $key);
			$this->initialVector = hash("sha256", $initialVector);

			// Initialize key and an initial vector.
			//$this->key =$key;
			//$this->initialVector = $initialVector;
		}

		public function encrypt ($value)
		{
			if ( is_null ($value) )
				$value = "" ;

			if ( !($value) )
				throw new Exception ("A non-string value can not be encrypted.") ;


			// Append padding
			$value = self::toPkcs7 ($value) ;

			// Encrypt the value.
			$output = mcrypt_encrypt (MCRYPT_RIJNDAEL_128, $this->key, $value, MCRYPT_MODE_CBC, $this->initialVector) ;

			// Return a base64 encoded string of the encrypted value.
			return base64_encode ($output) ;
		}

		public function decrypt ($value)
		{
			if ( !is_string ($value) or $value == "" )
				throw new Exception ("The cipher string must be a non-empty string.") ;


			// Decode base64 encoding. 
			$value = base64_decode ($value) ;

			// Decrypt the value.
			$output = mcrypt_decrypt (MCRYPT_RIJNDAEL_128, $this->key, $value, MCRYPT_MODE_CBC, $this->initialVector) ;

			// Strip padding and return.
			return self::fromPkcs7 ($output) ;
		}

		private static function toPkcs7 ($value)
		{
			if ( is_null ($value) )
				$value = "" ;

			if ( !is_string ($value) )
				throw new Exception ("A non-string value can not be used to pad.") ;


			// Get a number of bytes to pad.
			$padSize = self::STRENCRYPTER_BLOCK_SIZE - (strlen ($value) % self::STRENCRYPTER_BLOCK_SIZE) ;

			// Add padding and return.
			return $value . str_repeat (chr ($padSize), $padSize) ;
		}

		private static function fromPkcs7 ($value)
		{
			if ( !is_string ($value) or $value == "" )
				throw new Exception ("The string padded by PKCS7 must be a non-empty string.") ;

			$valueLen = strlen ($value) ;

			// The length of the string must be a multiple of block size.
			if ( $valueLen % self::STRENCRYPTER_BLOCK_SIZE > 0 )
				throw new Exception ("The length of the string is not a multiple of block size.") ;


			// Get the padding size.
			$padSize = ord ($value{$valueLen - 1}) ;

			// The padding size must be a number greater than 0 and less equal than the block size.
			if ( ($padSize < 1) or ($padSize > self::STRENCRYPTER_BLOCK_SIZE) )
				throw new Exception ("The padding size must be a number greater than 0 and less equal than the block size.") ;

			// Check padding.
			for ($i = 0; $i < $padSize; $i++)
			{
				if ( ord ($value{$valueLen - $i - 1}) != $padSize )
					throw new Exception ("A padded value is not valid.") ;
			}

			// Strip padding and return.
			return substr ($value, 0, $valueLen - $padSize) ;
		}
	}

	//키값, 초기 벡터값 선언(16bit로 설정하기)
	define ("KEY", "s*hi!np2r)o_k7e^") ;   
	define ("IV", "sh@inpr4o_v8ect2") ; 

	//초기화
	$encrypt = new StringEncrypter(KEY, IV);

	//암호화
	$encrypted = $encrypt -> encrypt($user_pw);
	//복호화
	$decrypted = $encrypt -> decrypt($encrypted);

	//echo "암호화 테스트 : " . $encrypted . "\n"; 
	//echo "복호화 테스트 : " . $decrypted;
	echo $encrypted;

	/*
	아래 함수로 현재 파일 ajax로 호출
	function btn_click() {
		var user_pw = $("#user_pw").val();

		$.ajax ({
			url	: "encrypter.php", 
			type	: "POST", 
			data  : {user_pw : user_pw},
			success : function(data) {
				//alert(data);
				$("#user_pw").val(data);
				//$("#frm").submit();
			},
			error	: function(request, status, error) {
				console.log("code: " + request.status);
				console.log("message: " + request.responseText);
				console.log("error: " + error);
			}
		});
	}
*/
?>


