<?php

namespace Shengfai\LaravelAdmin\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * 用户控制台
 * Class ConsumersController
 *
 * @package package_name
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class ConsumersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->title = '用户列表';
        
        // 查询构造器
        $queryBulider = User::where('type', User::TYPE_USER);
        
        // ID查询
        if ($request->filled('id')) {
            $this->assign('id', $request->id);
            $queryBulider->where('id', $request->id);
        }
        
        // 注册日期
        if ($request->filled('days')) {
            $date = today()->tomorrow()->addDays(-$request->days);
            $queryBulider->whereDate('created_at', '>=', $date);
        }
        
        return $this->list($queryBulider);
    }
}
