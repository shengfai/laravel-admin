<form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.dimensions.modules', $dimension->id) }}" data-auto="true" method="POST">
	
    <div class="layui-form-item">
        <label class="layui-form-label">适用模型</label>
		<div class="layui-input-block">
            @foreach ($modules as $key => $module)
            <label class="think-checkbox" required="required">
            	@if (in_array($module->id, $used_module_ids))
                <input type="checkbox" checked name="modules[]" value="{{ $module->id }}" lay-ignore> {{ $module->name }}
                @else
                <input type="checkbox" name="modules[]" value="{{ $module->id }}" lay-ignore> {{ $module->name }}
                @endif
            </label>
            @endforeach
            @empty($modules)<span class="color-desc" style="line-height:36px">未配置模型</span>@endif
        </div>
    </div>
    
    <div class="hr-line-dashed"></div>

    <div class="layui-form-item text-center">
    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="layui-btn" type="submit">保存数据</button>
        <button class="layui-btn layui-btn-danger" type="button" data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>
    <script>
    window.form.render();
    </script>
</form>
