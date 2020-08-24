@extends('admin::layouts.default')

@section('body')

@include('admin::partials.menus')

<!-- 右则内容区域 开始 -->
<!-- 页面标签 -->
<div class="layadmin-pagetabs" id="LAY_app_tabs">
    <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
    <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
    <div class="layui-tab" lay-unauto="" lay-allowclose="true" lay-filter="layadmin-layout-tabs">
        <ul class="layui-tab-title" id="LAY_app_tabsheader">
        	@if(false)
            <li lay-id="dashboards" lay-attr="dashboards" class="">
                <a data-open="{{ route('admin.dashboard') }}"><i class="layui-icon layui-icon-home"></i></a>
            </li>
            @endif
        </ul>
    </div>
</div>
<div class="framework-body">
</div>

<!-- 右则内容区域 结束 -->
@endsection