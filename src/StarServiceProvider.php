<?php

namespace JobMetric\Star;

use Illuminate\Contracts\Container\BindingResolutionException;
use JobMetric\EventSystem\Support\EventRegistry;
use JobMetric\PackageCore\Exceptions\MigrationFolderNotFoundException;
use JobMetric\PackageCore\PackageCore;
use JobMetric\PackageCore\PackageCoreServiceProvider;

class StarServiceProvider extends PackageCoreServiceProvider
{
    /**
     * @throws MigrationFolderNotFoundException
     */
    public function configuration(PackageCore $package): void
    {
        $package->name('laravel-star')
            ->hasConfig()
            ->hasTranslation()
            ->hasMigration();
    }

    /**
     * after boot package
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function afterBootPackage(): void
    {
        // Register events if EventRegistry is available
        // This ensures EventRegistry is available if EventSystemServiceProvider is loaded
        if ($this->app->bound('EventRegistry')) {
            /** @var EventRegistry $registry */
            $registry = $this->app->make('EventRegistry');

            // Star Events
            $registry->register(\JobMetric\Star\Events\StarAddEvent::class);
            $registry->register(\JobMetric\Star\Events\StarRemovedEvent::class);
            $registry->register(\JobMetric\Star\Events\StarRemovingEvent::class);
            $registry->register(\JobMetric\Star\Events\StarUpdatedEvent::class);
            $registry->register(\JobMetric\Star\Events\StarUpdatingEvent::class);
        }
    }
}
