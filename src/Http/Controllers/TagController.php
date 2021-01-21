<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Models\Module;
use Shengfai\LaravelAdmin\Events\Tagged;
use Illuminate\Support\Facades\Event;

/**
 * 标签控制器
 * Class TagController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class TagController extends Controller
{

    /**
     * 设置关联标签
     *
     * @param \Illuminate\Http\Request $request
     * @param string $class
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function attach(Request $request, string $class, int $id)
    {
        // 当前模型
        $model = Module::getModelByClassName($class);
        
        // 当前对象
        $taggable = $model->namespace::find($id);
        
        if ($request->isMethod('Get')) {
            return $this->view([
                'id' => $id,
                'class' => $class,
                'model' => $model->load('dimensions'),
                'used_tags' => $taggable->tags()
                    ->pluck('id')
            ]);
        }
        
        $taggable->tags()->sync($request->tags);
        
        // 添加事件
        Event::dispatch(new Tagged($taggable));
        
        return $this->success();
    }
}
