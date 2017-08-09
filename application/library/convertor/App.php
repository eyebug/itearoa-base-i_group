<?php

/**
 * APP数据转换器
 */
class Convertor_App extends Convertor_Base {

    /**
     * 启动消息列表
     */
    public function startMsgListConvertor($list) {
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
                $dataTemp['pic'] = Enum_Img::getPathByKeyAndType($value['pic']);
                $dataTemp['status'] = $value['status'];
                $dataTemp['statusShow'] = $value['status'] ? Enum_Lang::getPageText('startMsg', 'enable') : Enum_Lang::getPageText('startMsg', 'disable');
                $dataTemp['msg'] = $value['msg'];
                $dataTemp['url'] = $value['url'];
                $tmp[] = $dataTemp;
            }
            $data['data']['list'] = $tmp;
            $data['data']['pageData']['page'] = intval($result['page']);
            $data['data']['pageData']['rowNum'] = intval($result['total']);
            $data['data']['pageData']['pageNum'] = ceil($result['total'] / $result['limit']);
        }
        return $data;
    }

    /**
     * 集团推送列表
     */
    public function pushListConvertor($list) {
        $data = array(
            'code' => intval($list['code']),
            'msg' => $list['msg']
        );
        if (isset($list['code']) && !$list['code']) {
            $result = $list['data'];
            $tmp = array();
            $baseModel = new BaseModel();
            $platformList = $baseModel->getPlatformList();
            foreach ($result['list'] as $key => $value) {
                $dataTemp = array();
                $dataTemp['id'] = $value['id'];
                $dataTemp['type'] = $value['type'];
                $dataTemp['dataid'] = $value['dataid'];
                $dataTemp['cn_title'] = $value['cn_title'];
                $dataTemp['en_title'] = $value['en_title'];
                $dataTemp['url'] = $value['url'];
                $dataTemp['result'] = $value['result'];
                $dataTemp['resultShow'] = $value['result'] ? Enum_Lang::getPageText('push', 'resultFail') : Enum_Lang::getPageText('push', 'resultSuccess');
                $dataTemp['platform'] = $value['platform'];
                $dataTemp['platformShow'] = $platformList[$dataTemp['platform']];
                $dataTemp['createtime'] = $value['createtime'] ? date('Y-m-d H:i:s', $value['createtime']) : '';
                $tmp[] = $dataTemp;
            }
            $data['data']['list'] = $tmp;
            $data['data']['pageData']['page'] = intval($result['page']);
            $data['data']['pageData']['rowNum'] = intval($result['total']);
            $data['data']['pageData']['pageNum'] = ceil($result['total'] / $result['limit']);
        }
        return $data;
    }

    /**
     * 启动广告图
     * @param $list
     * @return array
     */
    public function startPicListConvertor($list) {
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
                $dataTemp['pic'] = Enum_Img::getPathByKeyAndType($value['pic']);
                $dataTemp['status'] = $value['status'];
                $dataTemp['statusShow'] = $value['status'] ? '启用' : '禁用';
                $dataTemp['link'] = $value['link'];
                $tmp[] = $dataTemp;
            }
            $data['data']['list'] = $tmp;
            $data['data']['pageData']['page'] = intval($result['page']);
            $data['data']['pageData']['rowNum'] = intval($result['total']);
            $data['data']['pageData']['pageNum'] = ceil($result['total'] / $result['limit']);
        }
        return $data;
    }

    /**
     * 版本管理
     * @param $list
     * @return array
     */
    public function versionListConvertor($list) {
        $data = array(
            'code' => intval($list['code']),
            'msg' => $list['msg']
        );
        if (isset($list['code']) && !$list['code']) {
            $baseModel = new BaseModel();
            $platformList = $baseModel->getPlatformList();

            $result = $list['data'];
            $tmp = array();
            foreach ($result['list'] as $key => $value) {
                $dataTemp = array();
                $dataTemp['id'] = $value['id'];
                $dataTemp['platform'] = $value['platform'];
                $dataTemp['platformShow'] = $platformList[$dataTemp['platform']];
                $dataTemp['forced'] = $value['forced'];
                $dataTemp['forcedShow'] = $value['forced'] ? '是' : '否';
                $dataTemp['version'] = $value['version'];
                $dataTemp['description'] = $value['description'];
                $dataTemp['createtime'] = $value['createtime'] ? date('Y-m-d H:i:s', $value['createtime']) : '';
                $dataTemp['latest'] = $value['latest'];
                $dataTemp['latestShow'] = $value['latest'] ? '是' : '否';
                $tmp[] = $dataTemp;
            }
            $data['data']['list'] = $tmp;
            $data['data']['pageData']['page'] = intval($result['page']);
            $data['data']['pageData']['rowNum'] = intval($result['total']);
            $data['data']['pageData']['pageNum'] = ceil($result['total'] / $result['limit']);
        }
        return $data;
    }

    /**
     * APP启动图
     * @param $list
     * @return array
     */
    public function appImgListConvertor($list) {
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
                $dataTemp['pickey'] = Enum_Img::getPathByKeyAndType($value['pickey']);
                $dataTemp['status'] = $value['status'];
                $dataTemp['statusShow'] = $value['status'] ? '启用' : '禁用';
                $dataTemp['createtime'] = $value['createtime'] ? date('Y-m-d H:i:s', $value['createtime']) : '';
                $tmp[] = $dataTemp;
            }
            $data['data']['list'] = $tmp;
            $data['data']['pageData']['page'] = intval($result['page']);
            $data['data']['pageData']['rowNum'] = intval($result['total']);
            $data['data']['pageData']['pageNum'] = ceil($result['total'] / $result['limit']);
        }
        return $data;
    }

    /**
     * 问题反馈列表
     * @param $list
     * @return array
     */
    public function feedbackListConvertor($list) {
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
                $dataTemp['email'] = $value['email'];
                $dataTemp['content'] = $value['content'];
                $dataTemp['createtime'] = $value['createtime'] ? date('Y-m-d H:i:s', $value['createtime']) : '';
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