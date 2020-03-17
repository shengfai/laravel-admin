@empty($position->id)
	<form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.positions.store') }}" data-auto="true" method="POST">
@else
    <form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.positions.update', $position['id']) }}" data-auto="true" method="POST">
	<input type="hidden" name="_method" value="PUT">
@endif
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="layui-form-item">
        <label class="layui-form-label">名称</label>
        <div class="layui-input-block">
            <input type="text" name="name" value="{{ $position->name ??  '' }} " required="required" title="请输入推荐位名称" placeholder="请输入推荐位名称" class="layui-input">
        </div>
    </div>

	<div class="layui-form-item">
        <label class="layui-form-label">封面</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" onchange="$(this).nextAll('img').attr('src', this.value);" value="{{  $position->original_cover_pic ?? '' }}" name="cover_pic" title="请上传图片或输入图片URL地址" />
            <p class="help-block">文件最大2Mb，支持png/jpeg/jpg格式，建议上传图片的尺寸为300px300px。</p>
            <img style="width: 60px; height: auto;" data-tips-image src="{{ $position->cover_pic  ?? '/admin/images/image.png' }}" /> <a data-file="one" data-type="png,jpeg,jpg" data-field="cover_pic" class='btn btn-link'>上传图片</a>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">描述</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入推荐位描述" required="required" title="请输入推荐位描述" class="layui-textarea" name="description">{{ $position->description  ?? '' }}</textarea>
        </div>
    </div>

    <div class="hr-line-dashed"></div>

    <div class="layui-form-item text-center">
        <button class="layui-btn" type="submit">保存数据</button>
        <button class="layui-btn layui-btn-danger" type="button" data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>

</form>
<script>window.form.render();</script>