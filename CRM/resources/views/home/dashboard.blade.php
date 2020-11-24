@extends('layouts.app')
@section("content")

    <script type="text/javascript">
        $(function () {
            setNav(".dashboard");
        });
    </script>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            系统概览
        </h1>
    </section>

    <!-- Main content -->
    {{--<section class="content">
        <div class="row hidden">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <a href="/withdraw" class="small-box bg-aqua">
                    <div class="inner">
                        <h3>11111111111</h3>
                        <p>提现申请</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-bell-o"></i>
                    </div>
                    <div class="small-box-footer">
                        查看详情<i class="fa fa-arrow-circle-right"></i>
                    </div>
                </a>
            </div><!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <a href="/apply_upgrade" class="small-box bg-green">
                    <div class="inner">
                        <h3>111111111111</h3>
                        <p>直升等级申请</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-bell-o"></i>
                    </div>
                    <div class="small-box-footer">
                        查看详情<i class="fa fa-arrow-circle-right"></i>
                    </div>
                </a>
            </div><!-- ./col -->

        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="box box-success box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">用户注册情况</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-striped">
                            <tbody>
                            <tr>
                                <td>今日注册</td>
                                <td>11111</td>
                            </tr>
                            <tr>
                                <td>昨日注册</td>
                                <td>11111</td>
                            </tr>
                            <tr>
                                <td>本周注册</td>
                                <td>11111111111</td>
                            </tr>
                            <tr>
                                <td>本月注册</td>
                                <td>12312</td>
                            </tr>
                            <tr>
                                <td>用户总数</td>
                                <td>213412312</td>
                            </tr>
                            <tr class="hidden">
                                <td colspan="2">asdasdas</td>
                            </tr>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->


        </div>

    </section>--}}<!-- /.content -->
@endsection
