<?php
namespace Shengfai\LaravelAdmin\Fields;

class File extends Field
{

    /**
     * The specific defaults for subclasses to override
     *
     * @var array
     */
    protected $defaults = [
        'naming' => 'random',
        'length' => 32,
        'mimes' => false,
        'size_limit' => 2,
        'display_raw_value' => false
    ];

    /**
     * The specific rules for subclasses to override
     *
     * @var array
     */
    protected $rules = [
        'location' => 'required|string|directory',
        'naming' => 'in:keep,random',
        'length' => 'integer|min:0',
        'mimes' => 'string'
    ];

    /**
     * Builds a few basic options
     */
    public function build()
    {
        parent::build();
        
        // set the upload url depending on the type of config this is
        $url = $this->validator->getUrlInstance();
        // $route = $this->config->getType() === 'settings' ? 'admin.plugs.upfile' : 'admin.file_upload';
        $route = 'admin.plugs.upfile';
        
        // set the upload url to the proper route
        $this->suppliedOptions['upload_url'] = $url->route($route, [
            $this->config->getOption('name'),
            $this->suppliedOptions['field_name']
        ]);
    }
}
