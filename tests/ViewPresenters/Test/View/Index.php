<?php

namespace Neondigital\LaravelViewPresenter\Tests\ViewPresenters\Test\View;

use Neondigital\LaravelViewPresenter\ViewPresenter;

class Index extends ViewPresenter
{
    public function decorate()
    {
        foreach ($this->data as $key => $value) {
            $this->data[$key] .= ' is Awesome!';
        }

        return parent::decorate();
    }

    protected function decorateTest($data)
    {
        return strtoupper($data);
    }
}
