<?php /*a:3:{s:61:"/www/wwwroot/0768.cc/application/admin/view/company/form.html";i:1552607912;s:60:"/www/wwwroot/0768.cc/application/admin/view/common/head.html";i:1545903998;s:60:"/www/wwwroot/0768.cc/application/admin/view/common/foot.html";i:1545903998;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo config('sys_name'); ?>后台管理</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/static/plugins/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="/static/admin/css/global.css" media="all">
    <link rel="stylesheet" href="/static/common/css/font.css" media="all">
</head>
<body class="skin-<?php if(!empty($_COOKIE['skin'])){echo $_COOKIE['skin'];}else{echo '0';setcookie('skin','0');}?>">
<div class="admin-main layui-anim layui-anim-upbit" ng-app="hd" ng-controller="ctrl">
    <fieldset class="layui-elem-field layui-field-title">
        <legend><?php echo htmlentities($title); ?></legend>
    </fieldset>
    <form class="layui-form layui-form-pane">
        <div class="layui-form-item">
            <label class="layui-form-label">txt文件</label>
            <input type="hidden" name="companys" id="companys">
            <div class="layui-input-block">
                <div class="layui-upload">
                    <button type="button" class="layui-btn layui-btn-primary" id="companysBtn"><i class="icon icon-upload3"></i>点击上传</button>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button type="button" class="layui-btn" lay-submit="" lay-filter="submit"><?php echo lang('submit'); ?></button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="/static/plugins/layui/layui.js"></script>


<script src="/static/common/js/angular.min.js"></script>
<script>
    var m = angular.module('hd',[]);
    m.controller('ctrl',['$scope',function($scope) {
        layui.use(['form','upload'], function () {
            var form = layui.form,upload = layui.upload,$= layui.jquery;
            //普通上传
            var uploadInst = upload.render({
                elem: '#companysBtn'
                ,accept:'file'
                ,url: '<?php echo url("UpFiles/file"); ?>'
                ,done: function(res){
                    //上传成功
                    if(res.code>0){
                        $('#companys').val(res.url);
                    }else{
                        //如果上传失败
                        return layer.msg('上传失败');
                    }
                }
            });
            form.on('submit(submit)', function (data) {
                loading =layer.load(1, {shade: [0.1,'#fff']});
                // 提交到方法 默认为本身
                //data.field.id = $scope.field.id;
                $.post("add", data.field, function (res) {
                    layer.close(loading);
                    if (res.code > 0) {
                        layer.msg(res.msg, {time: 1000, icon: 1}, function () {
                            layer.closeAll("iframe");
                            //刷新父页面
                            parent.location.reload();
                        });
                    } else {
                        layer.msg(res.msg, {time: 1000, icon: 2});
                    }
                });
            })
        });
    }]);
</script>