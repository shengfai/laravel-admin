<table class="layui-table layui-box modal-form-box" lay-even lay-skin="nob" lay-size="sm" style="width:96%;margin:18px auto;">
    <tbody>
		@foreach ($columns as $vo)
    	@if ($vo['column_name'] !== 'operation')
        <tr>
            <td><b>{{$vo['title']}}</b></td>
            <td><div class="inline-block text-top margin-right-5">{{ $model->{$vo['column_name']} }}</div></td>
        </tr>
        @endif
		@endforeach
    </tbody>
</table>
