<form autocomplete="off" class="layui-form layui-box modal-form-box" action="{{ route('admin.managers.update', $manager->id) }}" data-auto="true" method="POST">
	<input type="hidden" name="_method" value="PUT">
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="layui-form-item">
        <label class="layui-form-label">用户名称</label>
        <div class="layui-input-block">
            <input type="text" name="name" readonly disabled value="{{ $manager->name }}" required="required" title="请输入用户名称" placeholder="请输入用户名称" class="layui-input layui-bg-gray">
        </div>
    </div>

	@if (auth()->user()->hasRole('Founder'))
    <div class="layui-form-item">
        <label class="layui-form-label">所属角色</label>
        <div class="layui-input-block">
            @foreach ($roles as $key => $role)
            <label class="think-checkbox">
                @if ($role['checked'])
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

    <div class="hr-line-dashed"></div>

    <div class="layui-form-item text-center">
        <button class="layui-btn" type="submit">保存数据</button>
        <button class="layui-btn layui-btn-danger" type="button" data-confirm="确定要取消编辑吗？" data-close>取消编辑</button>
    </div>

</form>
