<?php
namespace Shengfai\LaravelAdmin\Fields;

class Number extends Field
{

    /**
     * The specific defaults for subclasses to override.
     *
     * @var array
     */
    protected $defaults = [
        'min_max' => true,
        'symbol' => '',
        'decimals' => 0,
        'thousands_separator' => ',',
        'decimal_separator' => '.'
    ];

    /**
     * The specific rules for subclasses to override.
     *
     * @var array
     */
    protected $rules = [
        'symbol' => 'string',
        'decimals' => 'integer',
        'thousands_separator' => 'string',
        'decimal_separator' => 'string'
    ];

    /**
     * Parses a user-supplied number into the required SQL format with no commas for thousands and a for decimals.
     *
     * @param string $number
     * @return string
     */
    public function parseNumber($number)
    {
        return str_replace($this->getOption('decimal_separator'), '.', str_replace($this->getOption('thousands_separator'), '', $number));
    }
}
