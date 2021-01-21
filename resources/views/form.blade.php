<form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.model.save', ['model'=> $modelName, 'id'=>$model->id ?? 0]) }}" data-auto="true" method="POST">
	@foreach ($editFields as $vo)
	@php
	$title = $vo->getOption('title');
	$field_name = $vo->getOption('field_name');
	$value = $model->{$field_name} ?? '';
	$required = $vo->getOption('required');
	@endphp
    <div class="layui-form-item">
        <label class="layui-form-label">{{ $vo->getOption('title') }}</label>
        <div class="layui-input-block">
        	@if($vo->getOption('type') === 'text')
            <input type="text" name="{{$field_name}}" value="{{$model->{$field_name} ?? ''}}" class="layui-input" @if($required)required="required"@endif title="请输入{{$title}}" placeholder="请输入{{$title}}">
            @elseif($vo->getOption('type') === 'number')
            <input type="number" name="{{$field_name}}" value="{{$model->{$field_name} ?? ''}}" class="layui-input" @if($required)required="required"@endif title="请输入{{$title}}" placeholder="请输入{{$title}}">
            @elseif($vo->getOption('type') === 'textarea')
            <textarea name="{{$field_name}}" class="layui-textarea" @if($required)required="required"@endif placeholder="请输入{{$title}}" title="请输入{{$title}}">{{is_string($value) ? $value : new_json_encode($value)}}</textarea>
            @if($vo->getOption('tips'))<p class="help-block">{!! $vo->getOption('tips') !!}</p>@endif
            @elseif($vo->getOption('type') === 'enum')
            <select class="layui-input" @if($required)required="required"@endif name="{{$field_name}}">
                @foreach ($vo->getOption('options') as $option)
                <option @if($value == $option['id']) selected @endif value="{{ $option['id'] }}">{{ $option['text'] }}</option>
                @endforeach
            </select>
            @elseif($vo->getOption('type') === 'tag')
            <select class="layui-input {{$field_name}}-multiple-limit" @if($required)required="required"@endif multiple="multiple" name="{{$field_name}}{{ $vo->getOption('maxselection')>1 ? '[]' : '' }}">
                @foreach ($vo->getOption('options') as $option)
                <option @if($value == $option['id']) selected @endif value="{{ $option['id'] }}">{{ $option['text'] }}</option>
                @endforeach
            </select>
            <link href="{{ asset('admin/plugs/select2/select2.min.css') }}" rel="stylesheet">
            <script type="text/javascript">
            require(['jquery', 'select2'], function() {
                $(".{{$field_name}}-multiple-limit").select2({
                    placeholder: "请选择{{ $title }}",
                    tags: {{ $vo->getOption('customized') ? 'true' : 'false' }},
                    multiple: true,
                    maximumSelectionLength: {{ $vo->getOption('maxselection') }},
                });
            });
            </script>
            @elseif($vo->getOption('type') === 'switch')
            <input type="checkbox" name="{{$field_name}}" @if($required)required="required"@endif lay-skin="switch" @if($value) checked @endif lay-text="{{ implode('|', \Arr::pluck($vo->getOption('options'), 'text')) }}">
            @elseif($vo->getOption('type') === 'image')
            <input type="text" class="layui-input validate-error" @if($required)required="required"@endif onchange="$(this).nextAll('img').attr('src', this.value);" value="{{$model->{$field_name} ?? ''}}" name="{{$field_name}}" title="请上传图片或输入图片URL地址">
            <p class="help-block">{!! $vo->getOption('tips') !!}</p>
            <img style="width: 30px; height: auto;" data-tips-image="" src="{{isset($model) ? $model->{$field_name} ? : '/admin/images/image.png' : '/admin/images/image.png'}}"> <a data-file="one" data-type="ico,png,jpeg,jpg" data-field="{{$field_name}}" class="btn btn-link">上传图片</a>
            @elseif($vo->getOption('type') === 'datetime')
            <input type="text" id="{{$field_name}}"  name="{{$field_name}}" value="{{$model->{$field_name} ?? ''}}" @if($required)required="required"@endif title="请选择{{$title}}" placeholder="请选择{{$title}}" class="layui-input">
            <script type="text/javascript">
            window.laydate.render({type: 'datetime', elem: '#{{$field_name}}', trigger: 'click', format: 'yyyy-MM-dd HH:mm:ss'});
            </script>
            @elseif($vo->getOption('type') === 'date')
            <input type="text" id="{{$field_name}}"  name="{{$field_name}}" value="{{$model->{$field_name} ?? ''}}" @if($required)required="required"@endif title="请选择{{$title}}" placeholder="请选择{{$title}}" class="layui-input">
            <script type="text/javascript">
            window.laydate.render({type: 'date', elem: '#{{$field_name}}', trigger: 'click', format: 'yyyy-MM-dd'});
            </script>
            @elseif($vo->getOption('type') === 'year')
            <input type="text" id="{{$field_name}}"  name="{{$field_name}}" value="{{$model->{$field_name} ?? ''}}" @if($required)required="required"@endif title="请选择{{$title}}" placeholder="请选择{{$title}}" class="layui-input">
            <script type="text/javascript">
            window.laydate.render({type: 'year', elem: '#{{$field_name}}', trigger: 'click', format: 'yyyy'});
            </script>
            @endif
        </div>
    </div>
	@endforeach

    <div class="layui-form-item text-center">
    	<input type="hidden" name="_token" value="{{ csrf_token() }}">
        <button class="layui-btn" type="submit">保存数据</button>
        <button class="layui-btn layui-btn-danger" type="button" data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>

</form>
<script>window.form.render();</script>