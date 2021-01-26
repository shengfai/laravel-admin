<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Shengfai\LaravelAdmin\Contracts\Conventions;
use Shengfai\LaravelAdmin\Handlers\ActivityHandler;

/**
 * 管理员控制台
 * Class ManagerController
 *
 * @package \Shengfai\LaravelAdmin\Http\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class ManagerController extends Controller
{
    use HasPermissions;

    /**
     * 页面标题
     *
     * @var string
     */
    protected $title = '管理员列表';

    /**
     * Display a listing of the resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $queryBuilder = User::query()->whereType(User::TYPE_ADMINISTRATOR)
            ->with('roles')
            ->orderBy('updated_at', 'DESC');
        
        // 手机号
        if ($request->filled('phone')) {
            $this->assign('phone', $request->phone);
            $queryBuilder->where('phone', $request->phone);
        }
        
        if ($request->wantsJson()) {
            $data = $queryBuilder->paginate($this->perPage);
            return response()->json($data);
        }
        
        return $this->list($queryBuilder);
    }

    /**
     * Show the form for creating a new resource
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        // 获取站点所有角色
        $roles = Role::where('guard_name', Auth::getDefaultDriver())->get();
        
        return $this->view([
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(Request $request)
    {
        // 查找/新建
        $manager = User::firstOrNew([
            'phone' => $request->phone
        ]);
        
        $manager->fill([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'remark' => $request->remark,
            'type' => User::TYPE_ADMINISTRATOR,
            'registered_channel' => Conventions::REGISTERED_CHANNEL_CONSOLE
        ]);
        
        $manager->save();
        
        // 更新绑定角色
        if ($request->roles) {
            $manager->assignRole($request->roles);
        }
        
        ActivityHandler::console()->performedOn($manager)->log('授权用户');
        
        return $this->success('数据保存成功', '');
    }

    /**
     * Display the specified resource
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $manager
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Request $request, User $manager)
    {
        if (! $manager->exists) {
            $manager = $this->user();
        }
        
        if ($request->wantsJson()) {
            $manager->load([
                'roles.permissions'
            ]);
            return $this->response($manager->toArray());
        }
        
        return $this->view([
            'user' => $manager
        ]);
    }

    /**
     * Show the form for editing the specified resource
     *
     * @param \App\Models\User $manager
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(User $manager)
    {
        // 获取站点所有角色
        $roles = Role::where('guard_name', Auth::getDefaultDriver())->get();
        
        // 当前用户绑定的角色
        $bindRoles = $manager->getRoleNames()->toArray();
        
        foreach ($roles as &$val) {
            $val['checked'] = in_array($val['name'], $bindRoles);
        }
        
        return $this->view(compact('manager', 'roles'));
    }

    /**
     * Update the specified resource in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $manager
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, User $manager)
    {
        // 更新角色
        if ($request->roles) {
            
            // 绑定角色
            $manager->syncRoles($request->roles);
            
            ActivityHandler::console()->performedOn($manager)->log('用户授权');
        }
        
        // 更新用户
        if ($manager->update($request->all())) {
            return $this->success('恭喜, 数据保存成功!', '');
        }
        
        return $this->error('数据保存失败, 请稍候再试!');
    }

    /**
     * Remove the specified resource from storage
     *
     * @param \App\Models\User $manager
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $manager)
    {
        // 删除绑定角色
        $bindRoles = $manager->getRoleNames();
        
        foreach ($bindRoles as $role) {
            $manager->removeRole($role);
        }
        
        // 更新用户类型
        $manager->type = User::TYPE_USER;
        $manager->save();
        
        return $this->success();
    }

    /**
     * 绑定角色
     *
     * @param \App\Models\User $manager
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function authorizations(User $manager)
    {
        // 获取站点所有角色
        $roles = Role::where('guard_name', Auth::getDefaultDriver())->get();
        
        // 当前用户绑定的角色
        $bindRoles = $manager->getRoleNames()->toArray();
        foreach ($roles as &$val) {
            $val['checked'] = in_array($val['name'], $bindRoles);
        }
        
        return $this->view([
            'manager' => $manager,
            'roles' => $roles
        ]);
    }
}
