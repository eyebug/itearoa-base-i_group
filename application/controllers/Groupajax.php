<?php

/**
 * @author ZXM
 */
class GroupajaxController extends \BaseController {

    /**
     * @var GroupModel
     */
    private $groupModal;

    /**
     * @var Convertor_Group
     */
    private $groupConvertor;

    public function init() {
        parent::init();
        $this->groupModal = new GroupModel();
        $this->groupConvertor = new Convertor_Group();
    }

    /**
     * 获取用户列表
     */
    public function getUserListAction() {
        $paramList['page'] = $this->getPost('page');
        $paramList['id'] = intval($this->getPost('id'));
        $paramList['groupid'] = intval($this->getGroupId());
        $paramList['username'] = $this->getPost('username');
        $status = $this->getPost('status');
        $status !== 'all' && !is_null($status) ? $paramList['status'] = intval($status) : false;
        $result = $this->groupModal->getUserList($paramList);
        $result = $this->groupConvertor->userListConvertor($result);
        $this->echoJson($result);
    }

    /**
     * 新建和编辑集团信息数据
     */
    private function handlerUserSaveParams() {
        $paramList = array();
        $paramList['username'] = trim($this->getPost("username"));
        $paramList['realname'] = trim($this->getPost("realname"));
        $paramList['password'] = trim($this->getPost("password"));
        $paramList['remark'] = trim($this->getPost("remark"));
        $paramList['status'] = intval($this->getPost("status"));
        $paramList['groupid'] = intval($this->getGroupId());
        return $paramList;
    }

    /**
     * 新建集团信息
     */
    public function createUserAction() {
        $paramList = $this->handlerUserSaveParams();
        $paramList['createadmin'] = $this->userInfo['id'];
        $result = $this->groupModal->saveUserDataInfo($paramList);
        $this->echoJson($result);
    }

    /**
     * 更新集团信息
     */
    public function updateUserAction() {
        $paramList = $this->handlerUserSaveParams();
        $paramList['id'] = intval($this->getPost("id"));
        $result = $this->groupModal->saveUserDataInfo($paramList);
        $this->echoJson($result);
    }
}
