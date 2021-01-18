<?php
namespace Shengfai\LaravelAdmin\Fields;

class Text extends Field
{

    /**
     * The specific defaults for subclasses to override.
     *
     * @var array
     */
    protected $defaults = [
        'limit' => 0,
        'height' => 100
    ];

    /**
     * The specific rules for subclasses to override.
     *
     * @var array
     */
    protected $rules = [
        'limit' => 'integer|min:0',
        'height' => 'integer|min:0'
    ];
}
