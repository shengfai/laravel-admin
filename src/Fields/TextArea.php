<?php
namespace Shengfai\LaravelAdmin\Fields;

class TextArea extends Field
{

    /**
     * The specific defaults for subclasses to override.
     *
     * @var array
     */
    protected $defaults = [
        'maxlength' => 1024
    ];

    /**
     * The specific rules for subclasses to override.
     *
     * @var array
     */
    protected $rules = [
        'maxlength' => 'integer|min:0'
    ];
}
