<?php

/**
 * 新闻管理控制器
 */
class NewsController extends BaseController
{

    public function init()
    {
        parent::init();
        $languageList = array(Enum_Lang::LANG_KEY_CHINESE, Enum_Lang::LANG_KEY_ENGLISH);
        $this->_view->assign('languageList', $languageList);
        $this->_view->assign('langIndex', Enum_Lang::getSystemLang(true));
    }

    /**
     * 标签列表
     */
    public function tagListAction()
    {
        $this->_view->display('news/tag.phtml');
    }

    /**
     * 新闻列表
     */
    public function listAction()
    {
        $newsModel = new NewsModel();
        $tagList = $newsModel->getTagList(array('groupid' => $this->getGroupId()), 3600);
        $this->_view->assign('tagList', $tagList['data']['list']);
        $this->setAllowUploadFileType(Enum_Oss::OSS_PATH_PDF, 'allowTypePdf');
        $this->setAllowUploadFileType(Enum_Oss::OSS_PATH_IMAGE, 'allowTypeImage');
        $this->_view->display('news/news.phtml');
    }
}