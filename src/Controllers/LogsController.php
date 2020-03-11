<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->title = '系统日志';
        
        $queryBuilder = Activity::query();
        
        // 日志类型
        if ($request->filled('type')) {
            $queryBuilder->where('log_name', $request->type);
        }
        $this->assign('type', $request->type);
        
        return $this->list($queryBuilder);
    }

    /**
     * Display the specified resource.
     *
     * @param Activity $activity
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Activity $log)
    {
        return $this->view(compact('log'));
    }
}
