<?php
namespace Shengfai\LaravelAdmin\Fields;

class Boolean extends Field
{

    /**
     * The value
     *
     * @var bool
     */
    public $value = false;

    /**
     * Builds a few basic options
     */
    public function build()
    {
        parent::build();
        
        $value = $this->validator->arrayGet($this->suppliedOptions, 'value', true);
        
        // we need to set the value to 'false' when it is falsey so it plays nicely with select2
        if (! $value && $value !== '') {
            $this->suppliedOptions['value'] = 'false';
        }
    }
}
