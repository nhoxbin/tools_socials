<?php

function upanh($filename) {
	// Client id: f9274a775d445bc
	// Client secret: aa9145ecae98dae391c9bdf9df9b6ab335b7e2dc
	// access_token: 14fd3b81d912a5f8595cff29691350ea69c07bb2
	// refresh_token: be0a461989eeb6122caa2298ce7bc3102b208cfb
	$curl = curl_init();
	$client_id = 'f9274a775d445bc';

	$handle = fopen($filename, 'r');
	$data = fread($handle, filesize($filename));
	$pvars = array('image' => base64_encode($data));
	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.imgur.com/3/image.json',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $pvars,
		CURLOPT_HTTPHEADER => ['Authorization: Client-ID ' . $client_id],
		// CURLOPT_SSL_VERIFYPEER => true,
	));

	$response = curl_exec($curl);
	curl_close($curl);
	return json_decode($response, true)['data']['link'];
}