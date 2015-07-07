<?php

$fields = array('email' => 'test@tess.tt', 'password' => '123qwe');
$postvars = http_build_query($fields);
$url = 'http://ptest/auth';

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
    if (!empty($error = curl_error($ch))) {
        echo "Curl error: $error";
    }
    curl_close($ch);

    return [substr($response, 0, $headerSize), substr($response, $headerSize)];
}

function makeAuth($nonce) {
    $cnonce = md5(uniqid('auth' . rand(1, 999), true));
    $time = time() + 60;
    $auth = ['cnonce' => $cnonce, 'time' => $time, 'hash' => hash('sha1', $cnonce . $time . $nonce)];
    return $auth;
}

list($header, $bodyRaw) = request($url, 'post', null, $postvars, true);

echo $header, "\n", $bodyRaw, "\n";

$body = json_decode($bodyRaw, true);
if (empty($body['error'])) {
    $data = $body['data'];
    $nonce = $body['nonce'];
    echo 'session nonce: ', $nonce, "\n";
} else {
    var_dump($body);
}
//##################################################################################
//put
$fields = [
    'Book-Title' => 'The Da Vinci Code',
    'Book-Author' => 'Dan Brown',
    'Year-Of-Publication' => '20030',
    'Publisher' => 'Doubleday',
    'Image-URL-S' => 'http://images.amazon.com/images/P/0385504209.01.THUMBZZZ.jpg',
    'Image-URL-M' => 'http://images.amazon.com/images/P/0385504209.01.MZZZZZZZ.jpg',
    'Image-URL-L' => 'http://images.amazon.com/images/P/0385504209.01.LZZZZZZZ.jpg',
];

$postvars = http_build_query($fields);
$url = 'http://ptest/api/bxbook';

list($header, $bodyRaw) = request($url, 'put', makeAuth($body['nonce']), $postvars);
echo $header, "\n", $bodyRaw, "\n";
$body = json_decode($bodyRaw, true);

if (empty($body['error'])) {
    $data = $body['data'];
    $lastBookId = $data[0];
    var_dump($data);
} else {
    var_dump($body);
}
//get
$url = 'http://ptest/api/bxbook/' . $lastBookId;
echo "$url\n\n";

list($header, $bodyRaw) = request($url, 'get', makeAuth($body['nonce']));
echo $header, "\n", $bodyRaw, "\n";
$body = json_decode($bodyRaw, true);

if (empty($body['error'])) {
    $data = $body['data'];
    var_dump($data);
} else {
    var_dump($body);
}

//delete
$url = 'http://ptest/api/bxuser/999';

list($header, $bodyRaw) = request($url, 'delete', makeAuth($body['nonce']));
echo $header, "\n", $bodyRaw, "\n";
$body = json_decode($bodyRaw, true);

if (empty($body['error'])) {
    $data = $body['data'];
    var_dump($data);
} else {
    var_dump($body);
}

//post
$nonce = $body['nonce'];
$cnonce = md5(uniqid('auth' . rand(1, 999), true));
$time = time() + 60;
$auth = ['cnonce' => $cnonce, 'time' => $time, 'hash' => hash('sha1', $cnonce . $time . $nonce)];

$fields = ['ISBN' => '0385504209',
    'Book-Title' => 'The Da Vinci Code',
    'Book-Author' => 'Dan Brown',
    'Year-Of-Publication' => '20030',
    'Publisher' => 'Doubleday',
    'Image-URL-S' => 'http://images.amazon.com/images/P/0385504209.01.THUMBZZZ.jpg',
    'Image-URL-M' => 'http://images.amazon.com/images/P/0385504209.01.MZZZZZZZ.jpg',
    'Image-URL-L' => 'http://images.amazon.com/images/P/0385504209.01.LZZZZZZZ.jpg',
];

$postvars = http_build_query($fields);

$url = 'http://ptest/api/bxbook';

list($header, $bodyRaw) = request($url, 'post', $auth, $postvars);
echo $header, "\n", $bodyRaw, "\n";
$body = json_decode($bodyRaw, true);

if (empty($body['error'])) {
    $data = $body['data'];
    var_dump($data);
} else {
    var_dump($body);
}


exit;
