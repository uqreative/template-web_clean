<?


function fileupload($file_name, $file , $uploadfolder, $thumbnail_name , $thumbnail =0 , $thumbnail_w =1000 ,$thumbnail_h = 1000  ,$img_thumbnail =""  ,$img_del=""  ){
	$file_info=@getimagesize($file);		//이미지1 정보
	if ($file_info[2] == 1 ) $file_exe ="gif" ;
	if ($file_info[2] == 2 ) $file_exe ="jpg" ;   
	 $thumbnail_name =  $thumbnail_name.".".$file_exe  ; 
	  if ($thumbnail   ){
		$imagechecked = thumbnailImageCreate($file,$uploadfolder.$thumbnail_name  , $thumbnail_w ,$thumbnail_h, $img_thumbnail , $file_exe , "", 99); 
		$file_w =  $imagechecked[2]  ;  
		$file_h =  $imagechecked[3]  ;
	  }else{

		  
 
		@move_uploaded_file ($file,   $uploadfolder.$thumbnail_name );
		@unlink($img_del);
	
		$file_w  = $file_info[0];  
		$file_h	= $file_info[1];  
	  } 
	
  return array( $thumbnail_name , $file_w , $file_h );
} 
 


function imagescheck($file_name, $file ){
	if(!empty($file_name)){   
	    $file_info=@getimagesize($file);		 
		if(($file_info[2]!=1) && ($file_info[2]!=2)){
			msgview("이미지 형식을 gif , jpg 로 입력해 주세요", -1);
			exit;
			return 0;
		}
		return 1 ;
	}	
	return 0 ;
}



/*----------------------------------------------------------------------------- 
 *	> 함수 설명 <
 *	array(int 결과값(1=성공, 0=실패), string 이미지소스format방식, string 결과 메시지) THUMBNAIL_IMAGE_CREATE($image_path, $save_path, $max_with_size=100, $max_height_size=100, $save_format="jpg", $background_color="WHITE", $image_quality=100)
 *	이미지 섬네일 작성 함수 
 *	
 *	> 전달인자 설명 <
 *	$image_path				:				원본 경로
 *	$save_path				:				저장 경로
 *	$max_with_size			:				가로 최대 사이즈
 *	$max_height_size		:				세로 최대 사이즈
 *	$waterMakeImagePath		:				워터마크 이미지(값이 있는경우 실행)
 *	$save_format			:				저장 포맷 형식
 *	$background_color		:				세로 최대 사이즈
 *	$image_quality			:				압축률
 *	
 -----------------------------------------------------------------------------*/



