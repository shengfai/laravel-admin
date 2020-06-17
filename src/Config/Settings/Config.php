<?php

namespace Shengfai\LaravelAdmin\Config\Settings;

use Shengfai\LaravelAdmin\Config\Config as ConfigBase;
use Shengfai\LaravelAdmin\Config\ConfigInterface;
use Shengfai\LaravelAdmin\Exceptions\ValidationFailedException;

class Config extends ConfigBase implements ConfigInterface
{
    /**
     * The config type.
     *
     * @var string
     */
    protected $type = 'settings';
    
    /**
     * The default configuration options.
     *
     * @var array
     */
    protected $defaults = [
        'permission' => true,
        'before_save' => null,
        'actions' => [],
        'rules' => [],
        'messages' => [],
        'storage_path' => null
    ];
    
    /**
     * An array with the settings data.
     *
     * @var array
     */
    protected $data;
    
    /**
     * The rules array.
     *
     * @var array
     */
    protected $rules = [
        'title' => 'required|string',
        'edit_fields' => 'required|array|not_empty',
        'permission' => 'callable',
        'before_save' => 'callable',
        'actions' => 'array',
        'rules' => 'array',
        'messages' => 'array',
        'storage_path' => 'directory'
    ];

    /**
     * Fetches the data model for a config.
     *
     * @return array
     */
    public function getDataModel()
    {
        return $this->data;
    }

    /**
     * Sets the data model for a config.
     *
     * @param array $data
     */
    public function setDataModel($data)
    {
        $this->data = $data;
    }

    /**
     * Fetches the data for this settings config and stores it in the data property.
     *
     * @param array $fields
     */
    public function fetchData(array $fields)
    {
        // set up the blank data
        $data = [];
        
        foreach ($fields as $name => $field) {
            $data[$name] = null;
        }
        
        // populate the data from the file
        $this->setDataModel($this->populateData($data));
    }

    /**
     * Populates the data array if it can find the settings file.
     *
     * @param array $data
     *
     * @return array
     */
    public function populateData(array $data)
    {
        $saveData = \Option::all();
        
        // run through the saveData and update the associated fields that we populated from the edit fields
        foreach ($saveData as $field => $value) {
            if (array_key_exists($field, $data)) {
                $data[$field] = $value;
            }
        }
        
        return $data;
    }

    /**
     * Attempts to save a settings page.
     *
     * @param \Illuminate\Http\Request $input
     * @param array $fields
     *
     * @return mixed //string if error, true if success
     */
    public function save(\Illuminate\Http\Request $input, array $fields)
    {
        $data = [];
        $rules = $this->getOption('rules');
        
        // iterate over the edit fields to only fetch the important items
        foreach ($fields as $name => $field) {
            if ($field->getOption('editable')) {
                $data[$name] = $input->get($name);
                
                // make sure the bool field is set correctly
                if ($field->getOption('type') === 'bool') {
                    $data[$name] = $data[$name] === 'true' || $data[$name] === '1' ? 1 : 0;
                }
            } else {
                // unset uneditable fields rules
                unset($rules[$name]);
            }
        }
        
        // validate the model
        $this->validateData($data, $rules, $this->getOption('messages'));
        
        // run the beforeSave function if provided
        $beforeSave = $this->runBeforeSave($data);
        
        // Save data
        collect($data)->each(function ($value, $name) {
            settings($name, $value);
        });
        
        return true;
    }

    /**
     * Runs the before save method with the supplied data.
     *
     * @param array $data
     * @param mixed
     */
    public function runBeforeSave(array &$data)
    {
        $beforeSave = $this->getOption('before_save');
        
        if (is_callable($beforeSave)) {
            $bs = $beforeSave($data);
            
            // if a string is returned, assume it's an error and kick it back
            if (is_string($bs)) {
                throw new ValidationFailedException(ErrorCodes::HTTP_BAD_REQUEST, $bs);
            }
        }
        
        return true;
    }
}
