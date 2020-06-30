<form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.model.save', ['model'=> $modelName, 'id'=>$model->id ?? 0]) }}" data-auto="true" method="POST">
	@foreach ($editFields as $vo)
	@php
	$title = $vo->getOption('title');
	$field_name = $vo->getOption('field_name');
	$value = $model->{$field_name} ?? '';
	@endphp
    <div class="layui-form-item">
        <label class="layui-form-label">{{ $vo->getOption('title') }}</label>
        <div class="layui-input-block">
        	@if($vo->getOption('type') === 'text')
            <input type="text" name="{{$field_name}}" value="{{$model->{$field_name} ?? ''}}" class="layui-input" required="required" title="请输入{{$title}}" placeholder="请输入{{$title}}">
            @elseif($vo->getOption('type') === 'textarea')
            <textarea name="{{$field_name}}" class="layui-textarea" required="required" placeholder="请输入{{$title}}" title="请输入{{$title}}">{{$model->{$field_name} ?? ''}}</textarea>
            @elseif($vo->getOption('type') === 'switch')
            <input type="checkbox" name="{{$field_name}}" lay-skin="switch" @if($value) checked @endif lay-text="{{ implode('|', \Arr::pluck($vo->getOption('options'), 'text')) }}">
            @elseif($vo->getOption('type') === 'image')
            <input type="text" class="layui-input validate-error" required="required" onchange="$(this).nextAll('img').attr('src', this.value);" value="{{$model->{$field_name} ?? ''}}" name="{{$field_name}}" title="请上传图片或输入图片URL地址">
            <p class="help-block">{!! $vo->getOption('tips') !!}</p>
            <img style="width: 30px; height: auto;" data-tips-image="" src="{{$model->{$field_name} ?? '/admin/images/image.png'}}"> <a data-file="one" data-type="ico,png,jpeg,jpg" data-field="{{$field_name}}" class="btn btn-link">上传图片</a>
            @endif
        </div>
    </div>
	@endforeach

	{{--
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
    --}}

    <div class="hr-line-dashed"></div>

    <div class="layui-form-item text-center">
    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="layui-btn" type="submit">保存数据</button>
        <button class="layui-btn layui-btn-danger" type="button" data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>

</form>
<script>window.form.render();</script>