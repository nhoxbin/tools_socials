<?php

function check_proxy($proxy) {
	$proxy_info = explode(':', $proxy);
	$ip = $proxy_info[0];
	$port = $proxy_info[1];

	$ch = curl_init('http://api.proxyipchecker.com/pchk.php');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, 'ip='.$ip.'&port='.$port);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 6);
	$result['exe'] = curl_exec($ch);
	$result['err'] = curl_error($ch);
	curl_close($ch);

	$data = explode(';', $result['exe']);
	if (!empty($result['err']) || $data[0] == 0) {
		return ['type' => 'fail', 'error' => "Proxy die: $proxy"];
	}

	return ['type' => 'success', 'proxy' => $proxy, 'response_time' => $data[0]];
}