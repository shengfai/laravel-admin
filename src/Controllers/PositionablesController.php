<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Models\Position;
use Shengfai\LaravelAdmin\Models\Positionable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * 推荐位控制器
 * Class PositionablesController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class PositionablesController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create(Position $position)
    {
        return $this->view(compact('position'));
    }

    /**
     * Show the form for push a new resource.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function push(Request $request)
    {
        try {
            // 获取推荐对象
            $target = with($request, function ($model) {

                $className = '\App\Models\\' . Str::of($model->positionable_type)->singular()->studly();

                return $className::findOrFail($model->positionable_id);
            });
            $data = collect($target->getPositionedData())->toJson();

            $this->assign('data', json_decode($data));

            // 获取推荐位
            $positions = Position::ofSort()->get();
            $this->assign('positions', $positions);

            return $this->view();
        } catch (ModelNotFoundException $e) {
            return $this->error('推荐数据不存在, 请检查后重试!');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(Request $request)
    {
        try {
            // 判断记录是否存在
            $target = with($request, function ($model) {
                return $model->positionable_type::findOrFail($model->positionable_id);
            });

            $positionable = Positionable::updateOrCreate([
                'position_id' => $request->position_id,
                'positionable_type' => $request->positionable_type,
                'positionable_id' => $request->positionable_id
            ], [
                'title' => $request->title,
                'cover_pic' => $request->cover_pic,
                'description' => $request->description
            ]);

            return $this->success('恭喜, 数据保存成功!', '');
        } catch (ModelNotFoundException $e) {
            return $this->error('推荐数据不存在, 请检查后重试!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Positionable $data)
    {
        return $this->view(compact('data'), 'admin::positionables.push');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, Positionable $data)
    {
        $data->fill($request->all());
        if ($data->update()) {
            return $this->success('恭喜, 数据保存成功!', '');
        }

        return $this->error('数据保存失败, 请稍候再试!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Positionable $data)
    {
        $data->delete();

        return $this->success('数据保存成功', '');
    }
}