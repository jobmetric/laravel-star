<?php

namespace JobMetric\Star;

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
     * Register committed business outcome definitions alongside legacy events.
     *
     * @return void
     */
    public function afterBootPackage(): void
    {
        if ($this->app->bound('EventRegistry')) {
            $registry = $this->app->make('EventRegistry');
            $registry->register(\JobMetric\Star\Events\Star\StarStoreCommittedEvent::class);
            $registry->register(\JobMetric\Star\Events\Star\StarUpdateCommittedEvent::class);
            $registry->register(\JobMetric\Star\Events\Star\StarDeleteCommittedEvent::class);
        }
    }
}
