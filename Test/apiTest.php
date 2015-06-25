<?php

$fields = array('email' => 'ateamdev@gmail.com', 'password' => '123qwe');
$postvars = http_build_query($fields);
$url = 'http://todo/api/test';
if (1) {
    $url .='?XDEBUG_SESSION_START=netbeans-xdebug';
}
$cookie = 'cookie.txt';

function request($url, $postvars, $cookie, $newSession = false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);                //0 for a get request
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    if ($newSession) {
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    }
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $response = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

    curl_close($ch);

    return [substr($response, 0, $headerSize), substr($response, $headerSize)];
}

list($header, $bodyRaw) = request($url, $postvars, $cookie, true);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo $header, "\n", $bodyRaw, "\n";

$body = json_decode($bodyRaw, true);
if (empty($body['error'])) {
    $data = $body['data'];
    $nonce = $_SESSION['nonce'] = $data['nonce'];
    echo 'session nonce: ', $nonce, "\n";
} else {
    var_dump($body);
}
$cnonce = md5(uniqid('auth' . rand(1, 999), true));
$time = time() + 60;
$fields = array('cnonce' => $cnonce, 'time' => $time, 'hash' => hash('sha1', $cnonce . $time . $nonce));
var_dump($fields);
$postvars = http_build_query($fields);
list($header, $bodyRaw) = request($url, $postvars, $cookie);
echo $header, "\n", $bodyRaw, "\n";
$body = json_decode($bodyRaw, true);

if (empty($body['error'])) {
    $data = $body['data'];
    var_dump($data);
} else {
    var_dump($body);
}
