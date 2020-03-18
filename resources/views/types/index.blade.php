@extends('admin::layouts.content')

@section('button')
<button data-modal="{{route('admin.types.create', ['model'=>$model])}}" data-title="添加类别" class="layui-btn layui-btn-sm">添加类别</button>
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
                    <th class="text-left">ID</th>
                    <th class="text-left">名称</th>
                    <th class="text-left">分类级别</th>
                    <th class="text-left">上级分类</th>
                    <th class="text-left">添加者</th>
                    <th class="text-left">是否推荐</th>
                    <th class="text-left">添加时间</th>
                    <th class="text-left">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($list as $key => $vo)
                <tr>
                    <td class="list-table-sort-td">
                        <input name="_{{ $vo->id }}" value="{{ $vo->sort }}" class="list-sort-input">
                    </td>
                    <td class="text-left">{{ $vo->id }}</td>
                    <td class="text-left">{{ $vo->name }}</td>
                    <td class="text-left">
                        @if($vo->parent_id ==0)
                            <span class="color-green">一级分类 </span>
                        @else
                            <span class="color-blue">二级分类 </span>
                        @endif
                    </td>
                    <td class="text-left">{!! $vo->parent->name ?? '<span class="color-desc">未设置</span>' !!}</td>
                    <td class="text-left">{{ $vo->user->name }}</td>
                    <td class="text-left">
                        @if($vo->is_recommend)
                        <a data-update="{{ $vo->id }}" data-field="is_recommend" data-value="0" data-csrf="{{ csrf_token() }}" data-action="{{ route('admin.types.update', $vo->id) }}">已推荐</a>
                        @else
                        <a data-update="{{ $vo->id }}" data-field="is_recommend" data-value="1" data-csrf="{{ csrf_token() }}" data-action="{{ route('admin.types.update', $vo->id) }}">未推荐</a>
                        @endif
                    </td>
                    <td class="text-left"><span class="color-desc">{{ $vo->created_at->diffForHumans() }}</span></td>
                    <td class="text-left">
                        <a data-title="编辑类别" data-modal="{{ route('admin.types.edit', ['model'=>$model, 'type'=>$vo->id]) }}">编辑</a>
                        <span class="text-explode">|</span>
                        @if($vo->isUsed())
                        <span class="color-desc">删除</span>
                        @else
                        <a data-delete="{{ $vo->id }}" data-field="delete" data-csrf="{{ csrf_token() }}" data-action="{{ route('admin.types.destroy', ['type'=>$vo->id]) }}">删除</a>
                        @endif
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
<script>
(function () {
    window.form.render();
})();
</script>
@stop