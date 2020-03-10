@extends('admin::layouts.content')

@section('content')
<!-- 表单搜索 开始 -->
<div class="layui-card">
	<div class="layui-card-body">
    <form class="layui-form layui-form-pane form-search" action="{{route('admin.logs.index')}}" onsubmit="return false" method="get">
        <div class="layui-form-item layui-inline">
            <label class="layui-form-label">日志类型</label>
            <div class="layui-input-inline">
                <select name="type" class="layui-select" lay-search="">
                    <option value="" >- 全部日志 -</option>
                    <option value="1" @if($type==1) selected="selected" @endif>系统日志</option>
                    <option value="2" @if($type==2) selected="selected" @endif>操作日志</option>
                    <option value="3" @if($type==3) selected="selected" @endif>行为日志</option>
                    <option value="4" @if($type==4) selected="selected" @endif>抓取日志</option>
                </select>
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
                <th class="list-table-check-td think-checkbox">
                    <input data-auto-none="" data-check-target=".list-check-box" type="checkbox">
                </th>
                <th class="text-left">类型</th>
                <th class="text-left">用户</th>
                <th class="text-left">IP</th>
                <th class="text-left">方法</th>
                <th class="text-left">主机</th>
                <th class="text-left">节点</th>
                <th class="text-left">行为</th>
                <th class="text-left">内容</th>
                <th class="text-left">时间</th>
                <th class="text-left">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $key => $vo)
            <tr>
                <td class="list-table-check-td think-checkbox">
                    <input class="list-check-box" value="{{ $vo['id'] }}" type="checkbox">
                </td>
                <td class="text-left">{{ $vo->type_format }}</td>
                <td class="text-left">{{ $vo->user->name ?? '未设置'}}</td>
                <td class="text-left">{{ $vo->ip }}</td>
                <td class="text-left">{{ $vo->method }}</td>
                <td class="text-left">{{ $vo->host }}</td>
                <td class="text-left">{{ $vo->node }}</td>
                <td class="text-left">{{ $vo->action }}</td>
                <td class="text-left">{{ $vo->remark }}</td>
                <td class="text-left">{{ $vo->created_at->diffForHumans() }}</td>
                <td class="text-left">
                    <span class="text-explode">|</span>
                    <a data-title="查看日志" data-modal="{{ route('admin.logs.show', $vo['id']) }}">查看</a>
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