<?php
	
	// Axios에서 전달된 POST 변수를 JSON으로 incoming 해주세요. 
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)):
		$_POST = (array) json_decode(file_get_contents('php://input'), true);
	endif;
	
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require "./src/PHPMailer.php";
    //require "./src/SMTP.php";
	require "./src/POP3.php";
    require "./src/Exception.php";

    $mail = new PHPMailer(true);
    $success = false;
	$errMsg = "";
	
	
	try {
        // 서버세팅
        //디버깅 설정을 0 으로 하면 아무런 메시지가 출력되지 않습니다
        $mail -> SMTPDebug = 0; // 디버깅 설정
        //$mail -> isSMTP(); // SMTP 사용 설정

        // 지메일일 경우 smtp.gmail.com, 네이버일 경우 smtp.naver.com
        $mail -> Host = "mail.tubanretail.com";        // 네이버의 smtp 서버
        $mail -> SMTPAuth = true;                // SMTP 인증을 사용함
        $mail -> Username = "tubanretail";    // 메일 계정 (지메일일경우 지메일 계정)
        $mail -> Password = "tubanretail";             // 메일 비밀번호
        $mail -> SMTPSecure = "ssl";             // SSL을 사용함
        $mail -> Port = 465;                     // email 보낼때 사용할 포트를 지정
        $mail -> CharSet = "utf-8";              // 문자셋 인코딩

        // 보내는 메일
        $mail -> setFrom("admin@tubanretail.com", "");

        // 받는 메일
        $mail -> addAddress("cs@tubanretail.com", "");
        //$mail -> addAddress("js0424@tubanretail.com", "");
        //$mail -> addAddress("aduris@edacom.co.kr", "");
        $mail -> addAddress("hdk236p@edacom.co.kr", "");

        // 첨부파일
        // $mail -> addAttachment("./test1.zip");
        // $mail -> addAttachment("./test2.jpg");
		
		$location = $_POST["location"] ?: '-';
		$name = $_POST["name"] ?: '-';
		$phone = $_POST["phone"] ?: '-';
		$question = $_POST["question"] ?: '-';
		
		
		$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		$body .= '<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">';
		$body .= '<head>';
		$body .= '<meta http-equiv="Content-Type" content="text/html charset=UTF-8">';
		$body .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
		$body .= '<meta name="x-apple-disable-message-reformatting">';
		$body .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
		$body .= '</head>';
		$body .= '<body style="margin: 0;padding: 0;">';
		$body .= '<div class="container" style="width: 100%;margin: 0px;padding: 20px;box-sizing: border-box;background-color: cadetblue">';
		$body .= '<div class="title">';
		$body .= '<h2>창업상담신청내용</h2>';
		$body .= '</div>';
		$body .= '<div class="content" style="padding-left: 2%">';
		$body .= '<h3>';
		$body .= '희망 창업 지역 : '.$location.'<br>';
		$body .= '이름 : '.$name.'<br>';
		$body .= '연락처 : '.$phone.'<br>';
		$body .= '문의사항 : '.$question.'<br>';
		$body .= '개인정보 수집 및 마케팅 활용동의 여부 : 동의함';
		$body .= '</h3>';
		$body .= '</div>';
		$body .= '</div>';
		$body .= '</body>';
		$body .= '</html>';

        // 메일 전송
        
		
		if (
			$location != '-' &&
			$name != '-' &&
			$phone != '-'
		) {
			// 메일 내용
			$mail -> isHTML(true); // HTML 태그 사용 여부
			$mail -> Subject = "[놀라바 창업상담신청]".$name."_".$phone;  // 메일 제목
			$mail -> Body = $body;

			// Gmail로 메일을 발송하기 위해서는 CA인증이 필요하다.
			// CA 인증을 받지 못한 경우에는 아래 설정하여 인증체크를 해지하여야 한다.
			$mail -> SMTPOptions = array(
				"ssl" => array(
					"verify_peer" => false,
					"verify_peer_name" => true,
					"allow_self_signed" => true
				)
			);
			
			$mail -> send();
			
			$success = true;
		} else {
			$errMsg = '전송되는 파라미터를 확인해 주세요.';
		}
		
    } catch (Exception $e) {
		$errMsg = "Message could not be sent. Mailer Error : ";
        $errMsg = $errMsg.$mail -> ErrorInfo;
    }
	
	//echo $body;

	echo json_encode(['success' => $success, 'errMsg' => $errMsg])
	
	
?>