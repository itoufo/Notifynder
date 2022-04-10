<?php

namespace Itoufo\Notifer;

use Illuminate\Support\ServiceProvider;
use Itoufo\Notifer\Collections\Config;
use Itoufo\Notifer\Senders\OnceSender;
use Itoufo\Notifer\Senders\SingleSender;
use Itoufo\Notifer\Managers\SenderManager;
use Itoufo\Notifer\Senders\MultipleSender;
use Itoufo\Notifer\Resolvers\ModelResolver;
use Itoufo\Notifer\Contracts\ConfigContract;
use Itoufo\Notifer\Managers\NotiferManager;
use Itoufo\Notifer\Contracts\SenderManagerContract;
use Itoufo\Notifer\Contracts\NotiferManagerContract;

/**
 * Class NotiferServiceProvider.
 */
class NotiferServiceProvider extends ServiceProvider
{
    protected $migrations = [
        'NotificationCategories' => '2014_02_10_145728_notification_categories',
        'CreateNotificationGroupsTable' => '2014_08_01_210813_create_notification_groups_table',
        'CreateNotificationCategoryNotificationGroupTable' => '2014_08_01_211045_create_notification_category_notification_group_table',
        'CreateNotificationsTable' => '2015_05_05_212549_create_notifications_table',
        'AddExpireTimeColumnToNotificationTable' => '2015_06_06_211555_add_expire_time_column_to_notification_table',
        'ChangeTypeToExtraInNotificationsTable' => '2015_06_06_211555_change_type_to_extra_in_notifications_table',
        'AlterCategoryNameToUnique' => '2015_06_07_211555_alter_category_name_to_unique',
        'MakeNotificationUrlNullable' => '2016_04_19_200827_make_notification_url_nullable',
        'AddStackIdToNotifications' => '2016_05_19_144531_add_stack_id_to_notifications',
        'UpdateVersion4NotificationsTable' => '2016_07_01_153156_update_version4_notifications_table',
        'DropVersion4UnusedTables' => '2016_11_02_193415_drop_version4_unused_tables',
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->bindContracts();
        $this->bindConfig();
        $this->bindSender();
        $this->bindResolver();
        $this->bindNotifer();

        $this->registerSenders();
    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->config();
        $this->migration();
    }

    /**
     * Bind contracts.
     *
     * @return void
     */
    protected function bindContracts()
    {
        $this->app->bind(NotiferManagerContract::class, 'notifer');
        $this->app->bind(SenderManagerContract::class, 'notifer.sender');
        $this->app->bind(ConfigContract::class, 'notifer.config');
    }

    /**
     * Bind Notifer config.
     *
     * @return void
     */
    protected function bindConfig()
    {
        $this->app->singleton('notifer.config', function () {
            return new Config();
        });
    }

    /**
     * Bind Notifer sender.
     *
     * @return void
     */
    protected function bindSender()
    {
        $this->app->singleton('notifer.sender', function () {
            return new SenderManager();
        });
    }

    /**
     * Bind Notifer resolver.
     *
     * @return void
     */
    protected function bindResolver()
    {
        $this->app->singleton('notifer.resolver.model', function () {
            return new ModelResolver();
        });
    }

    /**
     * Bind Notifer manager.
     *
     * @return void
     */
    protected function bindNotifer()
    {
        $this->app->singleton('notifer', function ($app) {
            return new NotiferManager(
                $app['notifer.sender']
            );
        });
    }

    /**
     * Register the default senders.
     *
     * @return void
     */
    public function registerSenders()
    {
        app('notifer')->extend('sendSingle', function (array $notifications) {
            return new SingleSender($notifications);
        });

        app('notifer')->extend('sendMultiple', function (array $notifications) {
            return new MultipleSender($notifications);
        });

        app('notifer')->extend('sendOnce', function (array $notifications) {
            return new OnceSender($notifications);
        });
    }

    /**
     * Publish and merge config file.
     *
     * @return void
     */
    protected function config()
    {
        $this->publishes([
            __DIR__.'/../config/notifer.php' => config_path('notifer.php'),
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/notifer.php', 'notifer');
    }

    /**
     * Publish migration files.
     *
     * @return void
     */
    protected function migration()
    {
        foreach ($this->migrations as $class => $file) {
            if (! class_exists($class)) {
                $this->publishMigration($file);
            }
        }
    }

    /**
     * Publish a single migration file.
     *
     * @param string $filename
     * @return void
     */
    protected function publishMigration($filename)
    {
        $extension = '.php';
        $filename = trim($filename, $extension).$extension;
        $stub = __DIR__.'/../migrations/'.$filename;
        $target = $this->getMigrationFilepath($filename);
        $this->publishes([$stub => $target], 'migrations');
    }

    /**
     * Get the migration file path.
     *
     * @param string $filename
     * @return string
     */
    protected function getMigrationFilepath($filename)
    {
        if (function_exists('database_path')) {
            return database_path('/migrations/'.$filename);
        }

        return base_path('/database/migrations/'.$filename); // @codeCoverageIgnore
    }
}
