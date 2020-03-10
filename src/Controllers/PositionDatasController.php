<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Models\Position;
use Shengfai\LaravelAdmin\Models\PositionData;
use Shengfai\LaravelAdmin\Contracts\Conventions;

/**
 * 推荐位控制器
 * Class PositionDatasController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class PositionDatasController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Position $position
     * @param PositionData $positionData
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]
     */
    public function index(Position $position, PositionData $positionData)
    {
        $this->title = $position->name;
        
        // 提示信息
        $alert = [
            'type' => Conventions::VIEW_ALERT_INFO,
            'content' => ''
        ];
        $this->assign('alert', $alert);
        
        // 关联模板主题
        $this->assign('position', $position);
        $queryBuilder = $positionData->where('position_id', '=', $position->id);
        
        return $this->list($queryBuilder);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Position $position
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create(Position $position)
    {
        return $this->view(compact('position'));
    }

    /**
     * Show the form for push a new resource.
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function push(Request $request)
    {
        // 获取推荐对象
        $target = get_target_object($request->target_type, $request->target_id);
        $data = collect($target->getPushDataForPosition())->toJson();
        
        $this->assign('data', json_decode($data));
        
        // 获取推荐位
        $positions = Position::ofSort()->get();
        $this->assign('positions', $positions);
        
        return $this->view();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(Request $request)
    {
        $positionData = PositionData::updateOrCreate([
            'position_id' => $request->position_id,
            'target_type' => $request->target_type,
            'target_id' => $request->target_id
        ], [
            'title' => $request->title,
            'cover_pic' => $request->cover_pic,
            'description' => $request->description
        ]);
        
        if ($positionData) {
            return $this->success('恭喜, 数据保存成功!', '');
        }
        return $this->error('数据保存失败, 请稍候再试!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PositionData $data
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(PositionData $data)
    {
        $this->assign('data', $data);
        $this->view = 'admin.positiondatas.push';
        return $this->view();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param PositionData $data
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, PositionData $data)
    {
        $data->fill($request->all());
        if ($data->update()) {
            return $this->success('恭喜, 数据保存成功!', '');
        }
        return $this->error('数据保存失败, 请稍候再试!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PositionData $data
     */
    public function destroy(PositionData $data)
    {
        $data->delete();
        return $this->success('数据保存成功', '');
    }
}
