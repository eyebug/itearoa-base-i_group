<?php

class RecordModel extends BaseModel {

    public function addOperateLog($paramList) {
        $params = $this->initParam(array());
        do {
            $params['operatorid'] = $paramList['adminId'];
            $params['msg'] = $paramList['log'];
            $params['dataid'] = $paramList['dataId'];
            $params['module'] = intval($paramList['module']);
            $params['action'] = intval($paramList['action']);
            $params['ip'] = Util_Http::getIP();
            $params['miscinfo'] = $paramList['otherInfo'];
            $params['code'] = $paramList['code'];
            $params['admintype'] = Enum_Record::RECORD_GROUP_TYPE_ID;
            $params['admintypeid'] = $paramList['groupId'];
            if (empty($params['operatorid'])) {
                $result = array(
                    'code' => 1,
                    'msg' => 'ID不能为空'
                );
                break;
            }
            $result = $this->rpcClient->getResultRaw('AU002', $params);
        } while (false);
        return (array) $result;
    }
}