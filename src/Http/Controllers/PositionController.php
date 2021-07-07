<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Models\Position;
use Shengfai\LaravelAdmin\Models\Positionable;

/**
 * 推荐位控制器
 * Class PositionController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class PositionController extends Controller
{

    /**
     * Display the specified resource
     *
     * @param \Illuminate\Http\Request $request
     * @param \Shengfai\LaravelAdmin\Models\Position $position
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(Request $request, Position $position)
    {
        $this->title = '【' . $position->name . '】内容管理';
        
        if ($request->wantsJson()) {
            $data = Positionable::query()->ofPosid($position->id)->paginate($this->perPage);
            return response()->json($data);
        }
        
        $this->view = 'admin::position.show';
        $this->assign('position', $position);
        
        $queryBuilder = Positionable::ofPosid($position->id);
        return $this->list($queryBuilder);
    }
}