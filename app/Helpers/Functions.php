<?php

function destroySpecialChars($str) {
	if (!$str || $str == '') {
		return; // null
	}
	$str = htmlspecialchars(addslashes(htmlentities($str, ENT_QUOTES)));

	// kiểm tra nếu có các kí tự đặc biệt thì out
	if (preg_match('/[%||&||(||)||=||+||<||>||?||`||\/||;]/i', $str)) {
		return true;
	}

	return $str;
}

function BinJSON($types, $data = []) {
	// Đặt tên hàm là tên tui luôn =))
	/*$arr = [
		'success', 'success-alert', 'success-inject', 'success-redirect', 'success-alert-redirect',
		'error', 'error-alert', 'error-inject', 'error-redirect', 'error-alert-redirect'
	];*/
	if (is_array($types)) {
		$type = $types[0];
		$redirect = $types[1];
	}

	$params;
	if (!empty($data)) {
		// type will return if types là array
		$params = [
			'type' => $type ?? $types,
			'data' => $data
		];
		if (isset($redirect)) {
			$params['redirect'] = $redirect;
		}
	} else {
		$params = [
			'type' => false,
			'data' => ['message' => 'data can not be null']
		];
	}
	return $params;
}

function validateDate($date, $format = 'd.m.Y H:i') {
	$d = DateTime::createFromFormat($format, $date);
	return $d && $d->format($format) == $date;
}

function stripUnicode($str) {
	if(!$str) return false;
	$unicode = array(
		'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
		'd'=>'đ',
		'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
		'i'=>'í|ì|ỉ|ĩ|ị',
		'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
		'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
		'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
		'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
		'D'=>'Đ',
		'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
		'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
		'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
		'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
		'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
		'' => ' ',
	);
	foreach($unicode as $khongdau => $codau) {
		$str = preg_replace("/($codau)/i", $khongdau, $str);
	}
	return strtolower($str);
}

function random($type, $length = 5) {
	if ($type == 'email') {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen($characters) - 1;
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength)];
		}
		return $randomString;
	} elseif($type == 'string') {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters) - 1;
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength)];
		}
		return $randomString;
	} elseif ($type == 'number') {
		$randomNumber = substr(str_shuffle(str_repeat('0123456789', 3)), 0, $length);
		return $randomNumber;
	} elseif ($type == 'phone') {
		$length = 7;
		$nhamang = ['086', '096', '097', '098', '0162', '0163', '0164', '0165', '0166', '0167', '0168', '0169', '090', '093', '0120', '0121', '0122', '0126', '0128', '091', '094', '0123', '0124', '0125', '0127', '0129'];
		$randomNhamang = $nhamang[mt_rand(0, count($nhamang) - 1)];
		$phone = $randomNhamang . substr(str_shuffle(str_repeat('0123456789', 3)), 0, $length);
		return $phone;
	}
}

function arr_sort($array, $on, $order=SORT_ASC) {
	$new_array = array();
	$sortable_array = array();

	if (count($array) > 0) {
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				foreach ($v as $k2 => $v2) {
					if ($k2 == $on) {
						$sortable_array[$k] = $v2;
					}
				}
			} else {
				$sortable_array[$k] = $v;
			}
		}

		switch ($order) {
			case SORT_ASC:
				asort($sortable_array);
				break;
			case SORT_DESC:
				arsort($sortable_array);
				break;
		}

		foreach ($sortable_array as $k => $v) {
			$new_array[$k] = $array[$k];
		}
	}

	return $new_array;
}

function array_search_multidim($array, $column, $search_value) {
    return array_search($search_value, array_column($array, $column));
}

function mkurl($is_ssl, $host, $path = null, array $fields) {
	$url = ($is_ssl ? 'https://' : 'http://') . $host . '/v4.0/' . $path;
	if (!empty($fields)) {
		$url .= '?' . http_build_query($fields);
	}
	return $url;
}

function agent() {
	$userAgents = array(
		'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0; chromeframe/11.0.696.57)',
		'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/4.0; GTB7.4; InfoPath.3; SV1; .NET CLR 3.1.76908; WOW64; en-US)',
		'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 7.1; Trident/5.0)',
		'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; Media Center PC 6.0; InfoPath.3; MS-RTC LM 8; Zune 4.7)',
		'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; Media Center PC 6.0; InfoPath.3; MS-RTC LM 8; Zune 4.7',
		'Mozilla/5.0 (Windows NT 6.2; rv:21.0) Gecko/20130326 Firefox/21.0',
		'Mozilla/5.0 (Windows; U; MSIE 9.0; WIndows NT 9.0; en-US))',
		'Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)',
		'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20130401 Firefox/21.0',
		'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20130331 Firefox/21.0',
		'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20130330 Firefox/21.0',
		'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20100101 Firefox/21.0',
		'Mozilla/5.0 (Windows NT 6.1; rv:21.0) Gecko/20130401 Firefox/21.0',
		'Mozilla/5.0 (Windows NT 6.1; rv:21.0) Gecko/20130328 Firefox/21.0',
		'Mozilla/5.0 (Windows NT 6.1; rv:21.0) Gecko/20100101 Firefox/21.0',
		'Mozilla/5.0 (Windows NT 6.0) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.36 Safari/536.5',
		'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1063.0 Safari/536.3',
		'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1063.0 Safari/536.3',
		'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1062.0 Safari/536.3',
		'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1062.0 Safari/536.3',
		'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1061.1 Safari/536.3',
		'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1061.1 Safari/536.3',
		'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; ru-ru) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16',
		'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; ko-kr) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16',
		'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; it-it) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16',
		'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; HTC-P715a; en-ca) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16',
		'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; en-us) AppleWebKit/534.1+ (KHTML, like Gecko) Version/5.0 Safari/533.16',
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_0) AppleWebKit/536.3 (KHTML, like Gecko) Chrome/19.0.1063.0 Safari/536.3',
		'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; en-au) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16',
		'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.96 Mobile Safari/537.36',
		'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.9 Safari/536.5',
		'Opera/9.80 (X11; Linux i686; U; en-GB) Presto/2.2.15 Version/10.00',
		'Opera/9.80 (X11; Linux i686; U; en) Presto/2.2.15 Version/10.00',
		'Opera/9.80 (X11; Linux i686; U; Debian; pl) Presto/2.2.15 Version/10.00',
		'Opera/9.80 (X11; Linux i686; U; de) Presto/2.2.15 Version/10.00',
		'Opera/9.80 (Windows NT 6.1; U; zh-cn) Presto/2.2.15 Version/10.00',
		'Opera/9.80 (Windows NT 6.1; U; fi) Presto/2.2.15 Version/10.00',
		'Opera/9.80 (Windows NT 6.1; U; en) Presto/2.2.15 Version/10.00',
		'Opera/9.80 (Android; Opera Mini/7.6.40234/37.7148; U; id) Presto/2.12.423 Version/12.16',
	);

	$rand = rand(0, count($userAgents) - 1);
	return $userAgents[$rand];
}