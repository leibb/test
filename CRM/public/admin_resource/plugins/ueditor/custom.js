UE.registerUI('fetchRemoteImage', function(editor, uiName) {
    //创建一个button
    var btn = new UE.ui.Button({
        //按钮的名字
        name: uiName,
        //提示
        title: "抓取远程图片",
        //添加额外样式，指定icon图标，这里默认使用一个重复的icon
        cssRules: 'background-position: -660px -40px;',
        //点击时执行的命令
        onclick: function() {
            editor.fireEvent("catchRemoteImage");
        }
    });

    //因为你是添加button,所以需要返回这个button
    return btn;
});