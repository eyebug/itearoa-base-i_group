<?php

/**
 * APP管理
 */
class AppModel extends \BaseModel {

    /**
     * 获取启动消息列表
     */
    public function getStartMsgList($paramList) {
        do {
            $paramList['id'] ? $params['id'] = $paramList['id'] : false;
            $paramList['type'] ? $params['type'] = $paramList['type'] : false;
            if ($paramList['type']) {
                $paramList['dataid'] ? $params['dataid'] = $paramList['dataid'] : false;
            }
            isset($paramList['status']) ? $params['status'] = $paramList['status'] : false;
            $this->setPageParam($params, $paramList['page'], $paramList['limit'], 15);
            $result = $this->rpcClient->getResultRaw('APP001', $params);
        } while (false);
        return (array)$result;
    }

    /**
     * 新建和编辑启动消息数据
     */
    public function saveStartMsgDataInfo($paramList) {
        $params = $this->initParam();
        do {
            $result = array(
                'code' => 1,
                'msg' => '参数错误'
            );

            $paramList['id'] ? $params['id'] = $paramList['id'] : false;
            $paramList['type'] ? $params['type'] = $paramList['type'] : false;
            $paramList['dataid'] ? $params['dataid'] = $paramList['dataid'] : false;
            $paramList['msg'] ? $params['msg'] = $paramList['msg'] : false;
            $paramList['url'] ? $params['url'] = $paramList['url'] : false;
            !is_null($paramList['status']) ? $params['status'] = $paramList['status'] : false;

            $checkParams = Enum_App::getStartMsgMustInput();
            foreach ($checkParams as $checkParamOne) {
                if (empty($params[$checkParamOne])) {
                    break 2;
                }
            }
            if (empty($params['dataid'])) {
                break;
            }

            if ($paramList['pic']) {
                $uploadResult = $this->uploadFile($paramList['pic'], Enum_Oss::OSS_PATH_IMAGE);
                if ($uploadResult['code']) {
                    $result['msg'] = '展示图上传失败:' . $uploadResult['msg'];
                    break;
                }
                $params['pic'] = $uploadResult['data']['picKey'];
            }
            $interfaceId = $params['id'] ? 'APP003' : 'APP002';
            $result = $this->rpcClient->getResultRaw($interfaceId, $params);
        } while (false);
        return $result;
    }

    /**
     * 获取集团推送列表
     */
    public function getPushList($paramList) {
        do {
            $paramList['id'] ? $params['id'] = $paramList['id'] : false;
            $paramList['type'] ? $params['type'] = $paramList['type'] : false;
            $paramList['dataid'] ? $params['dataid'] = $paramList['dataid'] : false;
            isset($paramList['result']) ? $params['result'] = $paramList['result'] : false;
            isset($paramList['platform']) ? $params['platform'] = $paramList['platform'] : false;
            $this->setPageParam($params, $paramList['page'], $paramList['limit'], 15);
            $result = $this->rpcClient->getResultRaw('APP004', $params);
        } while (false);
        return (array)$result;
    }

    /**
     * 新建集团推送
     */
    public function createPush($paramList) {
        $params = $this->initParam();
        do {
            $result = array(
                'code' => 1,
                'msg' => '参数错误'
            );

            $paramList['platform'] ? $params['platform'] = $paramList['platform'] : false;
            $paramList['type'] ? $params['type'] = $paramList['type'] : false;
            $paramList['dataid'] ? $params['dataid'] = $paramList['dataid'] : false;
            $paramList['cn_title'] ? $params['cn_title'] = $paramList['cn_title'] : false;
            $paramList['cn_value'] ? $params['cn_value'] = $paramList['cn_value'] : false;
            $paramList['en_title'] ? $params['en_title'] = $paramList['en_title'] : false;
            $paramList['en_value'] ? $params['en_value'] = $paramList['en_value'] : false;
            $paramList['url'] ? $params['url'] = $paramList['url'] : false;

            $checkParams = Enum_App::getPushMustInput();
            foreach ($checkParams as $checkParamOne) {
                if (empty($params[$checkParamOne])) {
                    break 2;
                }
            }
            $result = $this->rpcClient->getResultRaw('APP005', $params);
        } while (false);
        return $result;
    }

    /**
     * 获取广告图列表
     */
    public function getStartPicList($paramList) {
        do {
            $paramList['id'] ? $params['id'] = $paramList['id'] : false;
            $params['groupid'] = $paramList['groupid'];
            isset($paramList['status']) ? $params['status'] = $paramList['status'] : false;
            $this->setPageParam($params, $paramList['page'], $paramList['limit'], 15);
            $result = $this->rpcClient->getResultRaw('APP006', $params);
        } while (false);
        return (array)$result;
    }

