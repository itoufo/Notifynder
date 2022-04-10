<?php

use Itoufo\Tests\Models\Car;
use Itoufo\Tests\Models\User;
use Itoufo\Tests\Models\CarL53;
use Itoufo\Tests\Models\UserL53;
use Illuminate\Database\Eloquent\Model;
use Itoufo\Notifer\Models\Notification;
use Itoufo\Notifer\NotiferServiceProvider;
use Itoufo\Notifer\Models\NotificationCategory;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Itoufo\Notifer\Facades\Notifer as NotiferFacade;

abstract class NotiferTestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            NotiferServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Notifer' => NotiferFacade::class,
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $artisan = $this->app->make('Illuminate\Contracts\Console\Kernel');
        app('db')->beginTransaction();
        $this->migrate($artisan);
        $this->migrate($artisan, '/../../../../tests/migrations');
        // Set up the User Test Model
        app('config')->set('notifer.notification_model', 'Itoufo\Notifer\Models\Notification');
        app('config')->set('notifer.model', 'Itoufo\Tests\Models\User');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.connections.test_sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('database.connections.test_mysql', [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => 3306,
            'database' => 'notifer',
            'username' => 'travis',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ]);
        $app['config']->set('database.connections.test_pgsql', [
            'driver' => 'pgsql',
            'host' => 'localhost',
            'port' => 5432,
            'database' => 'notifer',
            'username' => 'postgres',
            'password' => '',
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
        ]);
        if (env('DB_TYPE', 'sqlite') == 'mysql') {
            $app['config']->set('database.default', 'test_mysql');
        } elseif (env('DB_TYPE', 'sqlite') == 'pgsql') {
            $app['config']->set('database.default', 'test_pgsql');
        } else {
            $app['config']->set('database.default', 'test_sqlite');
        }
    }

    public function tearDown()
    {
        $resolver = app('notifer.resolver.model');
        $resolver->setTable(Notification::class, 'notifications');
        app('db')->rollback();
        if (app('db')->getDriverName() == 'mysql') {
            app('db')->statement('SET FOREIGN_KEY_CHECKS=0;');
            Notification::truncate();
            NotificationCategory::truncate();
            app('db')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    protected function getApplicationTimezone($app)
    {
        return 'UTC';
    }

    protected function migrate($artisan, $path = '/../../../../src/migrations')
    {
        $artisan->call('migrate', [
            '--path' => $path,
        ]);
    }

    protected function createCategory(array $attributes = [])
    {
        $attributes = array_merge([
            'text' => 'Notification send from #{from.id} to #{to.id}.',
            'name' => 'test.category',
        ], $attributes);

        $category = NotificationCategory::byName($attributes['name'])->first();
        if ($category instanceof NotificationCategory) {
            return $category;
        }

        return NotificationCategory::create($attributes);
    }

    protected function createUser(array $attributes = [])
    {
        $attributes = array_merge([
            'firstname' => 'John',
            'lastname' => 'Doe',
        ], $attributes);

        if ($this->getLaravelVersion() < 5.3) {
            return User::create($attributes);
        } else {
            return UserL53::create($attributes);
        }
    }

    protected function createCar(array $attributes = [])
    {
        $attributes = array_merge([
            'brand' => 'Audi',
            'model' => 'A6',
        ], $attributes);

        if ($this->getLaravelVersion() < 5.3) {
            return Car::create($attributes);
        } else {
            return CarL53::create($attributes);
        }
    }

    protected function sendNotificationTo(Model $model)
    {
        $category = $this->createCategory();

        return $model
            ->sendNotificationTo($category->getKey())
            ->from(2)
            ->send();
    }

    protected function sendNotificationsTo(Model $model, $amount = 10)
    {
        while ($amount > 0) {
            $this->sendNotificationTo($model);
            $amount--;
        }
    }

    protected function getLaravelVersion()
    {
        $version = app()->version();
        $parts = explode('.', $version);

        return ($parts[0].'.'.$parts[1]) * 1;
    }

    public function __call($name, $arguments)
    {
        if ($name == 'expectException') {
            $this->setExpectedException($arguments[0]);
        }
    }
}
