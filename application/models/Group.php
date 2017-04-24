<?php

class GroupModel extends \BaseModel {

    public function getUserList($paramList, $cacheTime = 0) {
        do {
            if ($cacheTime == 0) {
                $paramList['id'] ? $params['id'] = $paramList['id'] : false;
                $paramList['groupid'] ? $params['groupid'] = intval($paramList['groupid']) : false;
                $paramList['username'] ? $params['username'] = $paramList['username'] : false;
                isset($paramList['status']) ? $params['status'] = $paramList['status'] : false;
                $this->setPageParam($params, $paramList['page'], $paramList['limit'], 15);
            } else {
                $params['limit'] = 0;
            }
            $isCache = $cacheTime != 0 ? true : false;
            $result = $this->rpcClient->getResultRaw('AU004', $params, $isCache, $cacheTime);
        } while (false);
        return (array)$result;
    }

    public function saveUserDataInfo($paramList) {
        $params = $this->initParam($paramList);
        do {
            $checkParams = Enum_Group::getGroupUserMustInput();
            $result = array(
                'code' => 1,
                'msg' => '参数错误'
            );
            foreach ($checkParams as $checkParamOne) {
                if (empty($params[$checkParamOne])) {
                    break 2;
                }
            }
            if (empty($params['id']) && empty($params['password'])) {
                $result['msg'] = '新建密码不能为空';
                break;
            }
            if ($params['password']) {
                $params['password'] = Enum_Login::getMd5Pass($params['password']);
            } else {
                unset($params['password']);
            }
            $interfaceId = $params['id'] ? 'AU006' : 'AU005';
            $result = $this->rpcClient->getResultRaw($interfaceId, $params);
            if (!$result['code']) {
                $this->getUserList(array(), -2);
            }
        } while (false);
        return $result;
    }
}
