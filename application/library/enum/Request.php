<?php

/**
 * 用户枚举
 * @author ZXM
 * 2015年7月13日
 */
class Enum_Request {

    const RPC_REQUEST_UA = "Iservice/1.0(group;)";

    public static function getUrlConfigById($interfaceId) {
        $config = array(
            'B' => 'Rpc_UrlConfigBase',
            'AU' => 'Rpc_UrlConfigAdmin',
            'GH' => 'Rpc_UrlConfigHotel',
            'GA' => 'Rpc_UrlConfigActivity',
            'APP' => 'Rpc_UrlConfigApp',
            'NT' => 'Rpc_UrlConfigNews',
            'N' => 'Rpc_UrlConfigNotic',
            'H' => 'Rpc_UrlConfigHelp',
            'SF' => 'Rpc_UrlConfigStaff',
        );
        $fileKey = preg_replace('/\d+/', '', $interfaceId);
        $fileNameKey = $config[$fileKey];
        if (empty($fileNameKey)) {
            return false;
        }
        $interfaceConfig = $fileNameKey::getConfig($interfaceId);
        if ($interfaceConfig) {
            return $interfaceConfig;
        } else {
            return false;
        }
    }
}
