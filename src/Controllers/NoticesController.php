<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

/**
 * 消息控制器
 * Class NoticesController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月29日
 */
class NoticesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->title = '消息管理';
        
        $queryBuilder = Auth::user()->notifications()->getQuery();
        
        return $this->list($queryBuilder);
    }

    /**
     * Display the specified resource.
     *
     * @param DatabaseNotification $notification
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(DatabaseNotification $notice)
    {
        // 标记消息为已读
        $notice->forceFill([
            'read_at' => now()
        ]);
        
        $notice->save();
        
        return $this->view(compact('notice'));
    }

    /**
     * 删除用户消息
     *
     * @param DatabaseNotification $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(DatabaseNotification $notification)
    {
        $notification->delete();
        
        return $this->success('消息删除成功！', '');
    }
}
