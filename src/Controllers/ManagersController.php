<?php

namespace Shengfai\LaravelAdmin\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasPermissions;

/**
 * 管理员控制台
 * Class ManagersController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class ManagersController extends Controller
{
    use HasPermissions;
    
    /**
     * 页面标题
     *
     * @var string
     */
    protected $title = '管理员列表';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $user)
    {
        $queryBuilder = $user->where('type', User::TYPE_ADMINISTRATOR)->with('roles')->orderBy('updated_at', 'DESC');
        
        // 手机号
        if ($request->filled('phone')) {
            $this->assign('phone', $request->phone);
            $queryBuilder->where('phone', $request->phone);
        }
        
        return $this->list($queryBuilder);
    }
}
