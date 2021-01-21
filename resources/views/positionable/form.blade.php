@empty($data->id)
    <form autocomplete="off" id="PositionForm" class="layui-form layui-box modal-form-box" action="{{ route('admin.datas.store') }}" data-auto="true" method="POST">
@else
    <form autocomplete="off" id="PositionForm" class="layui-form layui-box modal-form-box" action="{{ route('admin.datas.update', $data->id) }}" data-auto="true" method="POST">
    <input type="hidden" name="_method" value="PUT">
@endif
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="layui-form-item">
        <label class="layui-form-label">对象模型</label>
        <div class="layui-input-block">
            <select name="positionable_type"  required="required" class="layui-select">
                @foreach(config('administrator.available_positioned_models') as $vo)
                <option  selected value="{{ $vo['model'] }}">{{ $vo['name']  }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">对象ID</label>
        <div class="layui-input-block">
            <input type="number" name="positionable_id" value="{{ $data->positionable_id ??  '' }}" required="required" title="请输入对象ID" placeholder="请输入对象ID" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">推荐名称</label>
        <div class="layui-input-block">
            <input type="text" name="title" value="{{ $data->title ??  '' }}" required="required" title="请输入推荐名称" placeholder="请输入推荐名称" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">推荐图片</label>
        <div class="layui-input-block">
            <input type="text" class="layui-input" onchange="$(this).nextAll('img').attr('src', this.value);" value="{{ $data->original_cover_pic ??  ''}}" name="cover_pic" title="请上传图片或输入图片URL地址" />
            <p class="help-block">文件最大2Mb，支持png/jpeg/jpg格式，建议上传图片的尺寸为300px300px。</p>
            <img style="width: 45px; height: auto;" data-tips-image src="{{ isset($data) ? $data->cover_pic ? : '/admin/images/image.png' : '/admin/images/image.png' }}" /> <a data-file="one" data-type="png,jpeg,jpg,gif" data-field="cover_pic" class='btn btn-link'>上传图片</a>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">推荐描述</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入推荐位描述" title="请输入推荐位描述" class="layui-textarea" name="description">{{ $data->description ??  '' }}</textarea>
        </div>
    </div>

    <div class="layui-form-item text-center">
        <input type="hidden" name="position_id" value="{{$position->id}}">
        <button class="layui-btn" type="submit">保存数据</button>
        <button class="layui-btn layui-btn-danger" type="button" data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>

</form>
<script>window.form.render();</script>