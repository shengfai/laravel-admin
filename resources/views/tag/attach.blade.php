<link href="{{ asset('admin/plugs/select2/select2.min.css') }}" rel="stylesheet">
<form autocomplete="off" class="layui-form layui-box modal-form-box" style="padding: 20px 50px 15px 0px;" action="{{ route('admin.tags.attach', ['model'=>$class, 'id'=>$id]) }}" data-auto="true" method="POST">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">
	
	@empty($model->dimensions)
    <p class="help-block text-center well">所属模型未配置标签维度！</p>
    @else
	@foreach ($model->dimensions as $dimension)
	<div class="layui-form-item">
        <label class="layui-form-label">{{ $dimension->name }}@维度</label>
        <div class="layui-input-block">
            <select class="layui-input taggable-{{ $dimension->id }}-multiple-limit" multiple="multiple" name="tags[]">
            	@foreach ($dimension->tags as $tag)
                <option value="{{ $tag->id }}"  @if ($used_tags->contains($tag->id)) selected @endif>{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endforeach
    @endempty
	
    <div class="text-center">
        <button class="layui-btn" type="submit">保存数据</button>
        <button class="layui-btn layui-btn-danger" type="button" data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>
</form>

<script type="text/javascript">
require(['jquery', '/admin/plugs/select2/select2.min.js'], function () {
	window.form.render();
	@foreach ($model->dimensions as $dimension)
	$(".taggable-{{ $dimension->id }}-multiple-limit").select2({
    	placeholder: "请选择{{ $dimension->name }}维度标签",
    	tags: false,
    	multiple: true,
    	maximumSelectionLength: {{ $dimension->limits }},
    });
	@endforeach
});
</script>