    /**
     * 新建和编辑启动广告图数据
     */
    public function saveStartPicDataInfo($paramList) {
        $params = $this->initParam();
        do {
            $result = array(
                'code' => 1,
                'msg' => '参数错误'
            );

            $paramList['id'] ? $params['id'] = $paramList['id'] : false;
            $paramList['link'] ? $params['link'] = $paramList['link'] : false;
            $paramList['pic'] ? $params['pic'] = $paramList['pic'] : false;
            $params['groupid'] = $paramList['groupid'];
            !is_null($paramList['status']) ? $params['status'] = $paramList['status'] : false;

            $checkParams = Enum_App::getStartPicMustInput();
            foreach ($checkParams as $checkParamOne) {
                if (empty($params[$checkParamOne])) {
                    break 2;
                }
            }
            unset($params['pic']);
            if ($paramList['pic']) {
                $uploadResult = $this->uploadFile($paramList['pic'], Enum_Oss::OSS_PATH_IMAGE);
                if ($uploadResult['code']) {
                    $result['msg'] = '启动图上传失败:' . $uploadResult['msg'];
                    break;
                }
                $params['pic'] = $uploadResult['data']['picKey'];
            }
            $interfaceId = $params['id'] ? 'APP008' : 'APP007';
            $result = $this->rpcClient->getResultRaw($interfaceId, $params);
        } while (false);
        return $result;
    }

    /**
     * 获取版本列表
     */
    public function getVersionList($paramList) {
        do {
            $paramList['id'] ? $params['id'] = $paramList['id'] : false;
            $paramList['platform'] ? $params['platform'] = $paramList['platform'] : false;
            isset($paramList['forced']) ? $params['forced'] = $paramList['forced'] : false;
            isset($paramList['latest']) ? $params['latest'] = $paramList['latest'] : false;
            $params['groupid'] = $paramList['groupid'];
            $this->setPageParam($params, $paramList['page'], $paramList['limit'], 15);
            $result = $this->rpcClient->getResultRaw('APP009', $params);
        } while (false);
        return (array)$result;
    }

    /**
     * 新建和编辑版本数据
     */
    public function saveVersionDataInfo($paramList) {
        $params = $this->initParam();
        do {
            $result = array(
                'code' => 1,
                'msg' => '参数错误'
            );

            $paramList['id'] ? $params['id'] = $paramList['id'] : false;
            $paramList['platform'] ? $params['platform'] = $paramList['platform'] : false;
            !is_null($paramList['forced']) ? $params['forced'] = $paramList['forced'] : false;
            !is_null($paramList['latest']) ? $params['latest'] = $paramList['latest'] : false;
            $paramList['version'] ? $params['version'] = $paramList['version'] : false;
            $paramList['description'] ? $params['description'] = $paramList['description'] : false;
            $params['groupid'] = $paramList['groupid'];

            $checkParams = Enum_App::getVersionMustInput();
            foreach ($checkParams as $checkParamOne) {
                if (empty($params[$checkParamOne])) {
                    break 2;
                }
            }
            $interfaceId = $params['id'] ? 'APP011' : 'APP010';
            $result = $this->rpcClient->getResultRaw($interfaceId, $params);
        } while (false);
        return $result;
    }

    /**
     * 获取启动图列表
     */
    public function getAppImgList($paramList) {
        do {
            $paramList['id'] ? $params['id'] = $paramList['id'] : false;
            isset($paramList['status']) ? $params['status'] = $paramList['status'] : false;
            $params['groupid'] = $paramList['groupid'];
            $this->setPageParam($params, $paramList['page'], $paramList['limit'], 15);
            $result = $this->rpcClient->getResultRaw('APP012', $params);
        } while (false);
        return (array)$result;
    }

    /**
     * 新建和编辑启动图数据
     */
    public function saveAppImgDataInfo($paramList) {
        $params = $this->initParam();
        do {
            $result = array(
                'code' => 1,
                'msg' => '参数错误'
            );

            $paramList['id'] ? $params['id'] = $paramList['id'] : false;
            $paramList['pickey'] ? $params['pickey'] = $paramList['pickey'] : false;
            !is_null($paramList['status']) ? $params['status'] = $paramList['status'] : false;
            $params['groupid'] = $paramList['groupid'];

            $checkParams = Enum_App::getAppImgMustInput();
            foreach ($checkParams as $checkParamOne) {
                if (empty($params[$checkParamOne])) {
                    break 2;
                }
            }
            if (empty($paramList['id']) && empty($params['pickey'])) {
                break;
            }

            unset($params['pickey']);
            if ($paramList['pickey']) {
                $uploadResult = $this->uploadFile($paramList['pickey'], Enum_Oss::OSS_PATH_IMAGE);
                if ($uploadResult['code']) {
                    $result['msg'] = '启动图上传失败:' . $uploadResult['msg'];
                    break;
                }
                $params['pickey'] = $uploadResult['data']['picKey'];
            }
            $interfaceId = $params['id'] ? 'APP014' : 'APP013';
            $result = $this->rpcClient->getResultRaw($interfaceId, $params);
        } while (false);
        return $result;
    }

    /**
     * 获取问题反馈列表
     */
    public function getFeedbackList($paramList) {
        do {
            $paramList['id'] ? $params['id'] = $paramList['id'] : false;
            $paramList['email'] ? $params['email'] = $paramList['email'] : false;
            $params['groupid'] = $paramList['groupid'];
            $this->setPageParam($params, $paramList['page'], $paramList['limit'], 15);
            $result = $this->rpcClient->getResultRaw('APP015', $params);
        } while (false);
        return (array)$result;
    }
}
