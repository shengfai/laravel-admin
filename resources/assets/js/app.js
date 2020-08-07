
// 当前资源URL目录
var baseRoot = (function () {
    var scripts = document.scripts, src = scripts[scripts.length - 1].src;
    return src.substring(0, src.lastIndexOf("/") - 2);
})();

// 配置参数
require.config({
    waitSeconds: 30,
    baseUrl: baseRoot,
    map: {"*": {css: baseRoot + "plugs/require/require.css.js"}},
    paths: {
        "template": ["plugs/template/template"],
        "pcasunzips": ["plugs/jquery/pcasunzips"],
        // openSource
        "json": ["/admin/plugs/jquery/json2.min"],
        "layui": ["plugs/layui/layui"],
        "base64": ["plugs/jquery/base64.min"],
        "angular": ["plugs/angular/angular.min"],
        "ckeditor": ["plugs/ckeditor/ckeditor"],
        "websocket": ["plugs/socket/websocket"],
        "clipboard": ["plugs/clipboard/clipboard.min"],
        // jQuery
        "jquery.ztree": ["plugs/ztree/jquery.ztree.all.min"],
        "jquery.masonry": ["plugs/jquery/masonry.min"],
        "jquery.cookies": ["plugs/jquery/jquery.cookie"],
        // bootstrap
        "bootstrap": ["/admin/plugs/bootstrap/js/bootstrap.min"],
        "bootstrap.typeahead": ["plugs/bootstrap/js/bootstrap3-typeahead.min"],
        "bootstrap.multiselect": ["plugs/bootstrap-multiselect/bootstrap-multiselect"],
        // distpicker
        "distpicker": ["plugs/distpicker/distpicker"],
        "select2": ["plugs/select2/select2.min"],
        //markdown
        jquery          : "/admin/plugs/jquery/jquery.min",
        marked          : "marked.min",
        prettify        : "prettify.min",                            
        raphael         : "raphael.min",
        underscore      : "underscore.min",
        flowchart       : "flowchart.min", 
        jqueryflowchart : "jquery.flowchart.min", 
        sequenceDiagram : "sequence-diagram.min",
        katex           : "katex.min",
        editormd        : "../editormd.amd" // Using Editor.md amd version for Require.js
    },
    shim: {
        // open-source
        "websocket": {deps: [baseRoot + "plugs/socket/swfobject.min.js"]},
        // jquery
        "jquery.ztree": {deps: ["css!" + baseRoot + "plugs/ztree/zTreeStyle/zTreeStyle.css"]},
        // bootstrap
        "bootstrap.typeahead": {deps: ["bootstrap"]},
        "bootstrap.multiselect": {deps: ["bootstrap", "css!" + baseRoot + "plugs/bootstrap-multiselect/bootstrap-multiselect.css"]},
        "distpicker": {deps: [baseRoot + "plugs/distpicker/distpicker.data.js"]}
    },
    deps: ["json", "bootstrap"],
    // 开启debug模式，不缓存资源
    // urlArgs: "ver=" + (new Date()).getTime()
});

//引入新的插件
layui.config({
    base: "./plugs/"	//拓展模块的根目录
}).extend({
    pca: "pca"
});

// 注册jquery到require模块
define("jquery", function () {
    return layui.$;
});

// UI框架初始化
PageLayout.call(this);

// UI框架布局函数
function PageLayout(callback, custom) {
    window.WEB_SOCKET_SWF_LOCATION = baseRoot + "plugs/socket/WebSocketMain.swf";
    require(custom || [], callback || false);
}