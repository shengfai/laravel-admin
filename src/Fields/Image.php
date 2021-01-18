<?php
namespace Shengfai\LaravelAdmin\Fields;

class Image extends File
{

    /**
     * The specific defaults for the image class.
     *
     * @var array
     */
    protected $imageDefaults = [
        'sizes' => []
    ];

    /**
     * The specific rules for the image class.
     *
     * @var array
     */
    protected $imageRules = [
        'sizes' => 'array'
    ];

    /**
     * Gets all rules.
     *
     * @return array
     */
    public function getRules()
    {
        $rules = parent::getRules();
        
        return array_merge($rules, $this->imageRules);
    }

    /**
     * Gets all default values.
     *
     * @return array
     */
    public function getDefaults()
    {
        $defaults = parent::getDefaults();
        
        return array_merge($defaults, $this->imageDefaults);
    }
}
