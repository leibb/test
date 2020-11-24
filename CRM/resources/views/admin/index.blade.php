@extends('layouts.app')

@section("content")

    <script type="text/javascript">
        $(function () {
            setNav(".admin-index");
        });
    </script>

    <section class="content-header">
        <h1>账号管理</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <a href="javascript:;" class="btn btn-primary edit pull-right">添加</a>
            </div>
            <div class="box-body">
                <form id="form">
                    <div class="">
                        <div class="row col-sm-12">
                            <label class="pull-left name">姓名</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-sm" name="name">
                            </div>
                            <label class="pull-left phone">手机</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-sm" name="phone">
                            </div>
                            <div class="col-sm-1">
                                <a class="btn btn-block btn-default btn-flat" id="search">搜索</a>
                            </div>
                        </div>
                    </div>
                </form>
                <table id="list" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>工号</th>
                        <th>姓名</th>
                        <th>账户昵称</th>
                        {{--                        <th>所属角色</th>--}}
                        {{--                        <th>上级人员</th>--}}
                        <th>所属部门</th>
                        <th>手机号码</th>
                        <th>邮箱地址</th>
                        <th>添加时间</th>
                        <th>最后登录</th>
                        <th>是否启用</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </section><!-- /.content -->

    <script type="text/template" id="tpl-edit">
    @include("admin.tpl_edit_admin")
    </script>


    <script src="/admin_resource/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/admin_resource/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        window.g_data = [];

        //搜索
        $(document).on('click', '#search', function () {
            search_param = $("#form").serializeArray();
            $("#list").dataTable().fnDraw();
        });

        $(function () {
            var table_param = {
                "sAjaxSource": "/admin/lists",
                "iDisplayLength": 10,
                'columns': [
                    {'data': 'number'},
                    {'data': 'name'},
                    {'data': 'nickname'},
                    // {'data': 'role_name'},
                    // {'data': 'parent_admin_name'},
                    {'data': 'dep_name'},
                    {'data': 'phone'},
                    {'data': 'email'},
                    {'data': 'created_at'},
                    {'data': 'login_time'},
                    {
                        'data': 'status',
                        "render": function (data, type, full) {
                            return data == '1' ? '启用' : '停用';
                        }
                    },
                    {
                        "data": "id",
                        "render": function (data, type, full) {
                            return "<a href='javascript:;' class='fa fa-pencil-square-o' data-id='" + data + "' title='重置密码'>重置密码</a>" + "&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:;' class='edit fa fa-pencil-square-o' data-id='" + data + "' title='编辑'>编辑</a>" + "&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:;' class='open fa fa-pencil-square-o' data-id='" + data + "' data-status='1' title='启用'>启用</a>" + "&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:;' class='open fa fa-close' data-id='" + data + "' data-status='2' title='停用'>停用</a>";
                        }
                    },
                ]
            };

            $('#list').dataTable($.extend({}, dataTable_param, table_param));


            $(document).on('click', '.edit', function () {
                var id = $(this).data('id');
                if (!id) {
                    data = {
                        id: '',
                        number: '',
                        name: '',
                        nickname: '',
                        phone: '',
                        email: '',
                        status: '',
                        role_id: '',
                        dep_id: '',
                        roles: {}
                    };
                    edit(data);
                } else {
                    var loading = layer.load(1, {
                        shade: [0.3, '#000']
                    });
                    $.get("/admin/info", {id: id}, function (resp) {
                        layer.close(loading);
                        if (resp.code == 200) {
                            edit(resp.data);
                        } else {
                            layer.msg(resp.msg, {icon: 5});
                        }
                    }).fail(function () {
                        layer.close(loading);
                        layer.msg("保存失败", {icon: 5});
                    });
                }


            });

            function edit(data) {
                layer.open({
                    type: 1,
                    anim: 2,
                    maxWidth: 1000,
                    shadeClose: false,
                    title: "编辑",
                    content: _.template($("#tpl-edit").html())(data),
                    btn: ['保存', '关闭'],
                    success: function () {
                        $(".permission-group").each(function () {
                            if ($(this).find(".permissions .permission").length > 0) {
                                $(this).show();
                            }
                        });
                    },
                    yes: function (index, layero) {
                        var loading = layer.load(1, {
                            shade: [0.3, '#000']
                        });

                        $.post("/admin/save", $("#edit-form").serialize(), function (resp) {
                            if (resp.code == 200) {
                                layer.closeAll();
                                layer.msg("保存成功", {icon: 1});
                                setTimeout(function () {
                                    $("#list").dataTable().fnDraw();
                                }, 500);
                            } else {
                                layer.close(loading);
                                layer.msg(resp.msg, {icon: 5});
                            }
                        }).fail(function (msg) {
                            get422Errors(layer, msg, loading);
                        });
                    },
                    btn2: function (index) {
                        layer.closeAll();
                    }
                });
            }

            //启用停用
            $(document).on('click', '.open', function () {
                var id = $(this).data("id");
                var status = $(this).data("status");
                var comment = '';
                if (status == '1') comment = '启用';
                if (status == '2') comment = '停用';

                layer.confirm("确定" + comment + "？", function () {
                    var loading = layer.load(1, {
                        shade: [0.3, '#000']
                    });

                    $.post('/admin/saveStatus', {id: id, status: status}, function (res) {
                        if (res.code == 200) {
                            layer.closeAll();
                            layer.msg("操作成功", {icon: 1});
                            $("#list").dataTable().fnDraw(false);
                        } else {
                            layer.close(loading);
                            layer.msg(res.msg, {icon: 5});
                        }
                    }).fail(function () {
                        layer.close(loading);
                        layer.msg("网络错误", {icon: 5});
                    });
                })
            });

        });

    </script>


    <style type="text/css">
        .edit-dialog .list li {
            list-style: none;
        }

        .edit-dialog .list li label {
            text-align: right;
            width: 120px;
        }

        .edit-dialog .list li span {
            padding-left: 20px;
        }

    </style>

@endsection

