@extends('admin::layouts.content')

@section('button')
<a data-modal="{{route('admin.positions.datas.create', $position->id)}}" data-title="【{{$position->name}}】内容推送" class="layui-btn layui-btn-sm">推送内容</a>
@stop

@section('content')
<div class="layui-card">
    <div class="layui-card-body">
        <form autocomplete="off" onsubmit="return false;" data-auto="true" action="{{route('admin.positions.datas', $position->id)}}" method="get">
            @empty($position->datas)
            <p class="help-block text-center well">没 有 记 录 哦！</p>
            @else
            <input type="hidden" value="resort" name="action">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <table id="test" class="layui-table" lay-skin="line" lay-size="sm">
                <thead>
                <tr>
                    <th class="list-table-sort-td text-center">
                        <button type="submit" class="layui-btn layui-btn-normal layui-btn-xs">排 序</button>
                    </th>
                    <th class="text-left" width="80px">关联模型</th>
                    <th class="text-left" width="450px">标题</th>
                    <th class="text-left">摘要</th>
                    <th class="text-left" width="80px">推送时间</th>
                    <th class="text-left" width="90px">操作</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($position->datas as $key => $vo)
                <tr>
                    <td class="list-table-sort-td">
                        <input name="_{{ $vo->id }}" value="{{ $vo->sort }}" class="list-sort-input">
                    </td>
                    <td class="text-left">{{$vo->getPositionableModel('name')}}</a></td>
                    <td class="text-left">
                        <img style="height:22px; vertical-align: text-bottom;" data-tips-text="{{ $vo->title }}缩略图" data-tips-image src="{{ $vo->cover_pic ? : '/admin/images/image.png' }}">
                        {{$vo->title}}
                    </td>
                    <td class="text-left"><span class="color-desc">{{ $vo->description }}</span></td>
                    <td class="text-left"><span class="color-desc">{{ $vo->updated_at->diffForHumans() }}</span></td>
                    <td class="text-left">
                        <a data-modal="{{ route('admin.datas.edit', $vo->id) }}">编辑</a>
                        <span class="text-explode">|</span>
                        <a data-delete="{{ $vo->id }}" data-field="delete" data-csrf="{{ csrf_token() }}" data-action="{{ route('admin.datas.destroy', $vo->id) }}">删除</a>
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