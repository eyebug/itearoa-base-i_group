<?php

/**
 * 登录请求控制器
 */
class LoginajaxController extends \BaseController {

    /**
     * 处理登录
     */
    public function doLoginAction() {
        $request = $this->getRequest();
        $paramList['username'] = $request->getPost('username');
        $paramList['password'] = $request->getPost('password');

        $model = new LoginModel();
        $baseConvertor = new Convertor_Base();
        $result = $model->doLogin($paramList);
        $result = $baseConvertor->commonConvertor($result, 'login');
        $this->echoJson($result);
    }

    /**
     * 修改密码
     */
    public function changePassAction() {
        $paramList['userId'] = $this->userInfo['id'];
        $paramList['oldPass'] = $this->getPost('oldPass');
        $paramList['newPass'] = $this->getPost('newPass');

        $loginModel = new LoginModel();
        $result = $loginModel->changePass($paramList);
        $this->echoJson($result);
    }

    /**
     * 修改使用语言
     */
    public function changeLangugaeAction() {
        $result = array('code' => 1);
        $language = $this->getPost('language');
        $setResult = Enum_Lang::setSystemLang($language);
        if ($setResult) {
            $result = array('code' => 0);
        }
        $baseConvertor = new Convertor_Base();
        $result = $baseConvertor->commonConvertor($result, 'changeLanguage');
        $this->echoJson($result);
    }
}
