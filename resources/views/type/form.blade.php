@empty($type->id)
	<form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.types.store', ['model'=>$model]) }}" data-auto="true" method="POST">
@else
    <form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.types.update', $type->id) }}" data-auto="true" method="PUT">
	<input type="hidden" name="_method" value="PUT">
@endif
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	<div class="layui-form-item">
         <label class="layui-form-label">上级类别</label>
         <div class="layui-input-block">
             <select name="parent_id" class="layui-select full-width" lay-ignore>
					<option value="0" >- 选择上级类别 -</option>
					@foreach ($parent_types as $key => $vo)
					<option value="{{$vo->id}}" @if(isset($type->parent_id) && $type->parent_id==$vo->id) selected="selected" @endif>{{$vo->name}}</option>
					@endforeach
             </select>
         </div>
     </div>

    <div class="layui-form-item">
        <label class="layui-form-label">类别名称</label>
        <div class="layui-input-block">
            <input type="text" name="name" value="{{ $type->name ?? '' }}" required="required" title="请输入类型名称" placeholder="请输入类型名称" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">类别封面</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" onchange="$(this).nextAll('img').attr('src', this.value);" value="{{ $type->cover_pic ?? ''}}" name="cover_pic" title="请上传图片或输入图片URL地址" />
            <p class="help-block">文件最大2Mb，支持png/jpeg/jpg格式，建议上传图片的尺寸为300px300px。</p>
            <img style="width: 60px; height: auto;" data-tips-image src="{{ $type->cover_pic ?? '/admin/images/image.png' }}" /> <a data-file="one" data-type="png,jpeg,jpg" data-field="cover_pic" class='btn btn-link'>上传图片</a>
        </div>
    </div>

    <div class="hr-line-dashed"></div>

    <div class="layui-form-item text-center">
        <button class="layui-btn" type="submit">保存数据</button>
        @empty($type->id)<input type="hidden" name="model_type" value="{{$model}}" />@endif
        <button class="layui-btn layui-btn-danger" type="button" data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>
    <script>
        $(function () {
            $("[name='is_recommend'][value='{{ isset($type->is_recommend) ? $type->is_recommend : 1}}']").attr("checked",true);
        });
    window.form.render();
    </script>
</form>