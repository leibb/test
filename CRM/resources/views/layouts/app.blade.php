<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="renderer" content="webkit">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 3.3.4 -->
    <link href="/admin_resource/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- Font Awesome Icons -->
    <link href="/admin_resource/dist/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <!-- Ionicons -->
    <link href="/admin_resource/dist/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="/admin_resource/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css"/>
    <link href="/admin_resource/dist/css/skins/skin-blue.min.css" rel="stylesheet" type="text/css"/>

    <script src="/admin_resource/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="/admin_resource/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>


    <script src="/admin_resource/plugins/underscore-min.js" type="text/javascript"></script>
    <script src="/admin_resource/plugins/layer-v3.1.1/layer.js" type="text/javascript"></script>

    <!-- AdminLTE App -->
    <script src="/admin_resource/dist/js/app.js" type="text/javascript"></script>

    <script src="/admin_resource/plugins/vue.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {
            //子菜单为空则隐藏
            $(".treeview").each(function () {
                if ($(this).find(".treeview-menu li").length > 0) {
                    $(this).show();
                }
            });
            $.prototype.serializeObject = function () {
                var a, o, h, i, e;
                a = this.serializeArray();
                o = {};
                h = o.hasOwnProperty;
                for (i = 0; i < a.length; i++) {
                    e = a[i];
                    if (!h.call(o, e.name)) {
                        o[e.name] = e.value;
                    }
                }
                return o;
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $(document).ajaxError(function (req, err) {
                if (err.status == 403) {
                    layer.msg("无权限访问", {icon: 5});
                }
            });

        });

        function setNav(sec) {
            $("#menu " + sec).addClass('active').parents(".treeview").addClass("active");
        }

        //dataTable 国际化
        var oLanguage = {
            "oAria": {
                "sSortAscending": ": 升序排列",
                "sSortDescending": ": 降序排列"
            },
            "oPaginate": {
                "sFirst": "首页",
                "sLast": "末页",
                "sNext": "下一页",
                "sPrevious": "上一页"
            },
            "sEmptyTable": "没有相关记录",
            "sInfo": "第 _START_ 到 _END_ 条，共 _TOTAL_ 条",
            "sInfoEmpty": "共 0 条",
            "sInfoFiltered": "(从 _MAX_ 条记录中检索)",
            "sInfoPostFix": "",
            "sDecimal": "",
            "sThousands": ",",
            "sLengthMenu": "每页显示条数: _MENU_",
            "sLoadingRecords": "正在载入...",
            "sProcessing": "正在载入...",
            "sSearch": "搜索:",
            "sSearchPlaceholder": "",
            "sUrl": "",
            "sZeroRecords": "没有相关记录"
        };

        window.g_data = [];
        var search_param = [];

        //dataTable 请求函数
        function dataTable_fnServerData(sSource, aoData, fnCallback) {
            aoData = $.merge(aoData, search_param);

            var loading = layer.load(1, {
                shade: [0.3, '#000']
            });

            $.ajax({
                "dataType": 'json',
                "type": "GET",
                "url": sSource,
                "data": aoData,
                "success": fnCallback,
                "complete": function (data) {
                    var data = data.responseJSON.data;
                    layer.close(loading);
                    $.each(data, function () {
                        g_data[this.id] = this;
                    });
                }
            });
        }

        //dataTable 默认设置
        var dataTable_param = {
            "bPaginate": true,
            "bLengthChange": false,
            'bProcessing': true,
            "bFilter": false,
            "bSort": false,
            "bInfo": true,
            "bAutoWidth": false,
            "iDisplayLength": 20,
            "bServerSide": true,
            'bStateSave': false,
            "sAjaxDataProp": 'data',
            "sPaginationType": 'full_numbers',
            "oLanguage": oLanguage,
            "fnServerData": dataTable_fnServerData
        };

    </script>

</head>

<body class="skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="/" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">{{ config('app.name', 'Laravel') }}</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">{{ config('app.name', 'Laravel') }}</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <a href="/logout"
                   style="color: #ffffff; display: block; height: 50px;line-height: 50px;padding: 0 20px;">退出</a>
            </div>
            <div class="navbar-custom-menu">
                <a href="javascript:;"
                   style="color: #ffffff; display: block; height: 50px;line-height: 50px;padding: 0 20px;">
                    {{ \Illuminate\Support\Facades\Auth::user()->name }}
                </a>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu" id="menu">
                <li class="header"></li>
                <li class="dashboard">
                    <?php if(\App\Helpers\Access::can('/home')){?>
                    <a href="/"><i class='fa fa-dashboard'></i> <span>首页</span></a>
                    <?php }?>
                </li>


                <li class="treeview">
                    <a href="#"><i class='fa fa-cog'></i> <span>账号管理</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <?php \App\Helpers\Access::canEmmet('/admin/index', 'li.admin-index>a[href=`url`]{账号管理}');?>
                        <?php \App\Helpers\Access::canEmmet('/role/index', 'li.role-index>a[href=`url`]{角色管理}');?>
                        <?php \App\Helpers\Access::canEmmet('/dep/index', 'li.dep-index>a[href=`url`]{部门管理}');?>
                        <?php \App\Helpers\Access::canEmmet('/permission/index', 'li.permission-index>a[href=`url`]{权限管理}');?>
                    </ul>
                </li>

            </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div><!-- /.content-wrapper -->


    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class='control-sidebar-bg'></div>
</div><!-- ./wrapper -->

<style type="text/css">
    .treeview {
        display: none;
    }

    .dataTables_paginate {
        float: right;
    }

    .pagination {
        margin: 0;
    }

    .main-header {
        position: fixed;
        width: 100%;
    }

    .main-sidebar {
        position: fixed;
    }

    .content-wrapper {
        padding-top: 50px;
    }

    .edui-scale {
        -moz-box-sizing: content-box;
        box-sizing: content-box;
    }

    .main-sidebar {
        height: 100%;
        overflow-y: scroll;
    }

    .main-sidebar::-webkit-scrollbar {
        width: 5px;
    }

    /* 这是针对缺省样式 (必须的) */
    .main-sidebar::-webkit-scrollbar-track {
        background-color: #222d32;
    }

    /* 滚动条的滑轨背景颜色 */

    .main-sidebar::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 1);
    }

    /* 滑块颜色 */

    .main-sidebar::-webkit-scrollbar-button {
        background-color: #222d32;
    }

    /* 滑轨两头的监听按钮颜色 */

    .main-sidebar::-webkit-scrollbar-corner {
        background-color: black;
    }

    /* 横向滚动条和纵向滚动条相交处尖角的颜色 */

</style>
<script>
    function get422Errors(layer, msg, loading) {
        layer.close(loading);
        if (msg.status == 422) {
            var json = JSON.parse(msg.responseText);
            json = json.errors;
            for (var item in json) {
                for (var i = 0; i < json[item].length; i++) {
                    layer.msg(json[item][i], {icon: 5});
                    return; //遇到验证错误，就退出
                }
            }
        } else {
            layer.msg("系统错误", {icon: 5});
        }
    }
</script>
</body>
</html>
