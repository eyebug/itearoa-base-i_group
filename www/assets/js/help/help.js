var iGroup = iGroup || {};
iGroup.helpList = (function ($, ypGlobal) {

    var ajax = YP.ajax, tips = YP.alert, newsList = new YP.list, newsForm = new YP.form;
    var searchButton = $("#searchBtn");

    /**
     * 初始化列表
     */
    function initList() {
        $("#type").select2({
            placeholder: '全部',
            language: 'zh-CN'
        });
        newsList.init({
            colCount: 9,
            autoLoad: true,
            listUrl: ypGlobal.listUrl,
            listDomObject: $("#dataList"),
            searchButtonDomObject: searchButton,
            listTemplate: 'dataList_tpl',
            listSuccess: function (data) {
                newsList.writeListData(data);
            },
            listFail: function (data) {
                tips.show('数据加载失败！');
            }
        });
    }

    /**
     * 初始化编辑新增
     */
    function initEditor() {
        // 初始化表单保存
        var detailModal = $("#editor");
        $("#edit_typeid").select2({
            placeholder: '全部',
            language: 'zh-CN',
            width: 210
        });
        newsForm.init({
            editorDom: $("#listEditor"),
            saveButtonDom: $("#saveListData"),
            checkParams: eval(ypGlobal.checkParams),
            modelDom: detailModal,
            saveBefore: function (saveParams) {
                newsForm.updateParams({
                    saveUrl: saveParams.id > 0 ? ypGlobal.updateUrl : ypGlobal.createUrl
                });
                saveParams = newsForm.makeRecord(saveParams, saveParams.id, saveParams.title);
                return saveParams;
            },
            saveSuccess: function (data) {
                newsList.reLoadList();
            },
            saveFail: function (data) {
                tips.show(data.msg);
            }
        });
        // 新建产品
        $("#createData").on('click', function () {
            $("#ossfile").html("");
            newsForm.writeEditor({
                editorDom: $("#listEditor")
            });
        });
        // 编辑产品
        $("#dataList").on('click', 'button[op=editDataOne]', function () {
            var $editId = $(this).data('dataid'), $dataDom = $("#dataList").find("[dataId=" + $editId + "]");
            var dataList = {};
            $dataDom.find('td').each(function (key, value) {
                var dataOne = $(value);
                if (dataOne.attr('type')) {
                    dataList[dataOne.attr('type')] = dataOne.data('value');
                }
            });
            newsForm.writeEditor({
                editorDom: $("#listEditor"),
                writeData: dataList
            });
            $("#ossfile").html("");
            detailModal.modal('show');
        });
    }

    function initArticleEditor() {
        $("#dataList").on('click', 'button[op=editArticle]', function () {
            window.open('/article/editor?dataid=' + $(this).data('dataid') + '&datatype=' + $(this).data('type') + '&article=' + $(this).data('article'));
        });
    }

    function init() {
        initList();
        initEditor();
        initArticleEditor();
    }

    return {
        init: init
    };
})(jQuery, YP_GLOBAL_VARS);

$(function () {
    iGroup.helpList.init();
});
