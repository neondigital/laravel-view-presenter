<?php

namespace Neondigital\LaravelViewPresenter;

use Config;

class ViewPresenter
{
    public $requiredData = [];

    public $autoDecoration = true;

    protected $data = [];

    protected $viewModels = [];

    public function __construct()
    {
        $this->viewModels = Config::get('viewpresenter.view_models');
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function decorate()
    {
        foreach ($this->data as $key => $value) {
            $this->data[$key] = $this->decorateValue($key, $value);
        }

        return $this;
    }

    protected function decorateValue($key, $value)
    {
        if ($value instanceof \ArrayAccess or is_array($value)) {
            $data = [];
            foreach ($value as $key2 => $value2) {
                $data[$key2] = $this->decorateValue($key2, $value2);
            }
            return $data;
        }

        // Try to find suitable decorator
        $methodName = 'decorate' . ucfirst($key);
        if (method_exists($this, $methodName)) {
            $value = $this->{$methodName}($value);
        }

        // Do we have a view model to use?
        if (is_object($value)) {
            if (isset($this->viewModels[get_class($value)])) {
                $value = new $this->viewModels[get_class($value)]($value);
            }
        }

        return $value;
    }
}
