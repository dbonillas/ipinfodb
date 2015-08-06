<?php

namespace IpInfoDb;

use Zend\Http\Client as HttpClient;

class IpInfoDb
{

    /**
     * @const string API base url
     */
    const BASE_URL = 'https://api.ipinfodb.com';

    /**
     * @const string API version
     */
    const API_VERSION = 'v3';

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var HttpAdapterInterface
     */
    protected $httpAdapter;

    /**
     * @param string               $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;

        $this->httpAdapter = new HttpClient();
        $this->httpAdapter->setAdapter('Zend\Http\Client\Adapter\Curl');
    }

    /**
     * @param  string $ip
     * @return Response
     */
    public function country($ip)
    {
        return $this->send('ip-country', $ip);
    }

    /**
     * @param  string $ip
     * @return Response
     */
    public function city($ip)
    {
        return $this->send('ip-city', $ip);
    }

    /**
     * @param  string $endpoint
     * @param  string $ip
     * @return Response
     */
    protected function send($endpoint, $ip)
    {
        $content = array(
            "statusCode" => "OK",
	        "statusMessage" => "no results",
	        "ipAddress" => $ip,
	        "countryCode" => 0,
	        "countryName" => 0
        );
        $params = [
            'ip'     => $ip,
            'key'    => $this->apiKey,
            'format' => 'json'
        ];

        $url  = self::BASE_URL . '/' . self::API_VERSION . '/' . $endpoint;
        $url .= '?' . http_build_query($params);

        $this->httpAdapter->setUri($url);
        $response = $this->httpAdapter->send();

        if ($response->isSuccess()) {
            $content = json_decode($response->getBody(), true);
        }
        return new Response($content);

    }

}
