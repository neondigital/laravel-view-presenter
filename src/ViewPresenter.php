<?php

namespace Neondigital\LaravelViewPresenter;

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
        foreach ($this->data as $key => $value) {
            // Try to find suitable decorator
            $methodName = 'decorate' . ucfirst($key);
            if (method_exists($this, $methodName)) {
                $this->data[$key] = $this->{$methodName}($value);
            }
        }

        return $this;
    }
}