FUNCTION thumbnailImageCreate($image_path, $save_path, $max_with_size=130, $max_height_size=130, $waterMakeImagePath="", $save_format="jpg", $background_color="WHITE", $image_quality=78){
	
	/**
	 *	원본 이미지 사이즈 알아오기
	 *	$image_path_size_info[0]		:		원본 이미지 가로 사이즈
	 *	$image_path_size_info[1]		:		원본 이미지 세로 사이즈
	 *	$image_path_size_info[2]		:		원본 이미지 포맷 방식
	 *												1 - GIF
	 *												2 - JPG
	 *												3 - PNG
	 *												4 - SWF
	 *													   
	 *	$image_path_size_info[3]		:		원본 이미지 문자열 ex) height="xxx" width="xxx"
	 */
		$image_path_size_info=getimagesize($image_path);
		
		
		/**
		 *	THUMBNAIL IMAGE 사이즈 설정
		 */

		$save_path_width_size = $image_path_size_info[0];
		$save_path_height_size = $image_path_size_info[1];


 	if( $save_path_width_size  > $max_with_size  ){
		$save_path_width_size  = $max_with_size;
		$save_path_height_size = intval($image_path_size_info[1] / ( $image_path_size_info[0] / $max_with_size ));
	} 
 
		$save_image=ImageCreateTrueColor($save_path_width_size , $save_path_height_size );
		
		
	 
		switch($image_path_size_info[2]){
				case 1 :
							/**
							 *	GIF 포맷 형식
							 */	
							$source_image=ImageCreateFromGIF($image_path);
							$source_format="gif";
							break;
				case 2 :
							/**
							 *	JPG 포맷 형식
							 */
							$source_image=ImageCreateFromJPEG($image_path);
							$source_format="jpg";
							break;
				case 3 :
							/**
							 *	PNG 포맷 형식
							 */
							$source_image=ImageCreateFromPNG($image_path);
							$source_format="png";
							break;
				default :
							/**
							 *	GIF, JPG, PNG 포맷방식이 아닐경우 오류 값을 리턴 후 종료
							 */
							return array(0, $source_format, "!!! 원본이미지가 GIF, JPG, PNG 포맷 방식이 아s니어서 이미지 정보를 읽어올 수 없습니다. !!!");
		}
		
		
		/**
		 *	이미지 사이즈 소숫점 자리 삭제
		 */
		$save_path_width_size = round($save_path_width_size);
		$save_path_height_size = round($save_path_height_size);
		
		
		/**
		 *	$save_image=ImageCreate($save_path_width_size, $save_path_height_size) 부분에 원본이미지로 부터 복사본을 그린다.
		 *	$arg1		:		ImageCreateTrueColor 리턴 인자(붙여넣기 할 이미지)
		 *	$arg2		:		ImageCreateFromXXX 리턴 인자(복사할 이미지)
		 *	$arg3		:		붙여넣기 할 이미지의 X 시작점
		 *	$arg4		:		붙여넣기 할 이미지의 Y 시작점
		 *	$arg5		:		복사할 이미지의 X 시작점
		 *	$arg6		:		복사할 이미지의 Y 시작점
		 *	$arg7		:		붙여넣기 할 이미지의 X 끝점
		 *	$arg8		:		붙여넣기 할 이미지의 Y 끝점
		 *	$arg9		:		복사할 이미지의 X 끝점
		 *	$arg10		:		복사할 이미지의 Y 끝점
		 */ 
//		if(ImageCopyResized($save_image ,$source_image, 0, 0, 0, 0, $save_path_width_size, $save_path_height_size, ImageSX($source_image), ImageSY($source_image))){
		if(ImageCopyResampled($save_image ,$source_image, 0, 0, 0, 0, $save_path_width_size, $save_path_height_size, ImageSX($source_image), ImageSY($source_image))){
				/**
				 *	저정할 이미지의 포맷방식 선택 기본값 jpg
				 */
				switch($save_format){
						case "jpg"	:
						case "jpeg"	:
						case "JPG"	:
						case "JPEG"	:
										if(ImageJPEG($save_image, $save_path, $image_quality)){
												#####----- 워터마크처리 체크 -----#####
												if($waterMakeImagePath){
														#####----- 워터마크 처리 함수 호출 -----#####
														$waterMakeResult = imageWaterMaking($save_path, $waterMakeImagePath);
														if($waterMakeResult[0]){
																return array(1, $source_format, $save_path_width_size , $save_path_height_size  , "$save_path_width_size * $save_path_height_size $save_path JPG 포맷 이미지 생성 - 워터마크처리");
														}else{
																return array(0, $source_format, $save_path_width_size , $save_path_height_size  ,  "!!! $save_path_width_size * $save_path_height_size JPG 포맷 이미지 생성에 실패 했습니다 - 원인 : 워커마크처리오류. !!!");
														}
												}else{
														return array(1, $source_format, $save_path_width_size , $save_path_height_size  , "$save_path_width_size * $save_path_height_size $save_path JPG 포맷 이미지 생성");
												}
										}else{
												return array(0, $source_format, $save_path_width_size , $save_path_height_size  , "!!! $save_path_width_size * $save_path_height_size JPG 포맷 이미지 생성에 실패 했습니다. !!!");
										}
								break;
								
						case "png"	:
						case "PNG"	:
										if(ImagePNG($save_image, $save_path, 9 )){
												#####----- 워터마크처리 체크 -----#####
												if($waterMakeImagePath){
														#####----- 워터마크 처리 함수 호출 -----#####
														$waterMakeResult = imageWaterMaking($save_path, $waterMakeImagePath);
														if($waterMakeResult[0]){
																return array(1, $source_format,  $save_path_width_size , $save_path_height_size  , "$save_path_width_size * $save_path_height_size $save_path PNG 포맷 이미지 생성 - 워터마크처리");
														}else{
																return array(0, $source_format ,$save_path_width_size , $save_path_height_size  , "!!! $save_path_width_size * $save_path_height_size PNG 포맷 이미지 생성에 실패 했습니다 - 원인 : 워커마크처리오류. !!!");
														}
												}else{
														return array(1, $source_format,$save_path_width_size , $save_path_height_size ,  "$save_path_width_size * $save_path_height_size $save_path PNG 포맷 이미지 생성");
												}
										}else{
												return array(0, $source_format, "!!! $save_path_width_size * $save_path_height_size PNG 포맷 이미지 생성에 실패 했습니다. !!!");
										}
								break;
						case "gif";
						case "GIF";
										if(ImageGIF($save_image, $save_path, $image_quality)){
												#####----- 워터마크처리 체크 -----#####
												if($waterMakeImagePath){
														#####----- 워터마크 처리 함수 호출 -----#####
														$waterMakeResult = imageWaterMaking($save_path, $waterMakeImagePath);
														if($waterMakeResult[0]){
																return array(1, $source_format,$save_path_width_size , $save_path_height_size  ,  "$save_path_width_size * $save_path_height_size $save_path GIF 포맷 이미지 생성 - 워터마크처리");
														}else{
																return array(0, $source_format, $save_path_width_size , $save_path_height_size  ,  "!!! $save_path_width_size * $save_path_height_size GIF 포맷 이미지 생성에 실패 했습니다 - 원인 : 워커마크처리오류. !!!");
														}
												}else{
														return array(1, $source_format, $save_path_width_size , $save_path_height_size  , "$save_path_width_size * $save_path_height_size $save_path GIF 포맷 이미지 생성");
												}
										}else{
												return array(0, $source_format,$save_path_width_size , $save_path_height_size  ,  "!!! $save_path_width_size * $save_path_height_size GIF 포맷 이미지 생성에 실패 했습니다. !!!");
										}
								break;
						default :
								return array(0, $source_format, $save_path_width_size , $save_path_height_size  , "!!! 입력하신 포맷 이미지는 지원되지 않습니다. !!!");
				}
		}else{
				return array(0, $source_format, $save_path_width_size , $save_path_height_size  , "!!! ImageCopyResized 함수 수행시 에러가 발생했습니다. !!!");
		}
}



