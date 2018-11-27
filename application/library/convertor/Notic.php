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
                $dataTemp['titleLang1'] = $value['title_lang1'];
                $dataTemp['titleLang2'] = $value['title_lang2'];
                $dataTemp['titleLang3'] = $value['title_lang3'];
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
                $dataTemp['titleLang1'] = $value['title_lang1'];
                $dataTemp['titleLang2'] = $value['title_lang2'];
                $dataTemp['titleLang3'] = $value['title_lang3'];
                $dataTemp['articleLang1'] = $value['article_lang1'];
                $dataTemp['articleLang2'] = $value['article_lang2'];
                $dataTemp['articleLang3'] = $value['article_lang3'];
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

                $dataTemp['linkLang1'] = $value['link_lang1'];
                $dataTemp['linkLang2'] = $value['link_lang2'];
                $dataTemp['linkLang3'] = $value['link_lang3'];
                $dataTemp['enableLang1'] = $value['enable_lang1'];
                $dataTemp['enableLang2'] = $value['enable_lang2'];
                $dataTemp['enableLang3'] = $value['enable_lang3'];

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