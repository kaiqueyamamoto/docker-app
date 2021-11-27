<?php

declare(strict_types=1);

namespace dacoto\SetEnv;

use Illuminate\Contracts\Support\DeferrableProvider;

class ServiceProvider extends \Illuminate\Support\ServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->app->bind('setenv', SetEnvEditor::class);
    }

    public function provides(): array
    {
        return [
            'setenv'
        ];
    }
}
