<?php
namespace Shengfai\LaravelAdmin\Fields;

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
        'options' => 'array'
    ];

    /**
     * Builds a few basic options.
     */
    public function build()
    {
        parent::build();
        
        $options = $this->suppliedOptions;
        
        $dataOptions = is_callable($options['options']) ? $options['options']() : $options['options'];
        $options['options'] = [];
        
        // iterate over the options to create the options assoc array
        foreach ($dataOptions as $val => $text) {
            $options['options'][] = [
                'id' => is_numeric($val) ? $val : $text,
                'text' => $text
            ];
        }
        
        $this->suppliedOptions = $options;
    }
}
