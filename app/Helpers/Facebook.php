<?php

function sign_creator($username, $password) {
	// app_id: 6628568379 // fb for iphone
	$data = array(
		// 'api_key' => '882a8490361da98702bf97a021ddc14d', // fb for android
		'api_key' => '3e7c78e35a76a9299309885393b02d97', // fb for iphone
		'email' => $username,
		'format' => 'JSON',
		'generate_machine_id' => '0',
		'generate_session_cookies' => '1',
		'locale' => 'vi_vn',
		'method' => 'auth.login',
		'password' => $password,
		// 'return_ssl_resources' => '0',
		'v' => '1.0',
	);

	$sig = "";
	foreach ($data as $key => $value) {
		$sig .= "$key=$value";
	}

	$sig .= 'c1e620fa708a1d5696fb991c1bde5662';
	// $sig .= '62f8ce9f74b12f84c123cc23437a4a32';
	$sig = md5($sig);
	$data['sig'] = $sig;

	return file_get_contents('https://api.facebook.com/restserver.php?' . http_build_query($data));
}

function convert_cookie($session_cookies) {
	$cookie = '';
	for ($i = 0; $i < 2; $i++) {
		$cookie .= $session_cookies[$i]['name'] . '=' . $session_cookies[$i]['value'] . ';';
	}
	return $cookie;
}

function HandleLogin($array) {
	if (!isset($array['error_data'])) {
		return false;
	}
	// has error
	$code = $array['error_code'];
	$data = json_decode($array['error_data'], true);

	$message;
	switch ($code) {
		case 400: case 401:
			// #401 "Invalid username or password"
			$message = $data['error_message'];
			break;
		default:
			$message = 'Có lỗi xảy ra! Vui lòng liên hệ BQT để sửa lỗi. Xin cảm ơn!';
			break;
	}
	return $message;
}

function CheckAndHandleFBErrCode($arr) {
	if (!isset($arr['error']) && !isset($arr['error_code'])) {
		return false;
	}
	if (isset($arr['error'])) {
		$arr = $arr['error'];
	}
	$message = '';
	$err_code = isset($arr['error_code']) ? $arr['error_code'] : $arr['code'];
	if (isset($arr['error_code'])) {
		$err_code = $arr['error_code'];
		$err_data = json_decode($arr['error_data'], true);
	} else {
		$err_code = $arr['code'];
	}
	// error_code
	switch ($err_code) {
		case 190:
			// (error_subcode = 452) Error validating access token: The session has been invalidated because the user changed their password or Facebook has changed the session for security reasons.
			$message = $arr['message'] . ' Bạn vui lòng đăng nhập lại facebook vào hệ thống';
			break;
		case 200:
			$message = $arr['message'] . '. Thiếu access_token!';
			break;
		case 400: case 401:
			// "Invalid username or password (401)"
			$message = $err_data['error_message'] . '. Tài khoản đăng nhập ko đúng, vui lòng đăng nhập lại!';
			break;
		case 405:
			$message = $err_data['error_message'] . ' Account đã bị checkpoint! Hãy đăng nhập vào facebook của bạn!';
			break;
		case 803:
			// (#803) Some of the aliases you requested do not exist: 1000117952606
			$message = $arr['message'] . '. ID ko tồn tại, vui lòng điền đúng ID';
			break;
		default:
			$message = 'Có lỗi xảy ra! Vui lòng liên hệ BQT để sửa lỗi. Xin cảm ơn!';
			break;
	}
	return $message;
}
