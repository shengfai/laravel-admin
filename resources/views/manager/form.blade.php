@empty($manager['id'])
	<form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.managers.store') }}" data-auto="true" method="POST">
@else
    <form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.managers.update', $manager->id) }}" data-auto="true" method="PATCH">
@endif
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="layui-form-item">
        <label class="layui-form-label">用户名</label>
        <div class="layui-input-block">
            <input type="text" name="name" value="{{ $manager->name ?? '' }}" required="required" pattern="^.{2,10}$" title="请输入用户名称" placeholder="请输入2位及以上字符用户名称" class="layui-input">
        </div>
    </div>
    
    <div class="layui-form-item">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-block">
        	@empty($manager->phone)
            <input type="tel" name="phone" value="" required="required" title="请输入用户手机号" placeholder="请输入用户手机号" pattern="^1[3-9][0-9]{9}$" class="layui-input">
            <p class="help-block">注意：若该手机号存在，将提升为管理账号（请仔细确认该号码）。</p>
        	@else
        	<input type="tel" name="phone" value="{{ $manager->phone }}" readonly disabled required="required" title="请输入用户手机号" pattern="^1[3-9][0-9]{9}$" placeholder="请输入用户手机号" class="layui-input layui-bg-gray">
        	@endif
        </div>
    </div>
    
    @if(!isset($manager))
    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-block">
            <input type="password" name="password" value="" required="required" title="请输入用户初始密码" placeholder="请输入用户初始密码" class="layui-input">
        </div>
    </div>
    @endif
    
    <div class="layui-form-item">
        <label class="layui-form-label">联系邮箱</label>
        <div class="layui-input-block">
        	@empty($manager->email)
            <input type="text" name="email" value="" title="请输入联系邮箱" required="required" placeholder="请输入联系邮箱" pattern="^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$" class="layui-input">
            @else
            <input type="text" name="email" value="{{ $manager->email }}" readonly disabled title="请输入联系邮箱" placeholder="请输入联系邮箱" pattern="^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$" class="layui-input layui-bg-gray">
            @endif
        </div>
    </div>

	@if (!isset($manager) && auth()->user()->hasRole('Founder'))
    <div class="layui-form-item">
        <label class="layui-form-label">所属角色</label>
        <div class="layui-input-block">
            @foreach ($roles as $key => $role)
            <label class="think-checkbox">
                @if (isset($role['checked']) && $role['checked'])
                <input type="checkbox" checked name="roles[]" value="{{ $role['id'] }}" lay-ignore> {{ $role['name'] }}
                @else
                <input type="checkbox" name="roles[]" value="{{ $role['id'] }}" lay-ignore> {{ $role['name'] }}
                @endif
            </label>
            @endforeach
            @empty($roles)<span class="color-desc" style="line-height:36px">未配置角色</span>@endif
        </div>
    </div>
    @endif
    
    <div class="layui-form-item">
        <label class="layui-form-label">用户描述</label>
        <div class="layui-input-block">
            <textarea placeholder="请输入用户描述" title="请输入用户描述" class="layui-textarea" name="remark">{{ $manager->remark ?? '' }}</textarea>
        </div>
    </div>

    <div class="hr-line-dashed"></div>

    <div class="layui-form-item text-center">
    	<input type="hidden" name="unionid" value="{{ $manager->unionid ?? '' }}">
        <button class="layui-btn" type="submit">保存数据</button>
        <button class="layui-btn layui-btn-danger" type="button" data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>
</form>
<script>
    window.form.render();
</script>