<form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.users.update', $user['id']) }}" data-auto="true" method="PATCH">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="layui-form-item">
        <label class="layui-form-label">用户名称</label>
        <div class="layui-input-block">
            <input type="text" name="name" readonly disabled value="{{ $user->name }}（{{ $user->phone }}）" required="required" title="请输入用户名称" placeholder="请输入用户名称" class="layui-input layui-bg-gray">
        </div>
    </div>

	<div class="layui-form-item">
        <label class="layui-form-label">新的密码</label>
        <div class="layui-input-block">
            <input type="password" name="password" pattern="^\S{1,}$" required="required" title="请输入新密码" placeholder="请输入新的密码" class="layui-input">
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label">重复密码</label>
        <div class="layui-input-block">
            <input type="password" name="password_confirmation" pattern="^\S{1,}$" required="required" title="请重复输入新的密码" placeholder="请重复输入新的密码" class="layui-input">
        </div>
    </div>

    <div class="hr-line-dashed"></div>

    <div class="layui-form-item text-center">
        <button class="layui-btn" type="submit">保存数据</button>
        <button class="layui-btn layui-btn-danger" type="button" data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>

</form>
