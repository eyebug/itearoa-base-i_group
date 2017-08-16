<?php

/**
 * APP请求
 */
class AppajaxController extends \BaseController {

    /**
     * @var AppModel
     */
    private $appModel;

    /**
     * @var Convertor_App
     */
    private $appConvertor;

    public function init() {
        parent::init();
        $this->appModel = new AppModel();
        $this->appConvertor = new Convertor_App();
    }

    /**
     * 获取启动消息列表
     */
    public function getStartMsgListAction() {
        $paramList['id'] = intval($this->getPost('id'));
        $paramList['type'] = intval(Enum_App::START_MSG_TYPE_GROUP);
        $paramList['dataid'] = intval($this->getGroupId());
        $status = $this->getPost('status');
        $status !== 'all' && !is_null($status) ? $paramList['status'] = intval($status) : false;
        $result = $this->appModel->getStartMsgList($paramList);
        $result = $this->appConvertor->startMsgListConvertor($result);
        $this->echoJson($result);
    }

    /**
     * 新建和编辑启动消息数据
     */
    private function handlerStartMsgSaveParams() {
        $paramList = array();
        $paramList['type'] = intval(Enum_App::START_MSG_TYPE_GROUP);
        $paramList['dataid'] = intval($this->getGroupId());
        $paramList['pic'] = $_FILES['pic'];
        $paramList['msg'] = trim($this->getPost("msg"));
        $paramList['url'] = trim($this->getPost("url"));
        $paramList['status'] = intval($this->getPost("status"));
        return $paramList;
    }

    /**
     * 新建启动消息
     */
    public function createStartMsgAction() {
        $paramList = $this->handlerStartMsgSaveParams();
        $result = $this->appModel->saveStartMsgDataInfo($paramList);
        $this->echoJson($result);
    }

    /**
     * 更新启动消息
     */
    public function updateStartMsgAction() {
        $paramList = $this->handlerStartMsgSaveParams();
        $paramList['id'] = intval($this->getPost("id"));
        $result = $this->appModel->saveStartMsgDataInfo($paramList);
        $this->echoJson($result);
    }

    /**
     * 获取集团推送列表
     */
    public function getPushListAction() {
        $paramList['id'] = intval($this->getPost('id'));
        $paramList['type'] = Enum_App::PUSH_TYPE_GROUP;
        $paramList['content_type'] = Enum_App::PUSH_CONTENT_TYPE_URL;
        $paramList['dataid'] = $this->getGroupId();
        $result = $this->getPost('result');
        $result !== 'all' && !is_null($result) ? $paramList['result'] = intval($result) : false;
        $platform = $this->getPost('platform');
        $platform !== 'all' && !is_null($platform) ? $paramList['platform'] = intval($platform) : false;
        $result = $this->appModel->getPushList($paramList);
        $result = $this->appConvertor->pushListConvertor($result);
        $this->echoJson($result);
    }

    /**
     * 新建集团推送
     */
    public function createPushAction() {
        $paramList = array();
        $paramList['type'] = Enum_App::PUSH_TYPE_GROUP;
        $paramList['dataid'] = $this->getGroupId();
        $paramList['platform'] = intval($this->getPost("platform"));
        $paramList['cn_title'] = trim($this->getPost("cnTitle"));
        $paramList['cn_value'] = trim($this->getPost("cnValue"));
        $paramList['en_title'] = trim($this->getPost("enTitle"));
        $paramList['en_value'] = trim($this->getPost("enValue"));
        $paramList['url'] = trim($this->getPost("url"));
        $result = $this->appModel->createPush($paramList);
        $this->echoJson($result);
    }


    /**
     * 获取广告图列表
     */
    public function getStartPicListAction() {
        $paramList['id'] = intval($this->getPost('id'));
        $status = $this->getPost('status');
        $status !== 'all' && !is_null($status) ? $paramList['status'] = intval($status) : false;
        $paramList['groupid'] = $this->getGroupId();
        $result = $this->appModel->getStartPicList($paramList);
        $result = $this->appConvertor->startPicListConvertor($result);
        $this->echoJson($result);
    }

    /**
     * 新建和编辑启动广告图数据
     */
    private function handlerStartPicSaveParams() {
        $paramList = array();
        $paramList['link'] = trim($this->getPost("link"));
        $paramList['pic'] = $_FILES['pic'];
        $paramList['status'] = intval($this->getPost("status"));
        $paramList['groupid'] = $this->getGroupId();
        return $paramList;
    }

