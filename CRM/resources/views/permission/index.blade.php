@extends('layouts.app')

@section("content")

    <script type="text/javascript">
        $(function () {
            setNav(".permission-index");
        });
    </script>

    <section class="content-header">
        <h1>权限管理</h1>
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
                            <label class="pull-left permission_name">权限名称</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-sm" name="permission_name">
                            </div>
                            <label class="pull-left permission_route">权限路径</label>
                            <div class="col-sm-2">
                                <input type="text" class="form-control input-sm" name="permission_route">
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
                        <th>序号</th>
                        <th>权限名称</th>
                        <th>权限路径</th>
                        <th>添加时间</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </section><!-- /.content -->

    <script type="text/template" id="tpl-edit">
    @include("permission.tpl_edit_permission")
    </script>

    <script src="/admin_resource/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/admin_resource/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        window.g_data = [];

        //搜索
        $(document).on('click', '#search', function () {
            search_param = $("#form").serializeArray();
            console.log(search_param);
            $("#list").dataTable().fnDraw();
        });

        $(function () {
            var table_param = {
                "sAjaxSource": "/permission/lists",
                "iDisplayLength": 10,
                'columns': [
                    {'data': 'id'},
                    {'data': 'permission_name'},
                    {'data': 'permission_route'},
                    {'data': 'created_at'},
                ]
            };

            $('#list').dataTable($.extend({}, dataTable_param, table_param));


            $(document).on('click', '.edit', function () {
                var id = $(this).data('id');
                if (!id) {
                    data = {
                        id: '',
                        permission_name: '',
                        permission_route: ''
                    };
                    edit(data);
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

                        $.post("/permission/save", $("#edit-form").serialize(), function (resp) {
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

