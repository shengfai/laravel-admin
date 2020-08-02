<?php

namespace Shengfai\LaravelAdmin\Fields;

use Illuminate\Database\Query\Builder as QueryBuilder;

class Tag extends Field
{
    /**
     * The options used for the enum field.
     *
     * @var array
     */
    protected $rules = [
        'customized' => 'required|boolean',
        'maxselection' => 'required|integer|min:1',
        'options' => 'array',
    ];

    /**
     * Builds a few basic options.
     */
    public function build()
    {
        parent::build();

        $options = $this->suppliedOptions;

        $dataOptions        = is_callable($options['options']) ? $options['options']() : $options['options'];
        $options['options'] = [];

        //iterate over the options to create the options assoc array
        foreach ($dataOptions as $val => $text) {
            $options['options'][] = [
                'id'   => is_numeric($val) ? $val : $text,
                'text' => $text,
            ];
        }

        $this->suppliedOptions = $options;
    }

    /**
     * Fill a model with input data.
     *
     * @param \Illuminate\Database\Eloquent\model $model
     * @param mixed                               $input
     */
    public function fillModel(&$model, $input)
    {
        $model->{$this->getOption('field_name')} = $input;
    }

    /**
     * Sets the filter options for this item.
     *
     * @param array $filter
     */
    public function setFilter($filter)
    {
        parent::setFilter($filter);

        $this->userOptions['value'] = $this->getOption('value') === '' ? null : $this->getOption('value');
    }

    /**
     * Filters a query object.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @param array                              $selects
     */
    public function filterQuery(QueryBuilder &$query, &$selects = null)
    {
        //run the parent method
        parent::filterQuery($query, $selects);

        //if there is no value, return
        if ($this->getFilterValue($this->getOption('value')) === false) {
            return;
        }

        $query->where($this->config->getDataModel()->getTable().'.'.$this->getOption('field_name'), '=', $this->getOption('value'));
    }
}
