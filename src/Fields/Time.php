<?php
namespace Shengfai\LaravelAdmin\Fields;

use DateTime as DateTime;

class Time extends Field
{

    /**
     * The specific defaults for subclasses to override.
     *
     * @var array
     */
    protected $defaults = [
        'min_max' => true,
        'date_format' => 'yy-mm-dd',
        'time_format' => 'HH:mm'
    ];

    /**
     * The specific rules for subclasses to override.
     *
     * @var array
     */
    protected $rules = [
        'date_format' => 'string',
        'time_format' => 'string'
    ];

    /**
     * Get a date format from a time depending on the type of time field this is.
     *
     * @param int $time
     * @return string
     */
    public function getDateString(DateTime $time)
    {
        if ($this->getOption('type') === 'date') {
            return $time->format('Y-m-d');
        } elseif ($this->getOption('type') === 'datetime') {
            return $time->format('Y-m-d H:i:s');
        } else {
            return $time->format('H:i:s');
        }
    }
}
