<?php

namespace Shengfai\LaravelAdmin\DataTable\Columns;

use Shengfai\LaravelAdmin\Validator;
use Shengfai\LaravelAdmin\Config\ConfigInterface;
use Illuminate\Database\DatabaseManager as DB;
use Shengfai\LaravelAdmin\DataTable\Columns\Relationships\BelongsTo;
use Shengfai\LaravelAdmin\DataTable\Columns\Relationships\BelongsToMany;
use Illuminate\Support\Collection;

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
     * The config instance.
     *
     * @var \Illuminate\Database\DatabaseManager
     */
    protected $db;
    
    /**
     * The column objects.
     *
     * @var array
     */
    protected $columns = [];
    
    /**
     * The column options arrays.
     *
     * @var array
     */
    protected $columnOptions = [];
    
    /**
     * The included column (used for pulling a certain range of selects from the DB).
     *
     * @var array
     */
    protected $includedColumns = [];
    
    /**
     * The relationship columns.
     *
     * @var array
     */
    protected $relatedColumns = [];
    
    /**
     * The computed columns (either an accessor or a select was supplied).
     *
     * @var array
     */
    protected $computedColumns = [];
    
    /**
     * The class name of a BelongsTo relationship.
     *
     * @var string
     */
    const BELONGS_TO = 'Illuminate\\Database\\Eloquent\\Relations\\BelongsTo';
    
    /**
     * The class name of a BelongsToMany relationship.
     *
     * @var string
     */
    const BELONGS_TO_MANY = 'Illuminate\\Database\\Eloquent\\Relations\\BelongsToMany';
    
    /**
     * The class name of a HasMany relationship.
     *
     * @var string
     */
    const HAS_MANY = 'Illuminate\\Database\\Eloquent\\Relations\\HasMany';
    
    /**
     * The class name of a HasOne relationship.
     *
     * @var string
     */
    const HAS_ONE = 'Illuminate\\Database\\Eloquent\\Relations\\HasOne';

    /**
     * Create a new action Factory instance.
     *
     * @param \Shengfai\LaravelAdmin\Validator $validator
     * @param \Shengfai\LaravelAdmin\Config\ConfigInterface $config
     * @param \Illuminate\Database\DatabaseManager $db
     */
    public function __construct(Validator $validator, ConfigInterface $config, DB $db)
    {
        // set the config, and then validate it
        $this->config = $config;
        $this->validator = $validator;
        $this->db = $db;
    }

    /**
     * Fetches a Column instance from the supplied options.
     *
     * @param array $options
     *
     * @return \Shengfai\LaravelAdmin\DataTable\Columns\Column
     */
    public function make($options)
    {
        return $this->getColumnObject($options);
    }

    /**
     * Creates the Column instance.
     *
     * @param array $options
     *
     * @return \Shengfai\LaravelAdmin\DataTable\Columns\Column
     */
    public function getColumnObject($options)
    {
        $class = $this->getColumnClassName($options);
        
        return new $class($this->validator, $this->config, $this->db, $options);
    }

    /**
     * Gets the column class name depending on whether or not it's a relationship and what type of relationship it is.
     *
     * @param array $options
     *
     * @return string
     */
    public function getColumnClassName($options)
    {
        $model = $this->config->getDataModel();
        $namespace = __NAMESPACE__ . '\\';
        
        // if the relationship is set
        if ($method = $this->validator->arrayGet($options, 'relationship')) {
            if (method_exists($model, $method)) {
                $relationship = $model->{$method}();
                
                if (is_a($relationship, self::BELONGS_TO_MANY)) {
                    return $namespace . 'Relationships\BelongsToMany';
                } elseif (is_a($relationship, self::HAS_ONE) || is_a($relationship, self::HAS_MANY)) {
                    return $namespace . 'Relationships\HasOneOrMany';
                }
            }
            
            // assume it's a nested relationship
            return $namespace . 'Relationships\BelongsTo';
        }
        
        return $namespace . 'Column';
    }

    /**
     * Parses an options array and a string name and returns an options array with the column_name option set.
     *
     * @param mixed $name
     * @param mixed $options
     *
     * @return array
     */
    public function parseOptions($name, $options)
    {
        if (is_string($options)) {
            $name = $options;
            $options = [];
        }
        
        // if the name is not a string or the options is not an array at this point, throw an error because we can't do
        // anything with it
        if (!is_string($name) || !is_array($options)) {
            throw new \InvalidArgumentException('One of the columns in your ' . $this->config->getOption('name') .
                     ' model configuration file is invalid');
        }
        
        // in any case, make sure the 'column_name' option is set
        $options['column_name'] = $name;
        
        return $options;
    }

    /**
     * Gets the column objects as an integer-indexed array.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getColumnsOfGrip()
    {
        $columns = $this->getVisibleColumns();
        
        $columns = $columns->transform(function ($item) {
            $column = [
                'field' => $item['column_name'],
                'title' => $item['title'],
                'sort' => $item['sortable'],
                'checkable' => $item['checkable'],
                'templet' => $item['template'] ?? '<div>{{d.' . $item['column_name'] . '.rendered}}</div>'
            ];
            if ($item['width'] > 0) $column['width'] = $item['width'];
            
            return $column;
        });
        
        $columns = $this->openCheckableColumn($columns);
        
        return $columns;
    }

    /**
     * Gets the column objects.
     *
     * @return array
     */
    public function getColumns()
    {
        // make sure we only run this once and then return the cached version
        if (empty($this->columns)) {
            foreach ($this->config->getOption('columns') as $name => $options) {
                // if only a string value was supplied, may sure to turn it into an array
                $object = $this->make($this->parseOptions($name, $options));
                $this->columns[$object->getOption('column_name')] = $object;
            }
        }
        
        return $this->columns;
    }

    /**
     * Gets the column objects as an integer-indexed array.
     *
     * @return array
     */
    public function getColumnOptions()
    {
        // make sure we only run this once and then return the cached version
        if (empty($this->columnOptions)) {
            foreach ($this->getColumns() as $column) {
                $this->columnOptions[] = $column->getOptions();
            }
        }
        
        return $this->columnOptions;
    }

    /**
     * Gets the columns that are on the model's table (i.e.
     * not related or computed).
     *
     * @param array $fields
     *
     * @return array
     */
    public function getIncludedColumns(array $fields)
    {
        // make sure we only run this once and then return the cached version
        if (empty($this->includedColumns)) {
            $model = $this->config->getDataModel();
            
            foreach ($this->getColumns() as $column) {
                if ($column->getOption('is_related')) {
                    $this->includedColumns = array_merge($this->includedColumns, $column->getIncludedColumn());
                } elseif (!$column->getOption('is_computed')) {
                    $this->includedColumns[$column->getOption('column_name')] = $model->getTable() . '.' .
                             $column->getOption('column_name');
                }
            }
            
            // make sure the table key is included
            if (!$this->validator->arrayGet($this->includedColumns, $model->getKeyName())) {
                $this->includedColumns[$model->getKeyName()] = $model->getTable() . '.' . $model->getKeyName();
            }
            
            // make sure any belongs_to fields that aren't on the columns list are included
            foreach ($fields as $field) {
                if (is_a($field, 'Shengfai\\LaravelAdmin\\Fields\\Relationships\\BelongsTo')) {
                    $this->includedColumns[$field->getOption('foreign_key')] = $model->getTable() . '.' .
                             $field->getOption('foreign_key');
                }
            }
        }
        
        return $this->includedColumns;
    }

    /**
     * Gets the columns that are relationship columns.
     *
     * @return array
     */
    public function getRelatedColumns()
    {
        // make sure we only run this once and then return the cached version
        if (empty($this->relatedColumns)) {
            foreach ($this->getColumns() as $column) {
                if ($column->getOption('is_related')) {
                    $this->relatedColumns[$column->getOption('column_name')] = $column->getOption('column_name');
                }
            }
        }
        
        return $this->relatedColumns;
    }

    /**
     * Gets the columns that are computed.
     *
     * @return array
     */
    public function getComputedColumns()
    {
        // make sure we only run this once and then return the cached version
        if (empty($this->computedColumns)) {
            foreach ($this->getColumns() as $column) {
                if (!$column->getOption('is_related') && $column->getOption('is_computed')) {
                    $this->computedColumns[$column->getOption('column_name')] = $column->getOption('column_name');
                }
            }
        }
        
        return $this->computedColumns;
    }

    /**
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getVisibleColumns()
    {
        return collect($this->getColumnOptions())->filter(function ($item) {
            return $item['visible'];
        });
    }

    /**
     *
     * @param Collection $columns
     * @return \Illuminate\Support\Collection
     */
    protected function openCheckableColumn(Collection $columns)
    {
        if ($columns->whereNotNull('checkable')->isEmpty()) {
            return $columns;
        }
        
        return $columns->prepend([
            'checkbox' => true
        ]);
    }
}
