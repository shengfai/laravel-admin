@extends('admin::layouts.content')

@section('button')
<button data-modal="{{url('/admin/users/create')}}" data-title="添加管理员" class="layui-btn layui-btn-sm">添加管理员</button>
@stop

@section('content')
<form autocomplete="off" onsubmit="return false;" data-auto="true" method="post">
    @empty($list)
    <p class="help-block text-center well">没 有 记 录 哦！</p>
    @else
    <input type="hidden" value="resort" name="action">
    <table id="test" class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th class='list-table-sort-td'>
                <button type="submit" class="layui-btn layui-btn-normal layui-btn-xs">排 序</button>
            </th>
            <th class='text-left'>ID</th>
            <th class='text-left'>名称</th>
            <th class='text-left'>类型</th>
            <th class='text-left'>手机</th>
            <th class='text-center'>状态</th>
            <th class='text-left'>注册时间</th>
            <th class='text-center'></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($list as $key => $vo)
        <tr>
            <td class="list-table-sort-td">
                <input name="_{{ $vo['id'] }}" value="{{ $vo['sort'] }}" class="list-sort-input">
            </td>
            <td class='text-left'>{{ $vo['id'] }}</td>
            <td class='text-left'>{{ $vo['name'] }}</td>
            <td class='text-left'>{{ $vo['type_format'] }}</td>
            <td class='text-left'>{{ $vo['phone'] }}</td>
            <td class="text-center nowrap">
                @if ($vo['status'] === 0)<span>已禁用</span>@elseif ($vo['status'] === 1)<span class="color-green">使用中</span>@endif
            </td>
            <td class='text-left'>{{ $vo['created_at'] }}</td>
            <td class="text-center nowrap notselect">

                <span class="text-explode">|</span>
                <a data-title="用户编辑" data-modal="{{ route('admin.users.edit', $vo['id']) }}">编辑</a>
                
                <span class="text-explode">|</span>
                <a data-title="设置密码" data-modal="{{ route('admin.users.reset_password', $vo['id']) }}">密码</a>
                
                <span class="text-explode">|</span>
                <a data-title="用户授权" data-modal="{{ route('admin.users.authorizations', $vo['id']) }}">授权</a>

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
@stop