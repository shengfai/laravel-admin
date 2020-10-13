@extends('admin::layouts.content')

@section('button')
<a data-modal="{{route('admin.positions.create')}}" data-title="添加推荐位" class="layui-btn layui-btn-sm">添加推荐位</a>
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
                    <th class='text-center' width="60px">ID</th>
                    <th class='text-left' width="150px">名称</th>
                    <th class='text-center' width="80px">推荐量</th>
                    <th class='text-left'>描述</th>
                    <th class='text-left' width="90px">创建时间</th>
                    <th class='text-left' width="120px">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($list as $key => $vo)
                <tr>
                    <td class="list-table-sort-td">
                        <input name="_{{ $vo['id'] }}" value="{{ $vo['sort'] }}" class="list-sort-input">
                    </td>
                    <td class='text-center'>{{ $vo->id }}</td>
                    <td class='text-left'>
                        <img style="height:22px; vertical-align: text-bottom;" data-tips-text="{{ $vo->name }}缩略图" data-tips-image src="{{ $vo->cover_pic ? : '/admin/images/image.png' }}">
                    	{{ $vo->name }}
                    </td>
                    <td class='text-center'>{{ $vo->datas_count }}</td>
                    <td class='text-left'><span class="color-desc">{{ $vo->description }}</span></td>
                    <td class='text-left'><span class="color-desc">{{ $vo->created_at->diffForHumans() }}</span></td>
                    <td class="text-center nowrap notselect">
                        <a data-title="{{ $vo->name }}详情" data-open="{{route('admin.positions.show', $vo->id)}}">关联内容</a>
        				<span class="text-explode">|</span>
                        <a data-title="编辑{{ $vo->name }}详情" data-modal="{{ route('admin.positions.edit', $vo->id) }}">编辑</a>
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
