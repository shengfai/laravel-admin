<link href="{{ asset('admin/plugs/select2/select2.min.css') }}" rel="stylesheet">
<form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.positions.datas.push',['model'=>$model,'id'=>$positionable->id]) }}" data-auto="true" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    @isset($positions)
    <div class="layui-form-item">
        <label class="layui-form-label label-required">推荐位</label>
        <div class="layui-input-block">
            <select required="required" class="layui-select position-multiple-limit full-width" multiple="multiple" name="position_ids[]">
                @foreach ($positions as $vo)
                <option @if($positioned_ids->contains($vo->id)) selected @endif value="{{$vo->id}}">{{$vo->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    @endisset

    <div class="layui-form-item">
        <label class="layui-form-label">推荐名称</label>
        <div class="layui-input-block">
            <input type="text" name="title" value="{{ $data['title'] ?? '' }}" required="required" title="请输入推荐名称" placeholder="请输入推荐名称" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">推荐图片</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" onchange="$(this).nextAll('img').attr('src', this.value);" value="{{ $data['cover_pic'] ?? ''}}" name="cover_pic" title="请上传图片或输入图片URL地址" />
            <p class="help-block">文件最大2Mb，支持png/jpeg/jpg格式，建议上传图片的尺寸为300px300px。</p>
            <img style="width: 45px; height: auto;" data-tips-image src="{{ $data['cover_pic'] ?? '/admin/images/image.png' }}" /> <a data-file="one" data-type="png,jpeg,jpg,gif" data-field="cover_pic" class='btn btn-link'>上传图片</a>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">推荐描述</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入推荐位描述" title="请输入推荐位描述" class="layui-textarea" name="description">{{ $data['description'] ?? '' }}</textarea>
        </div>
    </div>

    <div class="layui-form-item text-center">
        <button class="layui-btn" type="submit">保存数据</button>
        <button class="layui-btn layui-btn-danger" type="button" data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>

</form>
<script type="text/javascript">
require(['jquery', '/admin/plugs/select2/select2.min.js'], function () {
	$(".position-multiple-limit").select2({
    	placeholder: "请选择推荐位",
    	tags: false,
    	multiple: true,
    	maximumSelectionLength: 15,
    });
});
window.form.render();
</script>