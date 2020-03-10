@empty($role['id'])
	<form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.roles.store') }}" data-auto="true" method="POST">
@else
    <form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.roles.update', $role->id) }}" data-auto="true" method="POST">
	<input type="hidden" name="_method" value="PUT">
@endif
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="layui-form-item">
        <label class="layui-form-label">角色名称</label>
        <div class="layui-input-block">
        	@if(isset($role))
            <input type="text" name="name" value="{{ $role->name}}" readonly required="required" title="请输入权限名称" placeholder="请输入权限名称" class="layui-input layui-bg-gray">
            @else
			<input type="text" name="name" value="" required="required" title="请输入权限名称" placeholder="请输入权限名称" class="layui-input">
            @endif
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">角色描述</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入权限描述" required="required" title="请输入权限描述" class="layui-textarea" name="remark">{{ $role->remark ?? '' }}</textarea>
        </div>
    </div>

    <div class="hr-line-dashed"></div>

    <div class="layui-form-item text-center">
        <button class="layui-btn" type="submit">保存数据</button>
        <button class="layui-btn layui-btn-danger" type="button" data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>

</form>
