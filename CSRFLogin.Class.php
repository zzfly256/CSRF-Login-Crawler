<?php
/**
 * Class CSRFLogin
 * @author Rytia
 * @uses PHP 爬虫，模拟登录各种需要 CSRF 验证的系统
 * @copyright Github (zzfly256/CSRFLogin) all rights reserved.
 */
Class CSRFLogin
{
    protected $loginPageUrl;
    protected $loginTargetUrl;
    protected $contentUrl;
    protected $refererUrl;

    protected $result;

    /**
     * cUrl 函数封装
     * @param $url
     * @param string $post
     * @param string $cookie
     * @param int $returnCookie
     * @return mixed|string
     */
    public function curlRequest($url, $post = '', $cookie = '', $returnCookie = 1)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) 	Chrome/64.0.3282.186 Safari/537.36');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, $this->refererUrl);
        if (!empty($post)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if (!empty($cookie)) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($curl, CURLOPT_ENCODING,'gzip');
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:'));
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if ($returnCookie) {
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            @$info['cookie'] = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        } else {
            return $data;
        }
    }

    /**
     * 通过请求获取内容
     * @param $user
     * @param $password
     * @return string
     */
    public function post($user, $password)
    {
        // 第一次请求，获得 csrf 信息: token + session
        $result[0] = $this->curlRequest($this->loginPageUrl);
        
        // 取出 csrf token
        $pattern = '/<meta name="__hash__" content="(.*?)" \/>/is';
        preg_match_all($pattern, $result[0]['content'], $matches);

        // 整理第二次请求的内容
        $post["username"] = $user;
        $post["userpassword"] = $password;
        $post["__hash__"] = $matches[1][0];

        // 第二次请求，将当前 session 验证
        $result[1] = $this->curlRequest($this->loginTargetUrl, $post, $result[0]['cookie']);

        // 第三次请求，访问成绩页面，获得结果
        $result[2] = $this->curlRequest($this->contentUrl, null, $result[0]['cookie']);
        $this->retult = $result;
        return $result[2]['content'];
    }

    /**
     * CSRFLogin constructor.
     * @param $loginPageUrl
     * @param $loginTargetUrl
     * @param $contentUrl
     * @param string $refererUrl
     */
    public function __construct($loginPageUrl, $loginTargetUrl, $contentUrl, $refererUrl = '')
    {
        $this->loginPageUrl = $loginPageUrl;
        $this->loginTargetUrl = $loginTargetUrl;
        $this->contentUrl = $contentUrl;
        $this->targetUrl =$this->refererUrl;
        $this->refererUrl = $refererUrl;
    }
}
