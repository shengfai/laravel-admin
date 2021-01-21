@extends('admin::layouts.content')

@section('button')
<button data-modal="{{route('admin.menus.create')}}" data-title="添加菜单" class="layui-btn layui-btn-sm">添加菜单</button>
@stop

@section('content')
<div class="layui-card">
    <div class="layui-card-body" style="padding: 15px;">
        <form class="layui-form" autocomplete="off" onsubmit="return false;" data-auto="true" method="get">
            @empty($list)
            <p class="help-block text-center well">没 有 记 录 哦！</p>
            @else
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" value="resort" name="action">
            <table id="test" class="layui-table" lay-skin="line">
                <thead>
                <tr>
                    <th class="list-table-sort-td">
                        <button type="submit" class="layui-btn layui-btn-normal layui-btn-xs">排 序</button>
                    </th>
                    <th class="text-center">图标</th>
                    <th>名称</th>
                    <th>权限标识</th>
                    <th class="visible-lg">链接</th>
                    <th class="text-center">状态</th>
                    <th class="text-center">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($list as $key => $vo)
                <tr>
                    <td class="list-table-sort-td">
                        <input name="_{{ $vo['id'] }}" value="{{ $vo['sort'] }}" class="list-sort-input">
                    </td>
                    <td class="text-center">
                        <i class="{{ $vo['icon'] }} font-s18"></i>
                    </td>
                    <td class="nowrap"><span class="color-desc">{!! $vo['spl'] !!}</span>{{ $vo['name'] }}</td>
                    <td class="visible-lg">{{ $vo['code'] }}</td>
                    <td class="visible-lg">{{ $vo['url'] }}</td>
                    <td class="text-center nowrap">
                        @if ($vo['status'] === 0)<span>已禁用</span>@elseif ($vo['status'] === 1)<span class="color-green">使用中</span>@endif
                    </td>
                    <td class="text-center nowrap notselect">
        
                        <span class="text-explode">|</span>
                        <!--{if $vo.spt<2}-->
                        @if ($vo['spt'] < 2)
                        <a data-title="添加菜单" data-modal="{{ route('admin.menus.create') }}?parent_id={{ $vo['id'] }}">添加下级</a>
                        @else
                        <a class="color-desc">添加下级</a>
                        @endif
        
                        <span class="text-explode">|</span>
                        <a data-title="编辑菜单" data-modal="{{ route('admin.menus.edit', $vo['id']) }}">编辑</a>
        
                        @if ($vo['status'] === 1)
                        <span class="text-explode">|</span>
                        <a data-status="{{ $vo['ids'] }}" data-value="0" data-csrf="{{ csrf_token() }}" data-action="{{ route('admin.menus.update', $vo['id']) }}">禁用</a>
                        @else
                        <span class="text-explode">|</span>
                        <a data-status="{{ $vo['ids'] }}" data-value="1" data-csrf="{{ csrf_token() }}" data-action="{{ route('admin.menus.update', $vo['id']) }}">启用</a>
                        @endif
        
                        <span class="text-explode">|</span>
                        <a data-delete="{{ $vo['ids'] }}" data-field="delete" data-csrf="{{ csrf_token() }}" data-action="{{ route('admin.menus.destroy', $vo['id']) }}">删除</a>
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