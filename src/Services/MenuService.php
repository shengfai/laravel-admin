<?php
namespace Shengfai\LaravelAdmin\Services;

use Auth;
use App\Models\User;
use Shengfai\LaravelAdmin\Handlers\DataHandler;
use Shengfai\LaravelAdmin\Models\Menu;

/**
 * 控制台菜单服务
 * Class MenuService
 *
 * @package \Shengfai\LaravelAdmin\Services
 * @author ShengFai <shengfai@qq.com>
 */
class MenuService
{

    /**
     * 获取指定用户的授权菜单
     *
     * @param \App\Models\User $user
     * @return Illuminate\Database\Eloquent\Collection $menus
     */
    public function getAvailableMenusByUser(User $user = null)
    {
        // 当前登录用户
        if (blank($user)) {
            $user = Auth::user();
        }
        
        // 授权列表(包含空值)
        $permissions = $user->getAllPermissions()->pluck('name');
        
        // 授权菜单
        $menus = Menu::where(function ($query) use ($permissions) {
            return $query->whereIn('code', $permissions)->orWhere('permission_id', 0);
        })->usable()
            ->sorted()
            ->get();
        
        return $menus;
    }

    /**
     * 获取（可被）用作父节点列表
     *
     * @return array
     */
    public function getUsedAsParentList()
    {
        // 获取记录
        $menus = Menu::usable()->sorted('ASC')
            ->get()
            ->prepend(['name' => '顶级菜单', 'id' => '0', 'parent_id' => '-1']);
        
        // 处理格式
        $menus = DataHandler::arr2table($menus->toArray());
        
        foreach ($menus as $key => &$menu) {
            if (substr_count($menu['path'], '-') > 3) {
                unset($menus[$key]);
                continue;
            }
            if (isset($vo['parent_id'])) {
                $strCurrentPath = "-{$vo['parent_id']}-{$vo['id']}";
                if ('' !== $vo['parent_id'] && (false !== stripos("{$menu['path']}-", "{$strCurrentPath}-") || $menu['path'] === $strCurrentPath)) {
                    unset($menus[$key]);
                    continue;
                }
            }
        }
        
        return $menus;
    }
}
