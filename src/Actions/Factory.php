<?php
namespace Shengfai\LaravelAdmin\Actions;

use Shengfai\LaravelAdmin\Validator;
use Shengfai\LaravelAdmin\Config\ConfigInterface;

class Factory
{

    /**
     * The validator instance.
     *
     * @var \Shengfai\LaravelAdmin\Validator
     */
    protected $validator;

    /**
     * The config instance.
     *
     * @var \Shengfai\LaravelAdmin\Config\ConfigInterface
     */
    protected $config;

    /**
     * The actions array.
     *
     * @var array
     */
    protected $actions = [];

    /**
     * The array of actions options.
     *
     * @var array
     */
    protected $actionsOptions = [];

    /**
     * The action permissions array.
     *
     * @var array
     */
    protected $actionPermissions = [];

    /**
     * The global actions array.
     *
     * @var array
     */
    protected $globalActions = [];

    /**
     * The array of global actions options.
     *
     * @var array
     */
    protected $globalActionsOptions = [];

    /**
     * The action permissions defaults.
     *
     * @var array
     */
    protected $actionPermissionsDefaults = [
        'create' => true,
        'delete' => true,
        'update' => true,
        'view' => true
    ];

    /**
     * Create a new action Factory instance.
     *
     * @param \Shengfai\LaravelAdmin\Validator $validator
     * @param \Shengfai\LaravelAdmin\Config\ConfigInterface $config
     */
    public function __construct(Validator $validator, ConfigInterface $config)
    {
        $this->config = $config;
        $this->validator = $validator;
    }

    /**
     * Takes the model and an info array of options for the specific action.
     *
     * @param string $name
     * @param array $options
     *
     * @return \Shengfai\LaravelAdmin\Actions\Action
     */
    public function make($name, array $options)
    {
        // check the permission on this item
        $options = $this->parseDefaults($name, $options);
        
        // now we can instantiate the object
        return $this->getActionObject($options);
    }

    /**
     * Sets up the default values for the $options array.
     *
     * @param string $name
     *            //the key name for this action
     * @param array $options
     *
     * @return array
     */
    public function parseDefaults($name, $options)
    {
        $model = $this->config->getDataModel();
        
        // if the name is not a string or the options is not an array at this point, throw an error because we can't do anything with it
        if (! is_string($name) || ! is_array($options)) {
            throw new \InvalidArgumentException('A custom action in your  ' . $this->config->getOption('action_name') . ' configuration file is invalid');
        }
        
        // set the action name
        $options['action_name'] = $name;
        
        // set the permission
        $permission = $this->validator->arrayGet($options, 'permission', false);
        $options['has_permission'] = is_callable($permission) ? $permission($model) : true;
        
        // check if the messages array exists
        $options['messages'] = $this->validator->arrayGet($options, 'messages', []);
        $options['messages'] = is_array($options['messages']) ? $options['messages'] : [];
        
        return $options;
    }

    /**
     * Gets an Action object.
     *
     * @param array $options
     *
     * @return \Shengfai\LaravelAdmin\Actions\Action
     */
    public function getActionObject(array $options)
    {
        return new Action($this->validator, $this->config, $options);
    }

    /**
     * Gets an action by name.
     *
     * @param string $name
     * @param bool $global
     *            //if true, search the global actions
     *
     * @return mixed
     */
    public function getByName($name, $global = false)
    {
        $actions = $global ? $this->getGlobalActions() : $this->getActions();
        
        // loop over the actions to find our culprit
        foreach ($actions as $action) {
            if ($action->getOption('action_name') === $name) {
                return $action;
            }
        }
        
        return false;
    }

    /**
     * Gets all actions.
     *
     * @param bool $override
     *
     * @return array of Action objects
     */
    public function getActions($override = false)
    {
        // make sure we only run this once and then return the cached version
        if (empty($this->actions) || $override) {
            $this->actions = [];
            
            // loop over the actions to build the list
            foreach ($this->config->getOption('actions') as $name => $options) {
                $this->actions[] = $this->make($name, $options);
            }
        }
        
        return $this->actions;
    }

    /**
     * Gets all actions as arrays of options.
     *
     * @param bool $override
     *
     * @return array of Action options
     */
    public function getActionsOptions($override = false)
    {
        // make sure we only run this once and then return the cached version
        if (empty($this->actionsOptions) || $override) {
            $this->actionsOptions = [];
            
            // loop over the actions to build the list
            foreach ($this->getActions($override) as $name => $action) {
                $this->actionsOptions[] = $action->getOptions(true);
            }
        }
        
        return $this->actionsOptions;
    }

    /**
     * Gets all batch actions.
     *
     * @param bool $override
     *
     * @return array of Action objects
     */
    public function getBatchActions($override = false)
    {
        // make sure we only run this once and then return the cached version
        if (empty($this->batchActions) || $override) {
            $this->batchActions = [];
            
            // loop over the actions to build the list
            foreach ($this->config->getOption('batch_actions') as $name => $options) {
                $this->batchActions[] = $this->make($name, $options);
            }
        }
        
        return $this->batchActions;
    }

    /**
     * Gets batch actions as arrays of options.
     *
     * @param bool $override
     *
     * @return array of Action options
     */
    public function getBatchActionsOptions($override = false)
    {
        // make sure we only run this once and then return the cached version
        if (empty($this->batchActionsOptions) || $override) {
            $this->batchActionsOptions = [];
            
            // loop over the global actions to build the list
            foreach ($this->getBatchActions($override) as $name => $action) {
                $this->batchActionsOptions[] = $action->getOptions();
            }
        }
        
        return $this->batchActionsOptions;
    }

    /**
     * Gets all global actions.
     *
     * @param bool $override
     *
     * @return array of Action objects
     */
    public function getGlobalActions($override = false)
    {
        // make sure we only run this once and then return the cached version
        if (empty($this->globalActions) || $override) {
            $this->globalActions = [];
            
            // loop over the actions to build the list
            foreach ($this->config->getOption('global_actions') as $name => $options) {
                $this->globalActions[] = $this->make($name, $options);
            }
        }
        
        return $this->globalActions;
    }

    /**
     * Gets all actions as arrays of options.
     *
     * @param bool $override
     *
     * @return array of Action options
     */
    public function getGlobalActionsOptions($override = false)
    {
        // make sure we only run this once and then return the cached version
        if (empty($this->globalActionsOptions) || $override) {
            $this->globalActionsOptions = [];
            
            // loop over the global actions to build the list
            foreach ($this->getGlobalActions($override) as $name => $action) {
                $this->globalActionsOptions[] = $action->getOptions();
            }
        }
        
        return $this->globalActionsOptions;
    }

    /**
     * Gets all action permissions.
     *
     * @param bool $override
     *
     * @return array of Action objects
     */
    public function getActionPermissions($override = false)
    {
        // make sure we only run this once and then return the cached version
        if (empty($this->actionPermissions) || $override) {
            $this->actionPermissions = [];
            $model = $this->config->getDataModel();
            $options = $this->config->getOption('action_permissions');
            $defaults = $this->actionPermissionsDefaults;
            
            // merge the user-supplied action permissions into the defaults
            $permissions = array_merge($defaults, $options);
            
            // loop over the actions to build the list
            foreach ($permissions as $action => $callback) {
                if (is_callable($callback)) {
                    $this->actionPermissions[$action] = (bool) $callback($model);
                } else {
                    $this->actionPermissions[$action] = (bool) $callback;
                }
            }
        }
        
        return $this->actionPermissions;
    }
}
