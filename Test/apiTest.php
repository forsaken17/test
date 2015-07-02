<?php

$fields = array('email' => 'ateamdev@gmail.com', 'password' => '123qwe');
$postvars = http_build_query($fields);
$url = 'http://todo/auth';
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
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    $response = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

    curl_close($ch);

    return [substr($response, 0, $headerSize), substr($response, $headerSize)];
}

list($header, $bodyRaw) = request($url, 'post', null, $postvars, true);

echo $header, "\n", $bodyRaw, "\n";

$body = json_decode($bodyRaw, true);
if (empty($body['error'])) {
    $data = $body['data'];
    $nonce = $data['nonce'];
    echo 'session nonce: ', $nonce, "\n";
} else {
    var_dump($body);
}
//post
$cnonce = md5(uniqid('auth' . rand(1, 999), true));
$time = time() + 60;
$auth = ['cnonce' => $cnonce, 'time' => $time, 'hash' => hash('sha1', $cnonce . $time . $nonce)];
//var_dump($fields);
//$postvars = http_build_query($fields);

$url = 'http://todo/api/test';

list($header, $bodyRaw) = request($url, 'post', $auth);
echo $header, "\n", $bodyRaw, "\n";
$body = json_decode($bodyRaw, true);

if (empty($body['error'])) {
    $data = $body['data'];
    var_dump($data);
} else {
    var_dump($body);
}

//get
$nonce = $data['nonce'];
$cnonce = md5(uniqid('auth' . rand(1, 999), true));
$time = time() + 60;
$auth = ['cnonce' => $cnonce, 'time' => $time, 'hash' => hash('sha1', $cnonce . $time . $nonce)];

$url = 'http://todo/api/test';

list($header, $bodyRaw) = request($url, 'get', $auth);
echo $header, "\n", $bodyRaw, "\n";
$body = json_decode($bodyRaw, true);

if (empty($body['error'])) {
    $data = $body['data'];
    var_dump($data);
} else {
    var_dump($body);
}
//delete
$nonce = $data['nonce'];
$cnonce = md5(uniqid('auth' . rand(1, 999), true));
$time = time() + 60;
$auth = ['cnonce' => $cnonce, 'time' => $time, 'hash' => hash('sha1', $cnonce . $time . $nonce)];

$url = 'http://todo/api/test';

list($header, $bodyRaw) = request($url, 'delete', $auth);
echo $header, "\n", $bodyRaw, "\n";
$body = json_decode($bodyRaw, true);

if (empty($body['error'])) {
    $data = $body['data'];
    var_dump($data);
} else {
    var_dump($body);
}
//put
$nonce = $data['nonce'];
$cnonce = md5(uniqid('auth' . rand(1, 999), true));
$time = time() + 60;
$auth = ['cnonce' => $cnonce, 'time' => $time, 'hash' => hash('sha1', $cnonce . $time . $nonce)];

$url = 'http://todo/api/test';

list($header, $bodyRaw) = request($url, 'put', $auth, http_build_query(['id' => 1]));
echo $header, "\n", $bodyRaw, "\n";
$body = json_decode($bodyRaw, true);

if (empty($body['error'])) {
    $data = $body['data'];
    var_dump($data);
} else {
    var_dump($body);
}

