<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Models\Position;

/**
 * 推荐位控制器
 * Class PositionsController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月10日
 */
class PositionsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Position $position
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]
     */
    public function index(Position $position)
    {
        $this->title = '推荐位列表';
        
        $queryBuilder = $position->withCount('datas')->sorted();
        
        return $this->list($queryBuilder);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return $this->form();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Position $position
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function store(Request $request, Position $position)
    {
        
        $position->fill($request->all());
        $position->save();
        
        return $this->success('数据保存成功', '');
    }

    /**
     * Display the specified resource.
     *
     * @param Position $position
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Position $position)
    {
        $this->title = $position->name . '内容管理';
        return $this->view(compact('position'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Position $position
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Position $position)
    {
        $this->assign('position', $position);
        
        return $this->form();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Position $position
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, Position $position)
    {
        $position->update($request->all());
        
        return $this->success('恭喜, 数据保存成功!', '');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Position $position
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(Position $position)
    {
        $position->delete();
        
        return $this->success('数据删除成功！', '');
    }

    /**
     * 绑定相应专题
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function model(Request $request)
    {
        $model = $request->input('model');
        
        $data = $model::all();
        
        return $this->success($data, '');
    }
}
