<?php

$url = 'http://ptest/api/bxbookrating/ranking/?country=usa&limit=5&offset=1';
if (1) {
    $url .='&XDEBUG_SESSION_START=netbeans-xdebug';
}

function request($url, $method, $auth = null, $postvars = null, $newSession = false) {
    $cookie = 'cookie.txt';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    if ($auth) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: ' . json_encode($auth)]);
    }
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    if ($newSession) {
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    }
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_TIMEOUT, 600);
    $response = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

    curl_close($ch);

    return [substr($response, 0, $headerSize), substr($response, $headerSize)];
}

list($header, $bodyRaw) = request($url, 'get', null, null, true);

echo $header, "\n", $bodyRaw, "\n";

$body = json_decode($bodyRaw, true);
if (empty($body['error'])) {
    $data = $body['data'];
    $nonce = $body['nonce'];
}
var_dump($body);
