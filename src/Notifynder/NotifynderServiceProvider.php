<?php

namespace Itoufo\Notifynder;

use Itoufo\Notifynder\Artisan\CreateCategory;
use Itoufo\Notifynder\Artisan\DeleteCategory;
use Itoufo\Notifynder\Artisan\CreateGroup;
use Itoufo\Notifynder\Artisan\PushCategoryToGroup;
use Itoufo\Notifynder\Builder\NotifynderBuilder;
use Itoufo\Notifynder\Categories\CategoryRepository;
use Itoufo\Notifynder\Categories\CategoryManager;
use Itoufo\Notifynder\Contracts\CategoryDB;
use Itoufo\Notifynder\Contracts\NotificationDB;
use Itoufo\Notifynder\Contracts\NotifynderCategory;
use Itoufo\Notifynder\Contracts\NotifynderDispatcher;
use Itoufo\Notifynder\Contracts\NotifynderGroup;
use Itoufo\Notifynder\Contracts\NotifynderGroupCategoryDB;
use Itoufo\Notifynder\Contracts\NotifynderGroupDB;
use Itoufo\Notifynder\Contracts\NotifynderNotification;
use Itoufo\Notifynder\Contracts\NotifynderSender;
use Itoufo\Notifynder\Contracts\NotifynderTranslator;
use Itoufo\Notifynder\Contracts\StoreNotification;
use Itoufo\Notifynder\Groups\GroupManager;
use Itoufo\Notifynder\Groups\GroupCategoryRepository;
use Itoufo\Notifynder\Groups\GroupRepository;
use Itoufo\Notifynder\Handler\Dispatcher;
use Itoufo\Notifynder\Models\Notification;
use Itoufo\Notifynder\Models\NotificationCategory;
use Itoufo\Notifynder\Models\NotificationGroup;
use Itoufo\Notifynder\Notifications\NotificationManager;
use Itoufo\Notifynder\Notifications\NotificationRepository;
use Itoufo\Notifynder\Parsers\ArtisanOptionsParser;
use Itoufo\Notifynder\Parsers\NotifynderParser;
use Itoufo\Notifynder\Senders\SenderFactory;
use Itoufo\Notifynder\Senders\SenderManager;
use Itoufo\Notifynder\Translator\Compiler;
use Itoufo\Notifynder\Translator\TranslatorManager;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;

class NotifynderServiceProvider extends ServiceProvider
{
    /**
     * Register Bindings.
     */
    public function register()
    {
        $this->notifynder();
        $this->senders();
        $this->notifications();
        $this->categories();
        $this->builder();
        $this->groups();
        $this->translator();
        $this->events();
        $this->contracts();
        $this->artisan();
    }

    /*
     * Boot the publishing config
     */
    public function boot()
    {
        $this->config();
        $this->migration();
    }

    /**
     * Bind Notifynder.
     */
    protected function notifynder()
    {
        $this->app->singleton('notifynder', function ($app) {
            return new NotifynderManager(
                $app['notifynder.category'],
                $app['notifynder.sender'],
                $app['notifynder.notification'],
                $app['notifynder.dispatcher'],
                $app['notifynder.group']
            );
        });

        // Register Facade
        $this->app->alias('notifynder', 'Notifynder');
    }

    /**
     * Bind Notifynder Categories to IoC.
     */
    protected function categories()
    {
        $this->app->singleton('notifynder.category', function ($app) {
            return new CategoryManager(
                $app->make('notifynder.category.repository')
            );
        });

        $this->app->singleton('notifynder.category.repository', function () {
            return new CategoryRepository(
                new NotificationCategory()
            );
        });
    }

    /**
     * Bind the notifications.
     */
    protected function notifications()
    {
        $this->app->singleton('notifynder.notification', function ($app) {
            return new NotificationManager(
                $app['notifynder.notification.repository']
            );
        });

        $this->app->singleton('notifynder.notification.repository', function ($app) {
            $notificationModel = $app['config']->get('notifynder.notification_model');
            $notificationInstance = $app->make($notificationModel);

            return new NotificationRepository(
                $notificationInstance,
                $app['db']
            );
        });

        // Default store notification
        $this->app->bind('notifynder.store', 'notifynder.notification.repository');
    }

    /**
     * Bind Translator.
     */
    protected function translator()
    {
        $this->app->singleton('notifynder.translator', function ($app) {
            return new TranslatorManager(
                $app['notifynder.translator.compiler'],
                $app['config']
            );
        });

        $this->app->singleton('notifynder.translator.compiler', function ($app) {
            return new Compiler(
                $app['filesystem.disk']
            );
        });
    }

    /**
     * Bind Senders.
     */
    protected function senders()
    {
        $this->app->singleton('notifynder.sender', function ($app) {
            return new SenderManager(
                 $app['notifynder.sender.factory'],
                 $app['notifynder.store'],
                 $app[Container::class]
             );
        });

        $this->app->singleton('notifynder.sender.factory', function ($app) {
            return new SenderFactory(
                $app['notifynder.group'],
                $app['notifynder.category']
            );
        });
    }

    /**
     * Bind Dispatcher.
     */
    protected function events()
    {
        $this->app->singleton('notifynder.dispatcher', function ($app) {
            return new Dispatcher(
                $app['events']
            );
        });
    }

