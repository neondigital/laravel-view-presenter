<?php

namespace Neondigital\LaravelViewPresenter;

use Config;

class ViewPresenter
{
    public $requiredData = [];

    public $autoDecoration = true;

    protected $data = [];

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
        $viewModels = Config::get('viewpresenter.view_models');

        foreach ($this->data as $key => $value) {
            // Try to find suitable decorator
            $methodName = 'decorate' . ucfirst($key);
            if (method_exists($this, $methodName)) {
                $this->data[$key] = $this->{$methodName}($value);
                continue;
            }

            // Do we have a view model to use?
            if (is_object($value)) {
                if ($viewModels[get_class($value)]) {
                    $this->data[$key] = new $viewModels[get_class($value)]($value);
                }
            }
        }

        return $this;
    }
}
