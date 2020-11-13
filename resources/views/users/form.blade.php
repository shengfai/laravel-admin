@empty($user['id'])
	<form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.users.store') }}" data-auto="true" method="POST">
@else
    <form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.users.update', $user->id) }}" data-auto="true" method="PATCH">
@endif
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-block">
            <input type="text" name="name" value="{{ $user->name }}" required="required" pattern="^.{2,10}$" title="请输入用户名称" placeholder="请输入2位及以上字符用户名称" class="layui-input">
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-block">
        	@empty($user->phone)
            <input type="tel" name="phone" value="{{ $user->phone }}" required="required" title="请输入用户手机号" placeholder="请输入用户手机号" pattern="^1[3-9][0-9]{9}$" class="layui-input">
        	@else
        	<input type="tel" name="phone" value="{{ $user->phone }}" readonly disabled required="required" title="请输入用户手机号" pattern="^1[3-9][0-9]{9}$" placeholder="请输入用户手机号" class="layui-input layui-bg-gray">
        	@endif
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label">联系邮箱</label>
        <div class="layui-input-block">
        	@empty($user->email)
            <input type="text" name="email" value="{{ $user->email }}" title="请输入联系邮箱" placeholder="请输入联系邮箱" pattern="^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$" class="layui-input">
            @else
            <input type="text" name="email" value="{{ $user->email }}" readonly disabled title="请输入联系邮箱" placeholder="请输入联系邮箱" pattern="^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$" class="layui-input layui-bg-gray">
            @endif
        </div>
    </div>


    <div class="layui-form-item">
        <label class="layui-form-label">性别</label>
        <div class="layui-input-block">
            <input type="radio" name="gender" value="0" title="未知" @if($user->gender == 0) checked  @endif>
            <input type="radio" name="gender" value="1" title="男" @if($user->gender == 1) checked  @endif>
            <input type="radio" name="gender" value="2" title="女" @if($user->gender == 2) checked  @endif>
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">用户描述</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入用户描述" required="required" title="请输入用户描述" class="layui-textarea" name="remark">{{ $user['remark'] }}</textarea>
        </div>
    </div>

    <div class="hr-line-dashed"></div>

    <div class="layui-form-item text-center">
    	<input type="hidden" name="unionid" value="{{ $user->unionid }}">
        <button class="layui-btn" type="submit">保存数据</button>
        <button class="layui-btn layui-btn-danger" type="button" data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>
</form>
<script>
    window.form.render();
</script>