@extends('admin::layouts.content')

@section('content')
<div class="layui-card">
	<div class="layui-card-body">
	<!-- 表单搜索 开始 -->
	<form class="layui-form layui-form-pane form-search form-filters" action="{{route('admin.users.index')}}" onsubmit="return false" method="get">
        <div class="layui-inline">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-inline">
            	<input class="layui-input" type="text" name="filter[name]" placeholder="搜索名称" value="{{$name ?? ''}}" />
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-inline">
            	<input class="layui-input" type="text" name="filter[phone]" placeholder="搜索手机号" value="{{$phone ?? ''}}" />
            </div>
        </div>
        <div class="layui-inline">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                <select name="filter[status]" class="layui-select" lay-search="">
                    <option value="" >- 全部 -</option>
                    <option value="2" @if(isset($status) && ($status==2)) selected="selected" @endif>未激活</option>
                    <option value="9" @if(isset($status) && ($status==1)) selected="selected" @endif>使用中</option>
                </select>
            </div>
        </div>
        <div class="layui-inline">
            <button class="layui-btn layui-btn-primary"><i class="layui-icon">&#xe615;</i> 搜 索</button>
        </div>
    </form>
    <!-- 表单搜索 结束 -->
    <form autocomplete="off" onsubmit="return false;" data-auto="true" method="post">
        @empty($list)
        <p class="help-block text-center well">没 有 记 录 哦！</p>
        @else
        <input type="hidden" value="resort" name="action">
        <table id="test" class="layui-table" lay-skin="line">
            <thead>
            <tr>
                <th class='text-left'>ID</th>
                <th class='text-left'>名称</th>
                <th class='text-left'>类型</th>
                <th class='text-left'>手机</th>
                <th class='text-center'>状态</th>
                <th class='text-left'>注册时间</th>
                <th class='text-center'>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $key => $vo)
            <tr>
                <td class='text-left'>{{ $vo['id'] }}</td>
                <td class='text-left'>{{ $vo['name'] }}</td>
                <td class='text-left'>{{ $vo->getTypeName() }}</td>
                <td class='text-left'>{{ $vo['phone'] }}</td>
                <td class="text-center nowrap">
                    @if ($vo['status'] === 0)
                    <span>已禁用</span>
                    @elseif ($vo['status'] === 1)
                    <span class="color-green">使用中</span>
                    @elseif ($vo['status'] === 2)
                    <span class="color-desc">未激活</span>
                    @else
                    <span>未知</span>
                    @endif
                </td>
                <td class='text-left'>{{ $vo['created_at'] }}</td>
                <td class="text-center nowrap notselect">
    
                    <span class="text-explode">|</span>
                    <a data-title="用户编辑" data-modal="{{ route('admin.users.edit', $vo['id']) }}">编辑</a>
                    
                    <span class="text-explode">|</span>
                    <a data-title="设置密码" data-modal="{{ route('admin.users.reset_password', $vo['id']) }}">密码</a>
                    
                    @if ($vo['status'] === 1)
                    <span class="text-explode">|</span>
                    <a data-status="{{ $vo['ids'] }}" data-value="0" data-csrf="{{ csrf_token() }}" data-action="{{ route('admin.users.update', $vo['id']) }}">禁用</a>
                    @else
                    <span class="text-explode">|</span>
                    <a data-status="{{ $vo['ids'] }}" data-value="1" data-csrf="{{ csrf_token() }}" data-action="{{ route('admin.users.update', $vo['id']) }}">启用</a>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @endempty
    </form>
	</div>
</div>
<script>
(function () {
    window.form.render();
})();
</script>
@stop