    /**
     * Bind Groups.
     */
    protected function groups()
    {
        $this->app->singleton('notifynder.group', function ($app) {
            return new GroupManager(
                $app['notifynder.group.repository'],
                $app['notifynder.group.category']
            );
        });

        $this->app->singleton('notifynder.group.repository', function () {
            return new GroupRepository(
                new NotificationGroup()
            );
        });

        $this->app->singleton('notifynder.group.category', function ($app) {
            return new GroupCategoryRepository(
                $app['notifynder.category'],
                new NotificationGroup()
            );
        });
    }

    /**
     * Bind Builder.
     */
    protected function builder()
    {
        $this->app->singleton('notifynder.builder', function ($app) {
            return new NotifynderBuilder(
                $app['notifynder.category']
            );
        });

        $this->app->resolving(NotifynderBuilder::class, function (NotifynderBuilder $object, $app) {
            $object->setConfig($app['config']);
        });
    }

    /**
     * Contracts of notifynder.
     */
    protected function contracts()
    {
        // Notifynder
        $this->app->bind(Notifynder::class, 'notifynder');

        // Repositories
        $this->app->bind(CategoryDB::class, 'notifynder.category.repository');
        $this->app->bind(NotificationDB::class, 'notifynder.notification.repository');
        $this->app->bind(NotifynderGroupDB::class, 'notifynder.group.repository');
        $this->app->bind(NotifynderGroupCategoryDB::class, 'notifynder.group.category');

        // Main Classes
        $this->app->bind(NotifynderCategory::class, 'notifynder.category');
        $this->app->bind(NotifynderNotification::class, 'notifynder.notification');
        $this->app->bind(NotifynderTranslator::class, 'notifynder.translator');
        $this->app->bind(NotifynderGroup::class, 'notifynder.group');

        // Store notifications
        $this->app->bind(StoreNotification::class, 'notifynder.store');
        $this->app->bind(NotifynderSender::class, 'notifynder.sender');
        $this->app->bind(NotifynderDispatcher::class, 'notifynder.dispatcher');
    }

    /**
     * Publish config files.
     */
    protected function config()
    {
        $this->publishes([
            __DIR__.'/../config/notifynder.php' => config_path('notifynder.php'),
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/notifynder.php', 'notifynder');

        // Set use strict_extra config option,
        // you can toggle it in the configuration file
        $strictParam = $this->app['config']->get('notifynder.strict_extra', false);
        NotifynderParser::setStrictExtra($strictParam);
    }

    /**
     * Publish migration files.
     */
    protected function migration()
    {
        if (! class_exists('NotificationCategories')) {
            $this->publishMigration('2014_02_10_145728_notification_categories');
        }
        if (! class_exists('CreateNotificationGroupsTable')) {
            $this->publishMigration('2014_08_01_210813_create_notification_groups_table');
        }
        if (! class_exists('CreateNotificationCategoryNotificationGroupTable')) {
            $this->publishMigration('2014_08_01_211045_create_notification_category_notification_group_table');
        }
        if (! class_exists('CreateNotificationsTable')) {
            $this->publishMigration('2015_05_05_212549_create_notifications_table');
        }
        if (! class_exists('AddExpireTimeColumnToNotificationTable')) {
            $this->publishMigration('2015_06_06_211555_add_expire_time_column_to_notification_table');
        }
        if (! class_exists('ChangeTypeToExtraInNotificationsTable')) {
            $this->publishMigration('2015_06_06_211555_change_type_to_extra_in_notifications_table');
        }
        if (! class_exists('AlterCategoryNameToUnique')) {
            $this->publishMigration('2015_06_07_211555_alter_category_name_to_unique');
        }
        if (! class_exists('MakeNotificationUrlNullable')) {
            $this->publishMigration('2016_04_19_200827_make_notification_url_nullable');
        }
        if (! class_exists('AddStackIdToNotifications')) {
            $this->publishMigration('2016_05_19_144531_add_stack_id_to_notifications');
        }
    }

    /**
     * @param string $filename
     */
    protected function publishMigration($filename)
    {
        $extension = '.php';
        $filename = trim($filename, $extension).$extension;
        $stub = __DIR__.'/../migrations/'.$filename;
        $target = $this->migrationFilepath($filename);
        $this->publishes([$stub => $target], 'migrations');
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function migrationFilepath($filename)
    {
        if (function_exists('database_path')) {
            return database_path('/migrations/'.$filename);
        } else {
            return base_path('/database/migrations/'.$filename);
        }
    }

    /**
     * Register Artisan commands.
     */
    protected function artisan()
    {
        // Categories
        $this->app->singleton('notifynder.artisan.category-add', function ($app) {
            return new CreateCategory(
                $app['notifynder.category']
            );
        });

        $this->app->singleton('notifynder.artisan.category-delete', function ($app) {
            return new DeleteCategory(
                $app['notifynder.category']
            );
        });

        // Groups
        $this->app->singleton('notifynder.artisan.group-add', function ($app) {
            return new CreateGroup(
                $app['notifynder.group']
            );
        });

        $this->app->singleton('notifynder.artisan.group-add-categories', function ($app) {
            return new PushCategoryToGroup(
                $app['notifynder.group'],
                new ArtisanOptionsParser()
            );
        });

        // Register commands
        $this->commands([
            'notifynder.artisan.category-add',
            'notifynder.artisan.category-delete',
            'notifynder.artisan.group-add',
            'notifynder.artisan.group-add-categories',
        ]);
    }
}
