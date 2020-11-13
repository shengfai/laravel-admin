@extends('admin::layouts.content')

@section('button')
<button data-modal="{{ route('admin.managers.create') }}" data-title="添加管理员" class="layui-btn layui-btn-sm">添加管理员</button>
@stop

@section('content')
<div class="layui-card">
	<div class="layui-card-body">
	<!-- 表单搜索 开始 -->
	<form class="layui-form layui-form-pane form-search" action="{{route('admin.managers.index')}}" onsubmit="return false" method="get">
        <div class="layui-form-item layui-inline">
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-inline">
            	<input class="layui-input" type="number" name="phone" placeholder="搜索手机号" value="{{$phone ?? ''}}" />
            </div>
        </div>
        <div class="layui-form-item layui-inline">
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
                <th class='text-left'>备注</th>
                <th class='text-left'>角色</th>
                <th class='text-left'>手机</th>
                <th class='text-left'>状态</th>
                <th class='text-left'>创建时间</th>
                <th class='text-left'>操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $key => $vo)
            <tr>
                <td class='text-left'>{{ $vo->id }}</td>
                <td class='text-left'>{{ $vo->name }}</td>
                <td class='text-left'>{{ \Str::limit($vo->remark, 15) }}</td>
                <td class='text-left'><span class="color-blue" data-tips-text="{{ optional($vo->roles->first())->remark }}">{{ optional($vo->roles->first())->name }}</span></td>
                <td class='text-left'>{{ $vo->phone }}</td>
                <td class="text-left">
                    @if ($vo->status == 0)<span>已禁用</span>@elseif ($vo->status == 1)<span class="color-green">使用中</span>@endif
                </td>
                <td class='text-left'>{{ $vo->created_at->toDateTimeString() }}</td>
                <td class="text-left" width="200px">
                    <span class="text-explode">|</span>
                    <a data-title="用户编辑" data-modal="{{ route('admin.managers.edit', $vo->id) }}">编辑</a>
                    <span class="text-explode">|</span>
                    <a data-title="设置密码" data-modal="{{ route('admin.users.reset_password', $vo->id) }}">密码</a>
                    <span class="text-explode">|</span>
                    <a data-title="用户授权" data-modal="{{ route('admin.managers.authorizations', $vo->id) }}">授权</a>
                    <span class="text-explode">|</span>
                    <a data-delete="{{ $vo->id }}" data-field="delete" data-csrf="{{ csrf_token() }}" data-action="{{ route('admin.managers.destroy', $vo->id) }}">移除</a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
         @if (isset($page))<p>{!! $page !!}</p>@endif
        @endempty
    </form>
	</div>
</div>
@stop