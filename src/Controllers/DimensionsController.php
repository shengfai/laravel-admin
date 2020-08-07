<?php

namespace Shengfai\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Shengfai\LaravelAdmin\Models\Dimension;
use Shengfai\LaravelAdmin\Models\Module;

/**
 * 维度控制器
 * Class DimensionsController
 *
 * @author ShengFai <shengfai@qq.com>
 * @version 2020年8月6日
 */
class DimensionsController extends Controller
{

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Dimension $dimension
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function modules(Request $request, Dimension $dimension)
    {
        if ($request->isMethod('GET')) {
            
            $modules = Module::get();
            $used_module_ids = $dimension->modules()->pluck('id')->all();
            
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