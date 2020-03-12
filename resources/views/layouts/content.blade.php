<!-- 右则内容区域 开始 -->
@yield('style')
<div class="layui-content">

    <!-- 页面标签 -->
    <div class="layadmin-pagetabs" id="LAY_app_tabs">
        <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
        <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
        <div class="layui-tab" lay-unauto="" lay-allowclose="true" lay-filter="layadmin-layout-tabs">
          	<ul class="layui-tab-title" id="LAY_app_tabsheader">
            	<li lay-id="home/console.html" lay-attr="home/console.html" class=""><i class="layui-icon layui-icon-home"></i><i class="layui-icon layui-unselect layui-tab-close">ဆ</i></li>
          		<li lay-id="home/homepage2.html" lay-attr="home/homepage2.html" class="layui-this"><span>主页二</span><i class="layui-icon layui-unselect layui-tab-close">ဆ</i></li>
          	</ul>
        </div>
	</div>
    
    <div class="layui-fluid">
        @isset($alert)
        <div class="alert alert-{{$alert['type']}} alert-dismissible" role="alert" style="border-radius:0">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            @isset($alert['title'])<p style="font-size:18px;padding-bottom:10px">{{$alert['title']}}</p>@endisset
            @isset($alert['content'])<p style="font-size:14px">{!! $alert['content'] !!}</p>@endisset
        </div>
        @endisset
         <div class="layui-header notselect page-subject">
            <div class="pull-left">@yield('title', $title)</div>
            <div class="pull-right margin-right-15 nowrap">@yield('button')</div>
        </div>
        @yield('content')
    </div>
</div>
@yield('script')
<!-- 右则内容区域 结束 -->