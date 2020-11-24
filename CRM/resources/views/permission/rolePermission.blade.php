@extends('layouts.app')

@section("content")

    <script type="text/javascript">
        $(function () {
            setNav(".role-index");
        });
    </script>

    <section class="content-header">
        <h1>权限分配</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <label class="btn pull-left">当前角色：{{$role['role_name']}}</label>
            </div>
            <div class="box-body">
                <form id="form">
                    <input type="hidden" name="role_id" value="{{$role['id']}}">
                    <?php foreach ($permissions as $permission){?>
                    <div class="checkbox">
                        <label><input type="checkbox" value="<?=$permission['id']?>" name="permission[]"
                                      class="permission"
                                      @if ($permission['is_role'] == '1') checked="true" @endif><?=$permission['permission_name']?>
                        </label>
                    </div>
                    <?php }?>
                </form>

            </div><!-- /.box-body -->
            <div class="box-header text-center">
                <a href="javascript:;" class="btn btn-primary" onclick="selectAll()">全选</a>
            </div>
            <div class="box-header text-center">
                <a href="javascript:;" class="btn btn-primary" style="margin-right: 30%"
                   onclick="savePermission()">保存</a>
                <a href="javascript:history.back(-1)" class="btn btn-primary"
                   style="background-color: #888888;border-color: #888888">返回</a>
            </div>
        </div><!-- /.box -->
    </section><!-- /.content -->

    <script src="/admin_resource/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="/admin_resource/plugins/datatables/dataTables.bootstrap.min.js" type="text/javascript"></script>

    <script type="text/javascript">
        window.g_data = [];

        function selectAll() {
            var permissions = $(".permission");
            // 遍历
            for (var i = 0; i < permissions.length; i++) {
                var permission = permissions[i];
                permission.checked = true;
            }
        }

        function savePermission() {
            var loading = layer.load(1, {
                shade: [0.3, '#000']
            });
            $.post("/role/savePermission", $("#form").serialize(), function (resp) {
                layer.close(loading);
                if (resp.code == 200) {
                    layer.msg("操作成功", {icon: 1});
                    window.location.reload();
                } else {
                    layer.msg(resp.msg, {icon: 5});
                }
            }).fail(function () {
                layer.close(loading);
                layer.msg("保存失败", {icon: 5});
            });
        }

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

