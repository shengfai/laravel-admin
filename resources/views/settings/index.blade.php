@extends('admin::layouts.content')

@section('content')
<div class="layui-card">
    <div class="layui-card-body" style="padding: 15px;">
    <form class="layui-form" onsubmit="return false;" action="{{route('admin.settings.store')}}" data-auto="true" method="post" class='form-horizontal' style='padding-top:20px'>
    
        <div class="layui-form-item">
            <label class="layui-form-label">站点名称</label>
            <div class='layui-input-block'>
                <input type="text" name="site_name" required="required" title="请输入站点名称" placeholder="请输入站点名称" value="{{settings('site_name')}}" class="layui-input">
                <p class="help-block">网站名称，显示在浏览器标签上</p>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">关键词</label>
            <div class='layui-input-block'>
                <input type="text" name="site_keywords" required="required" title="请输入站点关键词" placeholder="请输入站点关键词" value="{{settings('site_keywords')}}" class="layui-input">
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">站点描述</label>
            <div class='layui-input-block'>
                <input type="text" name="site_description" required="required" title="请输入站点描述" placeholder="请输入站点描述" value="{{settings('site_description')}}" class="layui-input">
            </div>
        </div>
    
        <div class="layui-form-item">
            <label class="layui-form-label">版权信息</label>
            <div class='layui-input-block'>
                <input type="text" name="site_copy" required="required" title="请输入版权信息" placeholder="请输入版权信息" value="{{settings('site_copy')}}" class="layui-input">
                <p class="help-block">程序的版权信息设置，在后台登录页面显示</p>
            </div>
        </div>
    
        <div class="layui-form-item">
            <label class="layui-form-label">程序名称</label>
            <div class='layui-input-block'>
                <input type="text" name="app_name" required="required" title="请输入程序名称" placeholder="请输入程序名称" value="{{settings('app_name')}}" class="layui-input">
                <p class="help-block">当前程序名称，在后台主标题上显示</p>
            </div>
        </div>
    
        <div class="layui-form-item">
            <label class="layui-form-label">程序版本</label>
            <div class='layui-input-block'>
                <input type="text" name="app_version" required="required" title="请输入程序版本" placeholder="请输入程序版本" value="{{settings('app_version')}}" class="layui-input">
                <p class="help-block">当前程序版本号，在后台主标题上标显示</p>
            </div>
        </div>
        
    	{{--
        <div class="layui-form-item">
            <label class="layui-form-label">统计代码</label>
            <div class='layui-input-block'>
                <input type="text" name="tongji_baidu_key" maxlength="32" pattern="^[0-9a-z]{32}$" title="请输入32位百度统计应用ID" placeholder="请输入32位百度统计应用ID" value="{{settings('tongji_baidu_key')}}" class="layui-input">
                <p class="help-block">百度统计应用ID，可以在<a target="_blank" href="https://tongji.baidu.com">百度网站统计</a>申请并获取</p>
            </div>
        </div>
        --}}
    
        <div class="layui-form-item">
            <label class="layui-form-label">站点Icon</label>
            <div class='layui-input-block'>
                <img data-tips-image style="height:auto;max-height:32px;min-width:32px" src="{{settings('site_icon')}}"/>
                <input type="hidden" name="site_icon" onchange="$(this).prev('img').attr('src', this.value)"
                       value="{{settings('site_icon')}}" class="layui-input">
                <a class="btn btn-link" data-file="one" data-type="ico,jpg,jpeg,png" data-field="site_icon">上传图片</a>
                <p>请上传ico格式图片。建议上传ICO图标的尺寸为：64*64。</p>
            </div>
        </div>

		{{--
        <div class="layui-form-item">
            <label class="layui-form-label">微信登录</label>
            <div class='layui-input-block'>
                <input type="radio" name="opened_socialite_weixin" value="1" title="开启" @if(settings('opened_socialite_weixin') == 1) checked @endif>
                <input type="radio" name="opened_socialite_weixin" value="0" title="关闭"  @if(settings('opened_socialite_weixin') == 0) checked @endif}>
            </div>
        </div>
        
        <div class="layui-form-item">
            <label class="layui-form-label">支付宝登录</label>
            <div class='layui-input-block'>
                <input type="radio" name="opened_socialite_alipay" value="1" title="开启" @if(settings('opened_socialite_alipay') == 1) checked @endif>
                <input type="radio" name="opened_socialite_alipay" value="0" title="关闭"  @if(settings('opened_socialite_alipay') == 0) checked @endif}>
            </div>
        </div>
        --}}
        
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
@stop
<script>window.form.render();</script>