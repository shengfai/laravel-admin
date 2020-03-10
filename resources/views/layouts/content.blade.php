<!-- 右则内容区域 开始 -->
@yield('style')
<div class="layui-content">
    <div class="layui-header notselect">
        <div class="pull-left"><h5>@yield('title', $title)</h5></div>
        <div class="pull-right margin-right-15 nowrap">@yield('button')</div>
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
        @yield('content')
    </div>
</div>
@yield('script')
<!-- 右则内容区域 结束 -->