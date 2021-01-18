<?php
namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Models\Position;
use Shengfai\LaravelAdmin\Models\Positionable;

/**
 * 推荐位控制器
 * Class PositionsController.
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */

/**
 * 推荐位控制器
 * Class PositionsController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class PositionsController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Position $position
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Request $request, Position $position)
    {
        if ($request->get('action') == 'resort') {
            $queryBuilder = Positionable::ofPosition($position->id);
            return $this->list($queryBuilder);
        }
        
        $this->title = '【' . $position->name . '】内容管理';
        
        return $this->view(compact('position'));
    }
}