<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Models\Position;
use Shengfai\LaravelAdmin\Models\Positionable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * 推荐位控制器
 * Class PositionableController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class PositionableController extends Controller
{

    /**
     * Show the form for creating a new resource
     *
     * @param \Shengfai\LaravelAdmin\Models\Position $position
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Position $position)
    {
        return $this->view(compact('position'));
    }

    /**
     * Show the form for push a new resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function push(Request $request)
    {
        try {
            
            // 获取推荐对象
            $positionable = with($request, function ($target) {
                $className = '\App\Models\\' . Str::of($target->model)->singular()->studly();
                return $className::findOrFail($target->id);
            });
            
            // 推送界面
            if ($request->isMethod('Get')) {
                
                // 获取推荐位
                $positions = Position::ofSort()->get();
                
                // 已推荐位置
                $positioned_ids = $positionable->positionables()->pluck('position_id');
                
                return $this->view([
                    'positions' => $positions,
                    'model' => $request->model,
                    'positionable' => $positionable,
                    'positioned_ids' => $positioned_ids,
                    'data' => $positionable->getPositionedData()
                ]);
            }
            
            // 推送至推荐位
            $positionables = collect($request->position_ids)->mapWithKeys(function ($posid) use ($request) {
                return [
                    $posid => [
                        'title' => $request->title,
                        'cover_pic' => $request->cover_pic,
                        'description' => $request->description,
                        'updated_at' => now()
                    ]
                ];
            });
            
            $positionable->positions()->sync($positionables);
            
            return $this->success('恭喜, 数据保存成功!', '');
        } catch (ModelNotFoundException $e) {
            return $this->error('推荐数据不存在, 请检查后重试!');
        }
    }

    /**
     * Remove the specified resource from storage
     *
     * @param \Shengfai\LaravelAdmin\Models\Positionable $data
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(Positionable $data)
    {
        $data->delete();
        
        return $this->success('数据保存成功', '');
    }
}