/*----------------------------------------------------------------------------- 
 
 *	> 함수 설명 <
 *	워터마크 처리 함수
 *	
 *	> 전달인자 설명 <
 *	$imagePath				:		워터마크를 처리할 이미지
 *	$waterMakeSourceImage	:		워터마크 이미지
 *	$ARGimageQuality		:		이미지 퀄리티
 *	
 -----------------------------------------------------------------------------*/
FUNCTION imageWaterMaking($ARGimagePath, $ARGwaterMakeSourceImage, $ARGimageQuality = 70){
		#####----- 이미지 정보 가져오기 -----#####
		$getSourceImageInfo = GETIMAGESIZE($ARGimagePath);
		#####----- 원본 이미지 검사 -----#####
		if(!$getSourceImageInfo[0]){
				return ARRAY(0, "!!! 원본 이미지가 존재하지 않습니다. !!!");
		}
		$getwaterMakeSourceImageInfo = GETIMAGESIZE($ARGwaterMakeSourceImage);
		#####----- 워터마크 이미지 검사 -----#####
		if(!$getwaterMakeSourceImageInfo[0]){
				return ARRAY(0, "!!! 워터마크 이미지가 존재하지 않습니다. !!!");
		}
		
		#####----- 원본 이미지 생성(로드) -----#####
		switch($getSourceImageInfo[2]){
				case 1 :	#####----- GIF 포맷 형식 -----#####
							$sourceImage = IMAGECREATEFROMGIF($ARGimagePath);
							break;
				case 2 :	#####----- JPG 포맷 형식 -----#####
							$sourceImage = IMAGECREATEFROMJPEG($ARGimagePath);
							break;
				case 3 :	#####----- PNG 포맷 형식 -----#####
							$sourceImage = IMAGECREATEFROMPNG($ARGimagePath);
							break;
				default :	#####----- GIF, JPG, PNG 포맷방식이 아닐경우 오류 값을 리턴 후 종료 -----#####
							return array(0, "!!! 원본이미지가 GIF, JPG, PNG 포맷 방식이 아니어서 이미지 정보를 읽어올 수 없습니다. !!!");
		}
		
		#####----- 워터마크 이미지 생성(로드) -----#####
		switch($getwaterMakeSourceImageInfo[2]){
				case 1 :	#####----- GIF 포맷 형식 -----#####
							$waterMakeSourceImage = IMAGECREATEFROMGIF($ARGwaterMakeSourceImage);
							break;
				case 2 :	#####----- JPG 포맷 형식 -----#####
							$waterMakeSourceImage = IMAGECREATEFROMJPEG($ARGwaterMakeSourceImage);
							break;
				case 3 :	#####----- PNG 포맷 형식 -----#####
							$waterMakeSourceImage = IMAGECREATEFROMPNG($ARGwaterMakeSourceImage);
							break;
				default :	#####----- GIF, JPG, PNG 포맷방식이 아닐경우 오류 값을 리턴 후 종료 -----#####
							return array(0, "!!! 워터마크이미지가 GIF, JPG, PNG 포맷 방식이 아니어서 이미지 정보를 읽어올 수 없습니다. !!!");
		}
		
		
		#####----- 워터마크 위치 구하기(중앙에 워터마크 삽입) -----#####
		$waterMakePositionWidth = ($getSourceImageInfo[0] - $getwaterMakeSourceImageInfo[0]) / 2;
		$waterMakePositionHeight = ($getSourceImageInfo[1] - $getwaterMakeSourceImageInfo[1]) / 2;
		
		#####----- 이미지 그리기 -----#####
		/**
		 *	$save_image=ImageCreate($save_path_width_size, $save_path_height_size) 부분에 원본이미지로 부터 복사본을 그린다.
		 *	$arg1		:		ImageCreateTrueColor 리턴 인자(붙여넣기 할 이미지)
		 *	$arg2		:		ImageCreateFromXXX 리턴 인자(복사할 이미지)
		 *	$arg3		:		붙여넣기 할 이미지의 X 시작점
		 *	$arg4		:		붙여넣기 할 이미지의 Y 시작점
		 *	$arg5		:		복사할 이미지의 X 시작점
		 *	$arg6		:		복사할 이미지의 Y 시작점
		 *	$arg7		:		붙여넣기 할 이미지의 X 끝점
		 *	$arg8		:		붙여넣기 할 이미지의 Y 끝점
		 *	$arg9		:		복사할 이미지의 X 끝점
		 *	$arg10		:		복사할 이미지의 Y 끝점
		 */
		IMAGECOPYRESIZED($sourceImage, $waterMakeSourceImage, $waterMakePositionWidth, $waterMakePositionHeight, 0, 0, ImageSX($waterMakeSourceImage), ImageSY($waterMakeSourceImage), ImageSX($waterMakeSourceImage), ImageSY($waterMakeSourceImage));
		
		#####----- 이미지 저장 -----#####
		switch($getSourceImageInfo[2]){
				case 1 :	#####----- GIF 포맷 형식 -----#####
							if(IMAGEGIF($sourceImage, $ARGimagePath, $ARGimageQuality)){
									return ARRAY(1, "GIF 형식 워터마크 이미지가 처리 되었습니다.");
							}else{
									return ARRAY(0, "GIF 형식 워터마크 이미지가 처리 도중 오류가 발생했습니다.");
							}
							break;
				case 2 :	#####----- JPG 포맷 형식 -----#####
							if(IMAGEJPEG($sourceImage, $ARGimagePath, $ARGimageQuality)){
									return ARRAY(1, "JPG 형식 워터마크 이미지가 처리 되었습니다.");
							}else{
									return ARRAY(0, "JPG 형식 워터마크 이미지가 처리 도중 오류가 발생했습니다.");
							}
							break;
				case 3 :	#####----- PNG 포맷 형식 -----#####
							if(IMAGEPNG($sourceImage, $ARGimagePath, $ARGimageQuality)){
									return ARRAY(1, "PNG 형식 워터마크 이미지가 처리 되었습니다.");
							}else{
									return ARRAY(0, "PNG 형식 워터마크 이미지가 처리 도중 오류가 발생했습니다.");
							}
							break;
				default :	#####----- GIF, JPG, PNG 포맷방식이 아닐경우 오류 값을 리턴 후 종료 -----#####
							return ARRAY(0, "!!! 원본마크이미지가 GIF, JPG, PNG 포맷 방식이 아니어서 이미지 정보를 읽어올 수 없습니다. !!!");
		}
		
		
}

?>
