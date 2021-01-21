<?php
namespace Shengfai\LaravelAdmin;

use Illuminate\Config\Repository as Config;
use Shengfai\LaravelAdmin\Services\MenuService;
use Shengfai\LaravelAdmin\Handlers\DataHandler;
use Shengfai\LaravelAdmin\Config\Factory as ConfigFactory;

class Menu
{

    /**
     * The config instance.
     *
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * The config instance.
     *
     * @var \Shengfai\LaravelAdmin\Config\Factory
     */
    protected $configFactory;

    /**
     * Create a new Menu instance.
     *
     * @param \Illuminate\Config\Repository $config
     * @param \Shengfai\LaravelAdmin\Config\Factory $config
     */
    public function __construct(Config $config, ConfigFactory $configFactory)
    {
        $this->config = $config;
        $this->configFactory = $configFactory;
    }

    /**
     * Gets the menu items indexed by their name with a value of the title.
     *
     * @param array $subMenu
     *            (used for recursion)
     *
     * @return array
     */
    public function getMenu($subMenu = null)
    {
        $menu = [];
        
        if (! $subMenu) {
            $subMenu = $this->config->get('administrator.models');
        }
        
        // iterate over the menu to build the return array of valid menu items
        foreach ($subMenu as $key => $item) {
            // if the item is a string, find its config
            if (is_string($item)) {
                // fetch the appropriate config file
                $config = $this->configFactory->make($item);
                
                // if a config object was returned and if the permission passes, add the item to the menu
                if (is_a($config, 'Shengfai\LaravelAdmin\Config\Config') && $config->getOption('permission')) {
                    $menu[$item] = $config->getOption('title');
                } elseif ($config === true) {
                    $menu[$item] = $key;
                }
            } elseif (is_array($item)) {
                $menu[$key] = $this->getMenu($item);
                
                // if the submenu is empty, unset it
                if (empty($menu[$key])) {
                    unset($menu[$key]);
                }
            }
        }
        
        return $menu;
    }

    /**
     * Gets the menu items indexed by their name with a value of the title.
     *
     * @return array
     */
    public function getMenus()
    {
        // 获取登录用户授权菜单
        $collection = (new MenuService())->getAvailableMenusByUser();
        
        // 转化为树形结构
        $menus = DataHandler::arr2tree($collection->toArray());
        
        return $menus;
    }
}
