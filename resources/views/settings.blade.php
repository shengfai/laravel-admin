@extends('admin::layouts.content')

@section('content')
<div class="layui-card">
    <div class="layui-card-body" style="padding: 15px;">
    <form class="layui-form" onsubmit="return false;" action="{{route('admin.settings.save', $settingsName)}}" data-auto="true" method="post" class='form-horizontal' style='padding-top:20px'>
    
    	@foreach ($editFields as $vo)
        <div class="layui-form-item">
            <label class="layui-form-label">{{$vo->getOption('title')}}</label>
            <div class='layui-input-block'>
            	@if($vo->getOption('type') === 'textarea')
	            <textarea name="{{$vo->getOption('field_name')}}" class="layui-textarea" required="required" placeholder="请输入{{$vo->getOption('title')}}" title="请输入{{$vo->getOption('title')}}">{{\Option::get($vo->getOption('field_name'))}}</textarea>
            	@elseif($vo->getOption('type') === 'image')
            	<img data-tips-image style="height:auto;max-height:32px;min-width:32px" src="{{\Option::get($vo->getOption('field_name'))}}"/>
                <input type="hidden" name="{{$vo->getOption('field_name')}}" onchange="$(this).prev('img').attr('src', this.value)"
                       value="{{\Option::get($vo->getOption('field_name'))}}" class="layui-input">
                <a class="btn btn-link" data-file="one" data-type="ico,jpg,jpeg,png" data-field="{{$vo->getOption('field_name')}}">上传图片</a>
                <p class="help-block">{!! $vo->getOption('tips') !!}</p>
            	@else
            	<input type="text" name="{{$vo->getOption('field_name')}}" required="required" title="请输入{{$vo->getOption('title')}}" placeholder="请输入{{$vo->getOption('title')}}" value="{{\Option::get($vo->getOption('field_name'))}}" class="layui-input">
                <p class="help-block">{!! $vo->getOption('tips') !!}</p>
            	@endif
            </div>
        </div>
        @endforeach
        
        <div class="hr-line-dashed"></div>
        <div class="layui-form-item">
            <div class="layui-footer">
            	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button class="layui-btn" type="submit">保存配置</button>
            </div>
        </div>
    
    </form>
    </div>
</div>
@endsection
<script>window.form.render();</script>