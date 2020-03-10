<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Http\Request;

/**
 * 日志控制器
 * Class LogsController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class LogsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Log $log
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Log $log)
    {
        $this->title = '系统日志管理';
        
        // 日志类型
        $queryBuilder = $log;
        if ($request->filled('type')) {
            $queryBuilder = $log->where('type', (int)$request->type);
        }
        $this->assign('type', $request->type);
        
        return $this->list($queryBuilder);
    }

    /**
     * Display the specified resource.
     *
     * @param Log $log
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Log $log)
    {
        return $this->view(compact('log'));
    }
}
