<!-- 右则内容区域 开始 -->
@yield('style')
<div class="layui-content">
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
        @isset($title)
         <div class="layui-header notselect page-subject">
            <div class="pull-left">@yield('title', $title)</div>
            <div class="pull-right nowrap">@yield('button')</div>
        </div>
        @endisset
        @yield('content')
    </div>
</div>
@yield('script')
<!-- 右则内容区域 结束 -->