<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Models\Type;

/**
 * 类别控制器
 * Class TypesController
 *
 * @package \Shengfai\LaravelAdmin\Controllers
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年3月11日
 */
class TypesController extends Controller
{
    
    /**
     * 页面标题
     *
     * @var string
     */
    protected $title = '类型管理';

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Type $type
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]
     */
    public function index(Request $request, Type $type)
    {
        $type = $type->ofType(get_associate_model($request->model), 'model_type');
        
        // 当前分类
        $this->buildModelTypes($request->model);
        
        return $this->list($type);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $model
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create(string $model)
    {
        // 获取上级类型
        $this->buildModelTypes($model);
        
        return $this->form();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Type $type
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request, Type $type)
    {
        try {
            
            $data = $request->all();
            
            $data['user_id'] = auth()->user()->id;
            
            $data['model_type'] = get_associate_model($data['model']);
            
            $type->fill($data);
            
            $type->save();
            
            return $this->success('数据添加成功', '');
        
        } catch (\Exception $e) {
            
            return $this->error('数据添加失败', '', $e);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $model
     * @param Type $type
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(string $model, Type $type)
    {
        // 获取类别记录
        $this->assign('type', $type);
        
        // 获取上级类型
        $this->buildModelTypes($model);
        
        return $this->form();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, $id)
    {
        try {
            $type = Type::query()->where('id', $id)->first();
            
            $type->fill($request->all());
            
            $type->save();
            
            return $this->success('数据更新成功', '');
        
        } catch (\Exception $e) {
        
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(int $id)
    {
        $type = Type::query()->find($id);
        
        // 判断子元素
        if ($type->children->isNotEmpty()) {
            return $this->error('请先删除该类型下的子类型', '');
        }
        
        $type->delete();
        
        return $this->success('数据删除成功', '');
    }

    /**
     * 获取父类型
     *
     * @param string $model
     * @return void
     */
    protected function buildModelTypes(string $model)
    {
        // 模型
        switch ($model) {
            case 'feedback':
                $this->title = '反馈类别';
                break;
            default:
                break;
        }
        $this->assign('model', $model);
        
        // 获取上级类型
        $parentTypes = Type::query()->where([
            'parent_id' => 0,
            'model_type' => get_associate_model($model)
        ])->get();
        
        $this->assign('parent_types', $parentTypes);
    }
}
