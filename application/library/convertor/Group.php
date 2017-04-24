<?php

class Convertor_Group extends Convertor_Base {

    public function userListConvertor($list) {
        $data = array(
            'code' => intval($list['code']),
            'msg' => $list['msg']
        );
        if (isset($list['code']) && !$list['code']) {
            $result = $list['data'];
            $tmp = array();
            foreach ($result['list'] as $key => $value) {
                $dataTemp = array();
                $dataTemp['id'] = $value['id'];
                $dataTemp['userName'] = $value['userName'];
                $dataTemp['realName'] = $value['realName'];
                $dataTemp['remark'] = $value['remark'];
                $dataTemp['status'] = $value['status'];
                $dataTemp['groupId'] = $value['groupId'];
                $dataTemp['groupName'] = $value['groupName'];
                $dataTemp['statusShow'] = $value['status'] ? Enum_Lang::getPageText('group', 'enable') : Enum_Lang::getPageText('group', 'disable');
                $dataTemp['createTime'] = $value['createTime'] ? date('Y-m-d H:i:s', $value['createTime']) : '';
                $dataTemp['lastLoginTime'] = $value['lastLoginTime'] ? date('Y-m-d H:i:s', $value['lastLoginTime']) : '';
                $dataTemp['lastLoginIp'] = $value['lastLoginIp'] ? Util_Tools::ntoip($value['lastLoginIp']) : '';
                $tmp[] = $dataTemp;
            }
            $data['data']['list'] = $tmp;
            $data['data']['pageData']['page'] = intval($result['page']);
            $data['data']['pageData']['rowNum'] = intval($result['total']);
            $data['data']['pageData']['pageNum'] = ceil($result['total'] / $result['limit']);
        }
        return $data;
    }
}

?>