<?php

namespace nastradamus39\tewloger;

/**
 * Very simple http client to send very simple post requests
 * with Basic http authorization
 * Class WebBot
 * @package nastradamus39
 */
class WebClient
{

    /** @var string $URL */
    private $URL;

    /** @var string $KEY */
    private $KEY;

    /** @var string $TOKEN */
    private $TOKEN;

    const METHOD_GET = "GET";

    const METHOD_POST = "POST";

    /**
     * WebBot constructor.
     * @throws \Exception
     */
    public function __construct($params)
    {
        $this->KEY = !empty($params['key']) ? $params['key'] : null;
        $this->TOKEN = !empty($params['token']) ? $params['token'] : null;
        $this->URL = !empty($params['url']) ? $params['url'] : null;

        $this->URL = trim($this->URL,"\\");

        if ($this->KEY === null || $this->TOKEN === null || $this->URL === null) {
            throw new \Exception(self::class.'. Mandatory property not set');
        }
    }

    /**
     * Generate string for request headers with HTTP Basic authorization
     * @return string
     */
    private function headers(){
        $header = "Content-Type: application/json\r\n".
            "Authorization: Basic {$this->TOKEN}\r\n";
        return $header;
    }

    /**
     * @param $params
     * @return mixed|string
     */
    public function send($method, $url, $params)
    {
        if($method === self::METHOD_GET)
            return $this->_get($url, $params);

        if($method === self::METHOD_POST)
            return $this->_post($url, $params);

    }

    /**
     * Send POST request
     * @param $url
     * @param $params
     * @return mixed
     */
    public function _post($url, $params){
        $opts = ['http' => [
            'method'  => self::METHOD_POST,
            'header'  => $this->headers(),
            'content' => json_encode($params)
        ]];

        $context  = stream_context_create($opts);
        $resp = file_get_contents($this->URL."/".$url, false, $context);
        return json_decode($resp);
    }

    /**
     * Send GET request
     * @param $url
     * @param $params
     * @return null
     */
    public function _get($url, $params){
        /**
         * TODO
         */
        return null;
    }

}