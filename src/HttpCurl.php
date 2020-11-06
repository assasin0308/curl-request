<?php

/**
 * @notes:
 * @author: assasin <assasin0308@sina.com>
 * @dateTime: 2020/11/5 15:20
 */
class HttpCurl{
    private $cookiefiles = '';
    private $gzip = false;
    private $userAgent = '';
    private $referer = '';
    private $timeout = 30;
    private $connectionTimeout = 10;
    private $headers = [];
    private $params = [];
    private $proxy = [];
    private static $instance;

    /**
     * @notes: 单例模式创建实例
     * @return HttpCurl
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:09
     */
    public static function getInstance(){
        if(!self::$instance instanceof self){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @notes: 设置是否开启cookie
     * @param $cookiefiles
     * @return $this
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:11
     */
    public function enableCookie($cookiefiles){
        $this->cookiefiles = $cookiefiles;
        return $this;
    }

    /**
     * @notes: 启用 & 禁用 gzip 压缩
     * @param $gzip
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:12
     */
    public function enableGzip($gzip){
        $this->gzip = $gzip;
        return $this;
    }

    /**
     * @notes: 设置请求来源，用于判断是从哪个网页URL获得点击当前请求中的网址/URL
     * @param $referer
     * @return $this
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:15
     */
    public function setReferer($referer){
        $this->referer = $referer;
        return $this;
    }

    /**
     * @notes: 设置User-Agent
     * @param $userAgent
     * @return $this
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:16
     */
    public function setUserAgent($userAgent){
        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * @notes: 设置执行的超时时间
     * @param $timeout
     * @return $this
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:18
     */
    public function setTimeout($timeout){
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @notes: 设置连接的最大超时时间
     * @param $connectionTimeout
     * @return $this
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:19
     */
    public function connectionTimeout($connectionTimeout){
        $this->connectionTimeout = $connectionTimeout;
        return $this;
    }

    /**
     * @notes: 设置请求头信息
     * @param $headers
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:20
     */
    public function setHeaders($headers){
        if(!is_array($headers)){
            return $this;
        }
        foreach ($headers as $k => $v){
            if(!is_numeric($k)){
                $headers[$k] = $k. ' : '.$v;
            }
        }
        $this->headers = array_values($headers);
        return $this;
    }

    /**
     * @notes: 设置请求参数
     * @param $params
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:23
     */
    public function setParams($params){
        $this->params = $params;
        return $this;
    }

    /**
     * @notes: 设置请求代理
     * @param $proxy
     * @param $proxy_port
     * @param string $proxyUserPassword
     * @param int $proxy_type
     * @param int $proxy_auth
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:25
     */
    public function setProxy($proxy,$proxy_port,$proxyUserPassword = '',$proxy_type = CURLPROXY_HTTP, $proxy_auth = CURLAUTH_BASIC){
        $this->proxy = [
            'PROXY' =>  $proxy,
            'PROXYPORT' => $proxy_port,
            'PROXYUSERPWD' => $proxyUserPassword,
            'PROXYTYPE' => $proxy_type,
            'PROXYAUTH' => $proxy_auth
        ];
        return $this;
    }

    /**
     * @notes: Http GET请求
     * @param $request_url
     * @return mixed
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:27
     */
    public function get($request_url){
        return $this->request('get',$request_url);
    }

    /**
     * @notes: Http POST 请求
     * @param $request_url
     * @return mixed
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:28
     */
    public function  post($request_url){
        return $this->request('post',$request_url);
    }

    /**
     * @notes: Http PUT请求
     * @param $request_url
     * @return mixed
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:29
     */
    public function put($request_url){
        return $this->request('put',$request_url);
    }

    /**
     * @notes: Http DELETE请求
     * @param $request_url
     * @return mixed
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:30
     */
    public function delete($request_url){
        return $this->request('delete',$request_url);
    }

    /**
     * @notes: Request
     * @param $request_method
     * @param $request_url
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:31
     */
    public function request($request_method,$request_url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $request_url);
        if (stripos($request_url, "https://") !== false) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            //curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        if ($this->cookiefiles) {
            $cookie_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'requesturl-cookie';
            if (!is_dir($cookie_path)) {
                mkdir($cookie_path);
            }
            $cookieFile = $cookie_path . DIRECTORY_SEPARATOR . $this->cookiefiles;
            curl_setopt($curl, CURLOPT_COOKIEJAR, $cookieFile);
            curl_setopt($curl, CURLOPT_COOKIEFILE, $cookieFile);
        }
        if ($this->gzip) {
            $this->headers[] = 'Accept-Encoding: gzip, deflate';
            curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
        }
        if ($this->userAgent) {
            curl_setopt($curl, CURLOPT_USERAGENT, $this->userAgent);
        }
        if ($this->referer) {
            curl_setopt($curl, CURLOPT_REFERER, $this->referer);
        }
        if ($this->timeout) {
            curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        }
        if ($this->connectionTimeout) {
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->connectionTimeout);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);
        switch ($request_method) {
            case 'get':
                curl_setopt($curl, CURLOPT_HTTPGET, true);
                curl_setopt($curl, CURLOPT_URL, $request_url . ($this->params ? '?' . http_build_query($this->params) : ''));
                break;
            case 'post':
                curl_setopt($curl, CURLOPT_POST, true);
                if ($this->params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
                }
                break;
            case 'put':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($this->params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
                }
                break;
            case 'delete':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                if ($this->params) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->params);
                }
                break;
        }
        foreach ($this->proxy as $key => $value) {
            if (!$value) {
                continue;
            }
            curl_setopt($curl, constant('CURLOPT_' . $key), $value);
        }
        $response = curl_exec($curl);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header = false;
        $body = false;
        if (is_numeric($headerSize)) {
            $header = substr($response, 0, $headerSize);
            $body = substr($response, $headerSize);
        }
        $info = array(
            'url' => curl_getinfo($curl, CURLINFO_EFFECTIVE_URL),
            'httpCode' => curl_getinfo($curl, CURLINFO_HTTP_CODE),
            'contentType' => curl_getinfo($curl, CURLINFO_CONTENT_TYPE),
            'contentTypeDownload' => curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD),
            'sizeDownload' => curl_getinfo($curl, CURLINFO_SIZE_DOWNLOAD),
            'errno' => curl_errno($curl),
            'error' => curl_error($curl),
            'proxy' => $this->proxy ? true : false
        );
        curl_close($curl);
        $this->reset();
        return ['header' => $header, 'body' => $body, 'info' => $info];
    }

    /**
     * @notes: 解析header头信息
     * @param $header
     * @return array
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:42
     */
    public function parseHeaders($header){
        if(!$header) return [];
        $headers = str_replace("\r\n",$header);
        $headers = preg_replace("/\n[ \t]/",' ',$header);
        $headers = explode("\n",$headers);
        array_shift($headers);
        $result = [];
        foreach($headers as $header){
            if(! $headers || strpos($header,':') === false){
                continue;
            }
            list($key,$value) = explode(':',$header,2);
            $value = trim($value);
            preg_replace('#(\s+)#i',' ',$value);
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * @notes: 请求重置
     * @author: assasin <assasin0308@sina.com>
     * @dateTime: 2020/11/5 19:35
     */
    public function reset() {
        $this->cookieFile = '';
        $this->gzip = false;
        $this->userAgent = '';
        $this->referer = '';
        $this->timeout = 10;
        $this->connectionTimeout = 5;
        $this->headers = [];
        $this->params = [];
        $this->proxy = [];
    }




}