<?php
namespace Shengfai\LaravelAdmin\Fields;

class Enum extends Field
{

    /**
     * The options used for the enum field.
     *
     * @var array
     */
    protected $rules = [
        'options' => 'required|array|not_empty'
    ];

    /**
     * Builds a few basic options.
     */
    public function build()
    {
        parent::build();
        
        $options = $this->suppliedOptions;
        
        $dataOptions = ($options['options'] instanceof \Closure) ? $options['options']() : $options['options'];
        $options['options'] = [];
        
        // iterate over the options to create the options assoc array
        foreach ($dataOptions as $val => $text) {
            $options['options'][] = [
                'id' => $val,
                'text' => $text
            ];
        }
        
        $this->suppliedOptions = $options;
    }
}
