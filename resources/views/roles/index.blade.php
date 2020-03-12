@extends('admin::layouts.content')

@section('button')
<button data-modal="{{route('admin.roles.create')}}" data-title="添加角色" class="layui-btn layui-btn-sm">添加角色</button>
@stop

@section('content')
<div class="layui-card">
	<div class="layui-card-body">
    <form autocomplete="off" onsubmit="return false;" data-auto="true" method="get">
        @empty($list)
        <p class="help-block text-center well">没 有 记 录 哦！</p>
        @else
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" value="resort" name="action">
        <table id="test" class="layui-table" lay-skin="line">
            <thead>
            <tr>
                <th class='list-table-sort-td'>
                    <button type="submit" class="layui-btn layui-btn-normal layui-btn-xs">排 序</button>
                </th>
                <th class='text-left'>名称</th>
                <th class='text-left'>描述</th>
                <th class='text-center'>状态</th>
                <th class='text-left'>添加时间</th>
                <th class='text-center'></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $key => $vo)
            <tr>
                <td class="list-table-sort-td">
                    <input name="_{{ $vo->id }}" value="{{ $vo->sort }}" class="list-sort-input">
                </td>
                <td class='text-left'>{{ $vo['name'] }}</td>
                <td class='text-left'>{{ $vo['remark'] ?? '<span class="color-desc">没有写描述哦！</span>'}}</td>
                <td class="text-center nowrap">
                    @if ($vo['status'] === 0)<span>已禁用</span>@elseif ($vo['status'] === 1)<span class="color-green">使用中</span>@endif
                </td>
                <td class='text-left'>{{ $vo['created_at'] }}</td>
                <td class="text-center nowrap notselect">
    
                    <span class="text-explode">|</span>
                    <a data-title="编辑角色" data-modal="{{ route('admin.roles.edit', ['role' => $vo->id]) }}">编辑</a>
                    
                    <span class="text-explode">|</span>
                    <a data-title="角色授权" data-open="{{ route('admin.roles.show', ['role' => $vo->id]) }}">授权</a>
    
                    @if ($vo['status'] === 1)
                    <span class="text-explode">|</span>
                    <a data-status="{{ $vo['ids'] }}" data-value="0" data-csrf="{{ csrf_token() }}" data-action="{{ route('admin.roles.update', ['role' => $vo->id]) }}">禁用</a>
                    @else
                    <span class="text-explode">|</span>
                    <a data-status="{{ $vo['ids'] }}" data-value="1" data-csrf="{{ csrf_token() }}" data-action="{{ route('admin.roles.update', ['role' => $vo->id]) }}">启用</a>
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
@stop