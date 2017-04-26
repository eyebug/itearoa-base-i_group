<?php

/**
 * @name Bootstrap
 * @author ryan
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * @see http://www.php.net/manual/en/class.yaf-bootstrap-abstract.php
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
class Bootstrap extends Yaf_Bootstrap_Abstract {

    public function _initConfig() {
        // 把配置保存起来
        $arrConfig = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('sysConfig', $arrConfig);

        $loginInfo = array();
        $sId = Util_Http::getCookie(Enum_Login::LOGIN_INFO_COOKIE_KEY_SID);
        $aId = Util_Http::getCookie(Enum_Login::LOGIN_INFO_COOKIE_KEY_AID);
        if ($sId && $aId) {
            $memKey = Auth_Login::genLoginMemKey($sId, $aId);
            $cacheOb = Cache_Redis::getInstance();
            $tmpJson = $cacheOb->get($memKey);
            if ($tmpJson) {
                $tmp = json_decode($tmpJson, true);
                if (is_array($tmp) && count($tmp) > 0) {
                    $loginInfo = $tmp;
                    $cacheOb->set($memKey, $tmpJson, Enum_Login::LOGIN_TIMEOUT);
                    $cookieTime = time() + Enum_Login::LOGIN_TIMEOUT;
                    Util_Http::setCookie(Enum_Login::LOGIN_INFO_COOKIE_KEY_AID, $aId, $cookieTime);
                    Util_Http::setCookie(Enum_Login::LOGIN_INFO_COOKIE_KEY_SID, $sId, $cookieTime);
                }
            }
        }
        Yaf_Registry::set('loginInfo', $loginInfo);
        Enum_Record::setRecordData('adminId', $loginInfo['id']);
        Enum_Record::setRecordData('groupId', $loginInfo['groupId']);
    }

    public function _initPlugin(Yaf_Dispatcher $dispatcher) {
        // 注册一个插件
        $dispatcher->registerPlugin(new ErrorPlugin());
        $dispatcher->registerPlugin(new LoginPlugin());
        $dispatcher->registerPlugin(new RecordPlugin());
    }

    public function _initRoute(Yaf_Dispatcher $dispatcher) {
        // 在这里注册自己的路由协议,默认使用简单路由
        $dispatcher->getRouter()->addRoute("name", new Yaf_Route_Regex("/^\/(\w+)\/(\w+)$/i", array(
            'controller' => ":name", // 使用上面匹配的:name, 也就是$1作为controller
            'action' => ":action"
        ), array(
            1 => "name", // now you can call $request->getParam("name")
            2 => "action"
        )));
    }

    public function _initMemcache(Yaf_Dispatcher $dispatcher) {
    }

    public function _initSession(Yaf_Dispatcher $dispatcher) {
    }

    public function _initView(Yaf_Dispatcher $dispatcher) {
        // 在这里注册自己的view控制器，例如smarty,firekylin
        Yaf_Dispatcher::getInstance()->autoRender(false);
    }
}
