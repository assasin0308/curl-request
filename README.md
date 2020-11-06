# Curl-request
Send your self-defined HTTP request to somewhere,and you do not care anything else.

### How to install ? 

```shell
composer require assasin/curl-request
```

```
{
    "require": {
        "assasin/curl-request": "^1.0"
    }
}
```

### What happened with this library ?

```json
Curl-request is a PHP-based Http library.
If you want to send a GET,POST,PUT,DELETE Http request,and enble cooke,enable gzip,set user-Agent,set referer,set header,set proxy and so on ...... all of these,all of fit 100%.
```

### How to use use it ? 

```php
// Complex request
$result = RequestUrl::getInstance()
        ->enableCookie('testCookiefile.tmp')
        ->enableGzip(true)
        ->setUserAgent('Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36')
        ->setReferer('https://www.baidu.com')
        ->setTimeout(10)
        ->setConnectTimeout(5)
        ->setHeaders(['Accept' => 'application/json'])
        ->setParams(['name' => 'admin', 'password' => '123456'])
        ->setProxy('127.0.0.1', 1080)
        ->post('https://www.baidu.com/user/login');

// Simple request
$result = RequestUrl::getInstance()->setParams(['key' => 'value'])->post('https://www.baidu.com');
$result = RequestUrl::getInstance()->get('https://www.example.com');

var_dump($result['body']);
// string '[...]'... (length=1270)

var_export($result['header']);
/*
'HTTP/2 200 
cache-control: max-age=604800
content-type: text/html; charset=UTF-8
date: Tue, 13 Aug 2019 10:26:21 GMT
etag: "1541025663+ident"
expires: Tue, 20 Aug 2019 10:26:21 GMT
last-modified: Fri, 09 Aug 2013 23:54:35 GMT
server: ECS (dcb/7F38)
vary: Accept-Encoding
x-cache: HIT
content-length: 1270
'
*/

$headers = RequestUrl::getInstance()->parseHeader($result['header']);
var_export($headers);
/*
[
  'cache-control' => 'max-age=604800',
  'content-type' => 'text/html; charset=UTF-8',
  'date' => 'Tue, 13 Aug 2019 10:26:21 GMT',
  'etag' => '"1541025663+ident"',
  'expires' => 'Tue, 20 Aug 2019 10:26:21 GMT',
  'last-modified' => 'Fri, 09 Aug 2013 23:54:35 GMT',
  'server' => 'ECS (dcb/7F38)',
  'vary' => 'Accept-Encoding',
  'x-cache' => 'HIT',
  'content-length' => '1270',
]
*/

var_export($result['info']);
/*
[
  'url' => 'https://www.example.com',
  'httpCode' => 200,
  'contentType' => 'text/html; charset=UTF-8',
  'contentTypeDownload' => 1270.0,
  'sizeDownload' => 1270.0,
  'errno' => 0,
  'error' => '',
  'proxy' => false,
]
*/
```

