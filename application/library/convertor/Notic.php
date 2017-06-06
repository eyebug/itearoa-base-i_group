<?php

/**
 * 物业通知数据转换器
 */
class Convertor_Notic extends Convertor_Base {

    /**
     * 标签列表转换器
     * @param array $list
     * @return array
     */
    public function tagListConvertor($list) {
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
                $dataTemp['title'] = $value['title'];
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
     * 列表转换器
     * @param array $list
     * @return array
     */
    public function getListConvertor($list) {
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
                $dataTemp['title'] = $value['title'];
                $dataTemp['article'] = $value['article'];
                $dataTemp['status'] = $value['status'];
                $dataTemp['statusShow'] = $value['status'] ? Enum_Lang::getPageText('news', 'enable') : Enum_Lang::getPageText('news', 'disable');
                $dataTemp['tagid'] = $value['tagId'];
                $dataTemp['tagShow'] = $value['tagName'];
                $dataTemp['createtime'] = $value['createTime'] ? date('Y-m-d H:i:s', $value['createTime']) : '';
                $dataTemp['updatetime'] = $value['updateTime'] ? date('Y-m-d H:i:s', $value['updateTime']) : '';
                $dataTemp['sort'] = $value['sort'];
                $dataTemp['pdf'] = $value['pdf'] ? Enum_Img::getPathByKeyAndType($value['pdf']) : '';
                $dataTemp['videoShow'] = $value['video'] ? Enum_Img::getPathByKeyAndType($value['video']) : '';
                $dataTemp['video'] = $value['video'];
                $dataTemp['pic'] = Enum_Img::getPathByKeyAndType($value['pic']);
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