    /**
     * 新建启动广告图
     */
    public function createStartPicListAction() {
        $paramList = $this->handlerStartPicSaveParams();
        $result = $this->appModel->saveStartPicDataInfo($paramList);
        $this->echoJson($result);
    }

    /**
     * 更新启动广告图
     */
    public function updateStartPicListAction() {
        $paramList = $this->handlerStartPicSaveParams();
        $paramList['id'] = intval($this->getPost("id"));
        $result = $this->appModel->saveStartPicDataInfo($paramList);
        $this->echoJson($result);
    }

    /**
     * 获取版本列表
     */
    public function getVersionListAction() {
        $paramList['id'] = intval($this->getPost('id'));
        $paramList['platform'] = intval($this->getPost('platform'));
        $forced = $this->getPost('forced');
        $forced !== 'all' && !is_null($forced) ? $paramList['forced'] = intval($forced) : false;
        $latest = $this->getPost('latest');
        $latest !== 'all' && !is_null($latest) ? $paramList['latest'] = intval($latest) : false;
        $paramList['groupid'] = $this->getGroupId();
        $result = $this->appModel->getVersionList($paramList);
        $result = $this->appConvertor->versionListConvertor($result);
        $this->echoJson($result);
    }

    /**
     * 新建和编辑版本数据
     */
    private function handlerVersionSaveParams() {
        $paramList = array();
        $paramList['platform'] = intval($this->getPost("platform"));
        $paramList['forced'] = intval($this->getPost("forced"));
        $paramList['latest'] = intval($this->getPost("latest"));
        $paramList['version'] = trim($this->getPost("version"));
        $paramList['description'] = trim($this->getPost("description"));
        $paramList['groupid'] = $this->getGroupId();
        return $paramList;
    }

    /**
     * 新建版本
     */
    public function createVersionAction() {
        $paramList = $this->handlerVersionSaveParams();
        $result = $this->appModel->saveVersionDataInfo($paramList);
        $this->echoJson($result);
    }

    /**
     * 更新版本
     */
    public function updateVersionAction() {
        $paramList = $this->handlerVersionSaveParams();
        $paramList['id'] = intval($this->getPost("id"));
        $result = $this->appModel->saveVersionDataInfo($paramList);
        $this->echoJson($result);
    }

    /**
     * 获取启动图列表
     */
    public function getAppImgListAction() {
        $paramList['id'] = intval($this->getPost('id'));
        $status = $this->getPost('status');
        $status !== 'all' && !is_null($status) ? $paramList['status'] = intval($status) : false;
        $paramList['groupid'] = $this->getGroupId();
        $result = $this->appModel->getAppImgList($paramList);
        $result = $this->appConvertor->appImgListConvertor($result);
        $this->echoJson($result);
    }

    /**
     * 新建和编辑启动图数据
     */
    private function handlerAppImgSaveParams() {
        $paramList = array();
        $paramList['link'] = trim($this->getPost("link"));
        $paramList['pickey'] = $_FILES['pickey'];
        $paramList['status'] = intval($this->getPost("status"));
        $paramList['groupid'] = $this->getGroupId();
        return $paramList;
    }

    /**
     * 新建启动图
     */
    public function createAppImgAction() {
        $paramList = $this->handlerAppImgSaveParams();
        $result = $this->appModel->saveAppImgDataInfo($paramList);
        $this->echoJson($result);
    }

    /**
     * 更新启动图
     */
    public function updateAppImgAction() {
        $paramList = $this->handlerAppImgSaveParams();
        $paramList['id'] = intval($this->getPost("id"));
        $result = $this->appModel->saveAppImgDataInfo($paramList);
        $this->echoJson($result);
    }

    /**
     * 获取问题反馈列表
     */
    public function getFeedbackListAction() {
        $paramList['id'] = intval($this->getPost('id'));
        $paramList['email'] = intval($this->getPost('email'));
        $paramList['groupid'] = $this->getGroupId();
        $result = $this->appModel->getFeedbackList($paramList);
        $result = $this->appConvertor->feedbackListConvertor($result);
        $this->echoJson($result);
    }

    /**
     * 获取关于我们语言版本列表
     */
    public function getAboutListAction() {
        $paramList['id'] = $this->getGroupId();
        $groupModel = new GroupModel();
        $result = $groupModel->getGroupDetail($paramList);
        $result = $this->appConvertor->aboutListConvertor($result);
        $this->echoJson($result);
    }

}
