@extends('admin::layouts.content')
	
@section('content')
<div class="layui-row layui-col-space15">
    <div class="layui-col-sm6 layui-col-md3">
        <div class="layui-card">
            <div class="layui-card-header">
                注册量 <span class="layui-badge layui-bg-blue layuiadmin-badge">7日</span>
            </div>
            <div class="layui-card-body layuiadmin-card-list">
                <p class="layuiadmin-big-font text-center">
                    <a data-tips-text="七日" data-open="">0</a>
                    <a data-tips-text="当天" data-open=""><sub style="font-size: 14px">(0)</sub></a>
                </p>
                <p>
                    <span class="layuiadmin-middle-font">总注册量</span>
                    <span class="layuiadmin-span-color">
                        <a data-open="">0</a>
                    </span>
                </p>
            </div>
        </div>
    </div>
    
    <div class="layui-col-sm6 layui-col-md3">
        <div class="layui-card">
            <div class="layui-card-header">
                机构数 <span class="layui-badge layui-bg-cyan layuiadmin-badge">7日</span>
            </div>
            <div class="layui-card-body layuiadmin-card-list">
                <p class="layuiadmin-big-font text-center">0</p>
                <p>
                    <span class="layuiadmin-middle-font">总机构数</span>
                    <span class="layuiadmin-span-color">0</span>
                </p>
            </div>
        </div>
    </div>
    
    <div class="layui-col-sm6 layui-col-md3">
        <div class="layui-card">
            <div class="layui-card-header">
                任务数 <span class="layui-badge layui-bg-green layuiadmin-badge">7日</span>
            </div>
            <div class="layui-card-body layuiadmin-card-list">
                <p class="layuiadmin-big-font text-center">0</p>
                <p>
                    <span class="layuiadmin-middle-font">总任务数</span>
                    <span class="layuiadmin-span-color">0</span>
                </p>
            </div>
        </div>
    </div>
    
    <div class="layui-col-sm6 layui-col-md3">
        <div class="layui-card">
            <div class="layui-card-header">
                标签数 <span class="layui-badge layui-bg-orange layuiadmin-badge">7日</span>
            </div>
            <div class="layui-card-body layuiadmin-card-list">
                <p class="layuiadmin-big-font text-center">0</p>
                <p>
                    <span class="layuiadmin-middle-font">总标签数</span>
                    <span class="layuiadmin-span-color">0</span>
                </p>
            </div>
        </div>
    </div>
    
    <div class="layui-col-sm12">
        <div class="layui-card">
            <div class="layui-card-header">
                用户行为
                {{--
                <div class="layui-btn-group layuiadmin-btn-group">
                    <a href="javascript:;" class="layui-btn layui-btn-primary layui-btn-xs">去年</a> <a href="javascript:;" class="layui-btn layui-btn-primary layui-btn-xs">今年</a>
                </div>
                --}}
            </div>
            <div class="layui-card-body">
                <div class="layui-row">
                    <div class="layui-col-sm8">
                        <div id="users-trends" class="layui-carousel layadmin-carousel layadmin-dataview" style="width: 100%; height: 300px;"></div>
                    </div>
                    <div class="layui-col-sm4">
                        <div class="layuiadmin-card-list">
                            <p class="layuiadmin-normal-font">周用户（100000）</p>
                            <span>环比增加</span>
                            <div class="layui-progress layui-progress-big" lay-showpercent="yes">
                                <div class="layui-progress-bar" lay-percent="30%" style="width: 30%;">
                                    <span class="layui-progress-text">30%</span>
                                </div>
                            </div>
                        </div>
                        <div class="layuiadmin-card-list">
                            <p class="layuiadmin-normal-font">周访问（100000）</p>
                            <span>环比增加</span>
                            <div class="layui-progress layui-progress-big" lay-showpercent="yes">
                                <div class="layui-progress-bar" lay-percent="60%" style="width: 60%;">
                                    <span class="layui-progress-text">60%</span>
                                </div>
                            </div>
                        </div>
                        <div class="layuiadmin-card-list">
                            <p class="layuiadmin-normal-font">周订单（10000）</p>
                            <span>环比增加</span>
                            <div class="layui-progress layui-progress-big" lay-showpercent="yes">
                                <div class="layui-progress-bar" lay-percent="80%" style="width: 80%;">
                                    <span class="layui-progress-text">80%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
