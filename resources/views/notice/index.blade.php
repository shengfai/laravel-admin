@extends('admin::layouts.content')

@section('content')
<div class="layui-card">
	<div class="layui-card-body">
    
    <form autocomplete="off" onsubmit="return false;" data-auto="true" method="post" action="{{ route('admin.notices.index') }}">
        @empty($list)
        <p class="help-block text-center well">没 有 记 录 哦！</p>
        @else
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        {{--
        <div class="LAY-app-message-btns" style="margin-bottom: 10px;">
        	<button class="layui-btn layui-btn-primary layui-btn-sm" data-type="all" data-events="ready">标记已读</button>
		</div>
		--}}
        <table id="test" class="layui-table" lay-skin="line">
            <thead>
            <tr>
                <th class="list-table-check-td think-checkbox">
                    <input data-auto-none="" data-check-target=".list-check-box" type="checkbox">
                </th>
                <th class="text-left">主题</th>
                <th class="text-left">类型</th>
                <th class="text-left">内容</th>
                <th class="text-left">时间</th>
                <th class="text-left">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($list as $key => $vo)
            <tr>
                <td class="list-table-check-td think-checkbox">
                    <input class="list-check-box" value="{{ $vo->id }}" type="checkbox">
                </td>
                <th class="text-left">{{ config('administrator.available_notified_topics')[$vo->topic] ?? '未配置' }}</th>
                <th class="text-left">{{ class_basename($vo->type) }}</th>
                <td class="text-left">
                	@if($vo->read_at)
                	<span class="color-desc">{{ $vo->data['message'] }}</span>
                	@else
                	<span class="color-text">{{ $vo->data['message'] }}</span>
                	<span class="layui-badge-dot layui-bg-blue"></span>
                	@endif
                </td>
                <td class="text-left"><span class="color-desc">{{ $vo->created_at }}</span></td>
                <td class="text-left">
                    <a data-title="查看消息" data-modal="{{ route('admin.notices.show', $vo->id) }}">查看</a>
                    {{--
                    <span class="text-explode">|</span>
                    <a data-delete="{{ $vo->id }}" data-field="delete" data-csrf="{{ csrf_token() }}" data-action="{{ route('admin.notices.destroy', $vo->id) }}">删除</a>
                	--}}
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