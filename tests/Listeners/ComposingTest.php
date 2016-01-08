<?php

namespace Neondigital\LaravelViewPresenter\Tests\Listeners;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Config\Repository as Config;
use Mockery;
use Neondigital\LaravelViewPresenter\Listeners\Composing;
use Neondigital\LaravelViewPresenter\Tests\TestCase;

class ComposingTest extends TestCase
{
    /**
     * @var Mockery\Mock
     */
    protected $viewMock;

    /**
     * @var Composing
     */
    protected $composingListener;

    protected function setUp()
    {
        $this->setUpMocks();

        $this->composingListener = new Composing($this->configMock);

        parent::setUp();
    }

    protected function setUpMocks()
    {
        $this->configMock = Mockery::mock(Config::class);
        $this->viewMock = Mockery::mock(View::class);
    }

    /**
     * @test
     */
    public function testItCanBeConstructed()
    {
        $this->assertInstanceOf(Composing::class, $this->composingListener);
    }

    /**
     * @test
     */
    public function testItPerformsAHandleMethod()
    {
        $this->configMock->shouldReceive('get')->once()->andReturn("Neondigital\\LaravelViewPresenter\\Tests\\ViewPresenters\\");

        $this->viewMock->shouldReceive('name')->once()->andReturn('test.view.index');
        $this->viewMock->shouldReceive('getData')->andReturn(['test' => 'Glenn']);
        $this->viewMock->shouldReceive('with');

        $this->composingListener->handle($this->viewMock);
    }
}
