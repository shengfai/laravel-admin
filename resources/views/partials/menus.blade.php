<!-- 顶部菜单区域 开始 -->
<div class="framework-topbar" style="position: fixed;top: 0;right: 0;width: 100%; " >
    <div class="topbar-head pull-left">
        <a href="" class="topbar-logo pull-left">{{ settings('app_name') }} <sup>{{ settings('app_version') }}</sup></a>
    </div>
    @foreach ($menus as $menu)
    @if ($menu['parent_id']==0)
    <a data-menu-node="m-{{ $menu['id'] }}" data-open="{{ $menu['url'] }}" class="topbar-btn pull-left transition">{{ $menu['name'] }}</a>
    @endif
    @endforeach
    <div class="pull-right">
        <div class="dropdown">
            <a href="#" class="dropdown-toggle topbar-btn text-center transition" data-toggle="dropdown">
                <span class="glyphicon glyphicon-user font-s13"></span>
                {{ Auth::user()->name }}
                <span class="toggle-icon glyphicon glyphicon-menu-up transition font-s13"></span>
            </a>
            <ul class="dropdown-menu">
                <li class="topbar-btn"><a data-modal="{{ route('admin.users.reset_password', Auth::user()->id) }}" data-title="更新密码"><i class="glyphicon glyphicon-lock"></i> 修改密码</a></li>
                <li class="topbar-btn"><a data-modal="{{ route('admin.users.edit', Auth::user()->id) }}" data-title="更新资料"><i class="glyphicon glyphicon-edit"></i> 修改资料</a></li>
                <li class="topbar-btn">
                    <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="glyphicon glyphicon-log-out"></i> 退出登录</a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </div>
    </div>
	<a class="topbar-btn pull-right transition" data-tips-text="消息" data-open="{{ route('admin.notices.index') }}" style="width:50px">
		<i class="layui-icon layui-icon-notice"></i>
		@if(Auth::user()->unreadNotifications()->count() > 0)
		<span class="layui-badge-dot"></span>
		@endif
	</a>
    <a class="topbar-btn pull-right transition" data-tips-text="刷新" data-reload="true" style="width:50px"><span class="glyphicon glyphicon-refresh font-s12"></span></a>
</div>
<!-- 顶部菜单区域 结束 -->

<!-- 左则菜单区域 开始 -->
<div class="framework-leftbar">
    @foreach ($menus as $menu)
    @isset($menu['sub'])
    <div class="leftbar-container hide notselect" data-menu-layout="m-{{ $menu['id'] }}">
    	@if(false)
        <div class="line-top">
            <i class="layui-icon font-s12">&#xe65f;</i>
        </div>
        @endif
        @foreach ($menu['sub'] as $subMenu)
        @if (!isset($subMenu['sub']))
        <a class="transition" data-menu-node="m-{{ $menu['id'] }}-{{ $subMenu['id'] }}" data-open="{{ url($subMenu['url']) }}?{{ $subMenu['params'] }}">
            {{ $subMenu['name'] }}
        </a>
        @else
        <div data-submenu-layout="m-{{ $menu['id'] }}-{{ $subMenu['id'] }}">
            <a class="menu-title transition">
                @empty(!$subMenu['icon'])<span class="{{ $subMenu['icon'] }} font-icon"></span>&nbsp;@endempty
                {{ $subMenu['name'] }} <i class="layui-icon pull-right font-s12 transition">&#xe619;</i>
            </a>
            <div>
                @foreach ($subMenu['sub'] as $thrMenu)
                <a class="transition" data-open="{{ url($thrMenu['url']) }}?{{ $thrMenu['params'] }}" data-menu-node="m-{{ $menu['id'] }}-{{ $subMenu['id'] }}-{{ $thrMenu['id'] }}">
                    @empty(!$thrMenu['icon'])<span class="{{ $thrMenu['icon'] }} font-icon"></span>@endempty {{ $thrMenu['name'] }}
                </a>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endisset
    @endforeach
</div>
<!-- 左则菜单区域 结束 -->