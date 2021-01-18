<?php
namespace Shengfai\LaravelAdmin\DataTable\Columns;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\DatabaseManager as DB;
use Shengfai\LaravelAdmin\Validator;
use Shengfai\LaravelAdmin\Config\ConfigInterface;

class Column
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
     * The config instance.
     *
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $db;

    /**
     * The options array.
     *
     * @var array
     */
    protected $options;

    /**
     * The originally-supplied options array.
     *
     * @var array
     */
    protected $suppliedOptions;

    /**
     * The default configuration options.
     *
     * @var array
     */
    protected $baseDefaults = [
        'sortable' => false,
        'output' => '(:value)',
        'sort_field' => null,
        'nested' => [],
        'is_included' => false,
        'external' => false,
        'visible' => true,
        'raw_output' => false,
        'checkable' => null,
        'template' => null,
        'width' => 80,
        'align' => 'left',
        'fixed' => null
    ];

    /**
     * The specific defaults for subclasses to override.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * The base rules that all fields need to pass.
     *
     * @var array
     */
    protected $baseRules = [
        'column_name' => 'required|string',
        'title' => 'string'
    ];

    /**
     * The specific rules for subclasses to override.
     *
     * @var array
     */
    protected $rules = [];

    /**
     * The table prefix.
     *
     * @var string
     */
    protected $tablePrefix = '';

    /**
     * Create a new action Factory instance.
     *
     * @param \Shengfai\LaravelAdmin\Validator $validator
     * @param \Shengfai\LaravelAdmin\Config\ConfigInterface $config
     * @param \Illuminate\Database\DatabaseManager $db
     * @param array $options
     */
    public function __construct(Validator $validator, ConfigInterface $config, DB $db, array $options)
    {
        $this->config = $config;
        $this->validator = $validator;
        $this->db = $db;
        $this->suppliedOptions = $options;
    }

    /**
     * Validates the supplied options.
     */
    public function validateOptions()
    {
        // override the config
        $this->validator->override($this->suppliedOptions, $this->getRules());
        
        // if the validator failed, throw an exception
        if ($this->validator->fails()) {
            throw new \InvalidArgumentException("There are problems with your '" . $this->suppliedOptions['column_name'] . "' column in the " . $this->config->getOption('name') . ' model: ' . implode('. ', $this->validator->messages()->all()));
        }
    }

    /**
     * Builds the necessary fields on the object.
     */
    public function build()
    {
        $model = $this->config->getDataModel();
        $options = $this->suppliedOptions;
        $this->tablePrefix = $this->db->getTablePrefix();
        
        // set some options-based defaults
        $options['title'] = $this->validator->arrayGet($options, 'title', $options['column_name']);
        $options['sort_field'] = $this->validator->arrayGet($options, 'sort_field', $options['column_name']);
        
        // if the supplied item is an accessor, make this unsortable for the moment
        if (method_exists($model, Str::camel('get_' . $options['column_name'] . '_attribute')) && $options['column_name'] === $options['sort_field']) {
            $options['sortable'] = false;
        }
        
        // run the visible property closure if supplied
        $visible = $this->validator->arrayGet($options, 'visible');
        
        if (is_callable($visible)) {
            $options['visible'] = $visible($this->config->getDataModel()) ? true : false;
        }
        
        $this->suppliedOptions = $options;
    }

    /**
     * Gets all user options.
     *
     * @return array
     */
    public function getOptions()
    {
        // make sure the supplied options have been merged with the defaults
        if (empty($this->options)) {
            // validate the options and build them
            $this->validateOptions();
            $this->build();
            $this->options = array_merge($this->getDefaults(), $this->suppliedOptions);
        }
        
        return $this->options;
    }

    /**
     * Gets a field's option.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getOption($key)
    {
        $options = $this->getOptions();
        
        if (! array_key_exists($key, $options)) {
            throw new \InvalidArgumentException("An invalid option was searched for in the '" . $options['column_name'] . "' column");
        }
        
        return $options[$key];
    }

    /**
     * Takes a column output string and renders the column with it (replacing '(:value)' with the column's field value).
     *
     * @param $value string
     *            $value
     * @param \Illuminate\Database\Eloquent\Model $item
     *
     * @return string
     */
    public function renderOutput($value, $item = null)
    {
        $output = $this->getOption('output');
        
        // default is xss secured untill u open `raw_output` option
        // e() is laravel blade `{{ }}` for printing data
        if (! $this->getOption('raw_output')) {
            $value = is_string($value) ? e($value) : e(new_json_encode($value));
        }
        
        if (is_callable($output)) {
            return $output($value, $item);
        }
        
        return str_replace('(:value)', $value, $output);
    }

    /**
     * Gets all rules.
     *
     * @return array
     */
    public function getRules()
    {
        return array_merge($this->baseRules, $this->rules);
    }

    /**
     * Gets all default values.
     *
     * @return array
     */
    public function getDefaults()
    {
        return array_merge($this->baseDefaults, $this->defaults);
    }
}
