<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Models\Type;

/**
 * 类别控制器
 * Class TypeController
 *
 * @package \Shengfai\LaravelAdmin\Http\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class TypeController extends Controller
{

    /**
     * 页面标题
     *
     * @var string
     */
    protected $title = '类型管理';

    /**
     * Display a listing of the resource
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\View\View|\Illuminate\Contracts\View\Factory|\Shengfai\LaravelAdmin\Controllers\unknown[]|\Shengfai\LaravelAdmin\Controllers\string[]|\Shengfai\LaravelAdmin\Controllers\NULL[]|\Shengfai\LaravelAdmin\Controllers\number[]
     */
    public function index(Request $request)
    {
        $queryBuilder = Type::query()->ofModel($request->model);
        
        // 当前分类
        $this->buildModelTypes($request->model);
        
        return $this->list($queryBuilder);
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
     * Store a newly created resource in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \Shengfai\LaravelAdmin\Models\Type $type
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(Request $request, Type $type)
    {
        try {
            $type->fill($request->all());
            
            $type->user_id = auth()->user()->id;
            
            $type->save();
            
            return $this->success('数据添加成功', '');
        } catch (\Exception $e) {
            return $this->error('数据添加失败', '', $e);
        }
    }

    /**
     * Show the form for editing the specified resource
     *
     * @param string $model
     * @param \Shengfai\LaravelAdmin\Models\Type $type
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
     * Update the specified resource in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function update(Request $request, $id)
    {
        try {
            // 获取记录
            $type = Type::query()->where('id', $id)->first();
            
            // 更新指定字段
            if ($request->isMethod('Patch')) {
                tap($type)->update([
                    $request->field => $request->value
                ]);
                
                return $this->success('数据更新成功', '');
            }
            
            // 全量更新
            $type->fill($request->all());
            
            $type->save();
            
            return $this->success('数据更新成功', '');
        } catch (\Exception $e) {}
    }

    /**
     * Remove the specified resource from storage
     *
     * @param \Shengfai\LaravelAdmin\Models\Type $type
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function destroy(Type $type)
    {
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
        $class = strtolower(class_basename($model));
        
        // 模型
        $this->title = config('administrator.types_title')[$class];
        $this->assign('model', $model);
        
        // 获取上级类型
        $parentTypes = Type::query()->ofModel($model)
            ->ofParent(0)
            ->get();
        
        $this->assign('parent_types', $parentTypes);
    }
}
