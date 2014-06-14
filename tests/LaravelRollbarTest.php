<?php

class LaravelRollbarTest extends Orchestra\Testbench\TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    protected function getPackageProviders()
    {
        return ['Atijust\LaravelRollbar\LaravelRollbarServiceProvider'];
    }

    public function testConfig()
    {
        $accessToken = str_random(32);

        Config::set('laravel-rollbar::config.access_token', $accessToken);

        $rollbar = $this->app->make('rollbar');

        $this->assertSame($accessToken, $rollbar->access_token);
        $this->assertSame($this->app->environment(), $rollbar->environment);
        $this->assertSame(base_path(), $rollbar->root);
    }

    public function testLogMessage()
    {
        $message = 'test';

        $notifier = Mockery::mock('RollbarNotifier');
        $notifier->shouldReceive('report_message')->once()->with($message, 'info', []);
        $this->app->instance('rollbar', $notifier);

        Config::set('laravel-rollbar::config.excluded_levels', ['debug']);

        Log::info($message);
        Log::debug($message);
    }

    public function testLogException()
    {
        $exception = new Exception();

        $notifier = Mockery::mock('RollbarNotifier');
        $notifier->shouldReceive('report_exception')->once()->with($exception);
        $this->app->instance('rollbar', $notifier);

        Config::set('laravel-rollbar::config.excluded_levels', ['debug']);

        Log::info($exception);
        Log::debug($exception);
    }

    public function testFlush()
    {
        $notifier = Mockery::mock('RollbarNotifier');
        $notifier->shouldReceive('flush')->once();
        $this->app->instance('rollbar', $notifier);

        $this->app->callFinishCallbacks(
            new Symfony\Component\HttpFoundation\Request(),
            new Symfony\Component\HttpFoundation\Response()
        );
    }
}
