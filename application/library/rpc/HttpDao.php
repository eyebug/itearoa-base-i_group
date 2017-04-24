<?php

class Rpc_HttpDao {

    private static $instance;

    private function __construct() {
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * 查询
     *
     * @param string $interfaceId
     * @param array $params
     * @param boolean $isJsonDecode
     * @param int $httpTimeout
     * @param boolean $isCache
     * @param int $cacheTime
     *            缓存时间单位是毫秒，如果不需要缓存则为-1,-2是代表不读但是需要写缓存
     * @return Ambigous <Ambigous, boolean, mixed, NULL>
     */
    public function getResultRaw($interfaceId, $params, $isCache = false, $cacheTime = -1, $isJsonDecode = true, $httpTimeout = 10) {
        $interfaceConfig = Enum_Request::getUrlConfigById($interfaceId);
        if (!$interfaceConfig) {
            return array(
                'code' => 1,
                'msg' => '接口信息获取失败'
            );
        }
        $interfaceConfig['httpMethod'] = isset($interfaceConfig['httpMethod']) ? $interfaceConfig['httpMethod'] : 'POST';
        $requestUrl = $this->getRequestUrl($interfaceConfig, $params);
        $result = false;
        if (strlen($requestUrl['url']) > 0) {
            $cacheKey = $this->getCacheKey($requestUrl['url'], $params);
            if ($isCache && $cacheTime > 0 && $cacheKey) {
                $memcache = Cache_Redis::getInstance();
                $result = $memcache->get($cacheKey);
            }
            if (!$result) {
                $imgSize = $params['imgSize'] ? $params['imgSize'] : '';
                $result = $this->getHttpResultRaw($interfaceId, $requestUrl, $isJsonDecode, $httpTimeout, $interfaceConfig['httpMethod'], $imgSize);
                $this->setCache($result, $cacheKey, $isCache, $cacheTime, $isJsonDecode);
            } else {
                $result = empty($isJsonDecode) ? $result : json_decode($result, true);
            }
            return empty($result) ? array(
                'code' => 1,
                'msg' => '查询失败'
            ) : $result;
        }
    }

    private function getCacheKey($url, $params) {
        $params = $params ? $params : array();
        unset($params['timestamp']);
        unset($params['sign']);
        $makeKeyList[] = $url;
        foreach ($params as $key => $param) {
            $makeKeyList[] = $key;
            $makeKeyList[] = (is_array($param) || is_object($param)) ? json_encode($param) : $param;
        }
        return md5(implode("-", $makeKeyList));
    }

    private function setCache($result, $key, $isCache, $cacheTime, $isJsonDecode) {
        if ($isCache && ($cacheTime > 0 || $cacheTime == -2)) {
            $cacheTime = $cacheTime == -2 ? 3600 : $cacheTime;
            $memcache = Cache_Redis::getInstance();
            if (!$isJsonDecode) {
                $result = json_decode($result, true);
            }
            if (is_array($result) && !$result['code']) {
                $memcache->set($key, json_encode($result), $cacheTime);
            }
        }
    }

    /**
     * 并发查询
     *
     * @param string $interfaceId
     * @param array $multiParams
     * @param boolean $isJsonDecode
     * @param int $httpTimeout
     * @return Ambigous <Ambigous, boolean, mixed, unknown>
     */
    public function getResultRawMulti($interfaceId, $multiParams, $isJsonDecode = false, $httpTimeout = 1) {
        // $interfaceConfig = Rpc_UrlConfig::getConfig ( $interfaceId );
        foreach ($multiParams as $params) {
            $interfaceConfig = Rpc_UrlConfig::getConfig($params['interfaceId']);
            $requestUrls[] = $this->getRequestUrl($interfaceConfig, $params['data']);
        }
        if (count($requestUrls) > 0) {
            return $this->getHttpResultRawMulti($interfaceId, $requestUrls, $isJsonDecode, $httpTimeout);
        }
    }

    /**
     * 并发读取接口
     *
     * @param string $interfaceId
     * @param string $requestUrl
     * @param boolean $isJsonDecode
     * @param int $httpTimeout
     * @return Ambigous <mixed, string>|boolean
     */
    public function getHttpResultRawMulti($interfaceId, $requestUrls, $isJsonDecode = false, $httpTimeout = 1) {
        foreach ((array)$requestUrls as $tKey => $requestUrl) {
            $requestUrl = $this->formatUrl($requestUrl);
            if ($this->isValidUrl($requestUrl)) {
                $validRequestUrls[$tKey] = $requestUrl;
            }
        }
        $responseResult = false;
        if (count($validRequestUrls) > 0) {

            $httpTimeout = max(1, (int)$httpTimeout);
            foreach ($validRequestUrls as $urlKey => $requestUrl) {
                Rpc_MultiCurl::add_curl($urlKey, Rpc_MultiCurl::mk_curl('get', $requestUrl, array(), array(
                    CURLOPT_TIMEOUT => $httpTimeout
                )));
            }

            $requestRaw = Rpc_MultiCurl::multi_exec();
            $requestStatus = '0';
            if (count($requestRaw['success']) > 0) {
                $responseRaw = $requestRaw['data'];
                $requestStatus = '200';
            }
            $requestStatus -= (count($responseRaw['error']) > 0) ? 1 : 0;

            if (!empty($responseRaw)) {
                if ($isJsonDecode) {
                    $responseResult = json_decode($responseRaw, true);
                } else {
                    $responseResult = $responseRaw;
                }
            }
        }

        return $responseResult;
    }

    /**
     * 查询操作
     *
     * @param string $interfaceId
     * @param string $requestUrl
     * @param boolean $isJsonDecode
     * @param int $httpTimeout
     *            -1=不执行http访问 >0=http超时时间
     * @return Ambigous <mixed, string>|boolean
     */
    public function getHttpResultRaw($interfaceId, $requestUrl, $isJsonDecode = false, $httpTimeout = 1, $httpMethod = 'GET', $imgSize) {
        $serviceDebug = Util_Tools::getDebugServiceAddress();
        // $serviceDebug = true;
        if ($serviceDebug) {
            $debugUrl = $requestUrl['url'];
            $debugUrl = str_replace("post", 'get', $debugUrl);
            $debugUrl = str_replace("POST", 'get', $debugUrl);
            var_dump($debugUrl . '&' . http_build_query($requestUrl['param']));
        }

        $responseResult = false;
        if ($httpMethod == 'POST') {
            $postData = $requestUrl['param'];
            $requestUrl = $requestUrl['url'];
        } else {
            $requestUrl = $requestUrl['url'] . '&' . http_build_query($requestUrl['param']);
        }
        // var_dump($requestUrl);
        $requestUrl = $this->formatUrl($requestUrl);
        if ($this->isValidUrl($requestUrl)) {
            $responseRaw = null;
            $httpTimeout = max(1, (int)$httpTimeout);
            $httpRet = Rpc_Curl::_request($requestUrl, $httpMethod, $postData, $httpTimeout, $imgSize);
            if ($httpRet['httpStatus']) {
                $responseRaw = $httpRet['response'];
            }
            if (!empty($responseRaw)) {
                if ($isJsonDecode) {
                    if (substr($responseRaw, 0, 3) == pack("CCC", 0xef, 0xbb, 0xbf)) {
                        $responseRaw = substr($responseRaw, 3);
                    }
                    $responseResult = json_decode($responseRaw, true);
                } else {
                    $responseResult = $responseRaw;
                }
            }
        }

        return $responseResult;
    }

    /**
     * 格式化url
     *
     * @param string $url
     * @return string
     */
    private function formatUrl($url) {
        return trim($url);
    }

    /**
     * 判断url有效性
     *
     * @param string $url
     * @return number
     */
    private function isValidUrl($url) {
        return strlen($url);
    }

    /**
     * 获取url
     *
     * @param array $interfaceConfig
     * @param array $params
     * @return boolean|Ambigous <string, unknown>
     */
    private function getRequestUrl($interfaceConfig, $params) {
        $requestUrl = array();
        if ($interfaceConfig) {
            $paramPart = array();
            if ($interfaceConfig['auth']) {
                $interfaceConfig['param'] = $interfaceConfig['param'] ? $interfaceConfig['param'] : array();
                $interfaceConfig['param'] = array_merge($interfaceConfig['param'], $this->getAuthParam());
            }
            foreach ($interfaceConfig['param'] as $paramKey => $paramConfig) {
                $paramValue = $this->formatParamValue($params[$paramKey], $paramConfig);
                if ((isset($params[$paramKey]) && !is_null($paramValue)) || isset($paramConfig['value'])) {
                    $paramValue = (empty($paramValue) && isset($paramConfig['value'])) ? $paramConfig['value'] : $paramValue;
                    $paramPart[$paramKey] = $paramValue;
                } elseif ($paramConfig['required'] == true) {
                    return false;
                }
            }

            $sign = Auth_Login::genSign($paramPart);
            $paramPart['sign'] = $sign;
            $requestUrl['param'] = $paramPart;
            $interfaceConfig['url'] = Enum_System::getServiceApiUrlByLink($interfaceConfig['url']);
            $requestUrl['url'] = $interfaceConfig['url'] . '?';
        }
        return $requestUrl;
    }

    /**
     * 验证参数
     *
     * @return array
     */
    private function getAuthParam() {
        $param = array(
            'time' => array(
                'required' => true,
                'format' => 'string',
                'style' => 'interface',
                'value' => time()
            )
        );
        return $param;
    }

    /**
     * 根据接口定义格式化类型
     *
     * @param string $value
     * @param string $paramConfig
     * @return string|number|unknown
     */
    private function formatParamValue($value, $paramConfig) {
        if ($paramConfig['format'] == 'string') {
            return strval($value);
        } elseif ($paramConfig['format'] == 'int') {
            return intval($value);
        } elseif ($paramConfig['format'] == 'isset') {
            return 1;
        } elseif ($paramConfig['format'] == 'file') {
            if ($value['tmp_name']) {
                return new CURLFile($value['tmp_name'], null, $value['name']);
            }
        } else {
            return $value;
        }
    }
}
