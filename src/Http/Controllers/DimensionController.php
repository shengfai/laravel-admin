<?php
namespace Shengfai\LaravelAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Models\Module;
use Shengfai\LaravelAdmin\Models\Dimension;

/**
 * 维度控制器
 * Class DimensionController
 *
 * @package \Shengfai\LaravelAdmin\Http\Controllers
 * @author ShengFai <shengfai@qq.com>
 */
class DimensionController extends Controller
{

    /**
     * Update the specified resource in storage
     *
     * @param \Illuminate\Http\Request $request
     * @param \Shengfai\LaravelAdmin\Models\Dimension $dimension
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function modules(Request $request, Dimension $dimension)
    {
        if ($request->isMethod('GET')) {
            
            $modules = Module::get();
            $used_module_ids = $dimension->modules()
                ->pluck('id')
                ->all();
            
            return $this->view([
                'modules' => $modules,
                'dimension' => $dimension,
                'used_module_ids' => $used_module_ids
            ]);
        }
        
        $dimension->modules()->sync($request->get('modules'));
        
        return $this->success();
    }
}