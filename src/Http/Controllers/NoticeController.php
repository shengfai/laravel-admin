<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

/**
 * 消息控制器
 * Class NoticeController
 *
 * @package \Shengfai\LaravelAdmin\Http\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class NoticeController extends Controller
{

    /**
     * Display a listing of the resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]
     */
    public function index(Request $request)
    {
        $this->title = '消息管理';
        
        $queryBuilder = Auth::user()->notifications()->getQuery();
        
        return $this->list($queryBuilder);
    }

    /**
     * Display the specified resource
     *
     * @param \Illuminate\Notifications\DatabaseNotification $notice
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
     * @param \Illuminate\Notifications\DatabaseNotification $notification
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(DatabaseNotification $notification)
    {
        $notification->delete();
        
        return $this->success('消息删除成功！', '');
    }
}
