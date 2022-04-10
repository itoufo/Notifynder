# Notifer 4 - Laravel 5

[![GitHub release](https://img.shields.io/github/release/Itoufo/Notifer.svg?style=flat-square)](https://github.com/Itoufo/Notifer/releases)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://raw.githubusercontent.com/Itoufo/Notifer/master/LICENSE)
[![GitHub issues](https://img.shields.io/github/issues/Itoufo/Notifer.svg?style=flat-square)](https://github.com/Itoufo/Notifer/issues)
[![Total Downloads](https://img.shields.io/packagist/dt/Itoufo/notifer.svg?style=flat-square)](https://packagist.org/packages/Itoufo/notifer)
[![VersionEye](https://www.versioneye.com/user/projects/5878c014a21fa90051522611/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/5878c014a21fa90051522611)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/ef2a6768-337d-4a88-ae0b-8a0eb9621bf5.svg?style=flat-square&label=SensioLabs)](https://insight.sensiolabs.com/projects/ef2a6768-337d-4a88-ae0b-8a0eb9621bf5)

[![Travis branch](https://img.shields.io/travis/Itoufo/Notifer/master.svg?style=flat-square&label=TravisCI)](https://travis-ci.org/Itoufo/Notifer/branches)
[![StyleCI](https://styleci.io/repos/18425539/shield)](https://styleci.io/repos/18425539)
[![Scrutinizer Build](https://img.shields.io/scrutinizer/build/g/Itoufo/Notifer.svg?style=flat-square&label=ScrutinizerCI)](https://scrutinizer-ci.com/g/Itoufo/Notifer/?branch=master)

[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/Itoufo/Notifer.svg?style=flat-square)](https://scrutinizer-ci.com/g/Itoufo/Notifer/?branch=master)
[![Code Climate](https://img.shields.io/codeclimate/github/Itoufo/Notifer.svg?style=flat-square)](https://codeclimate.com/github/Itoufo/Notifer)
[![Coveralls](https://img.shields.io/coveralls/Itoufo/Notifer.svg?style=flat-square)](https://coveralls.io/github/Itoufo/Notifer)

[![Slack Team](https://img.shields.io/badge/slack-astrotomic-orange.svg?style=flat-square)](https://astrotomic.slack.com)
[![Slack join](https://img.shields.io/badge/slack-join-green.svg?style=social)](https://notifer.signup.team)


Notifer is designed to manage notifications in a powerful and easy way. With the flexibility that Notifer offer, It provide a complete API to work with your notifications, such as storing, retrieving, and organise your codebase to handle hundreds of notifications. You get started in a couple of minutes to "enable" notifications in your Laravel Project.

Compatible DBs: **MySQL** - **PostgreSQL** - **SQLite**

Documentation: **[Notifer Docu](http://notifer.info)**

-----

## Installation

### Step 1

Add it on your `composer.json`

```
"Itoufo/notifer": "^4.0"
```

and run 

```
composer update
```

or run

```
composer require Itoufo/notifer
```


### Step 2

Add the following string to `config/app.php`

**Providers array:**

```
Itoufo\Notifer\NotiferServiceProvider::class,
```

**Aliases array:**

```
'Notifer' => Itoufo\Notifer\Facades\Notifer::class,
```


### Step 3

#### Migration & Config

Publish the migration as well as the configuration of notifer with the following command:

```
php artisan vendor:publish --provider="Itoufo\Notifer\NotiferServiceProvider"
```

Run the migration

```
php artisan migrate
```

## Senders

A list of official supported custom senders is in the [Notifer Doc](http).

We also have a [collect issue](https://github.com/Itoufo/Notifer/issues/242) for all additional senders we plan or already have.

If you want any more senders or want to provide your own please [create an issue](https://github.com/Itoufo/Notifer/issues/new?milestone=Senders).

## ToDo

Tasks we still have to do:

* add unittests for parser and models
* complete the new documentation

## Versioning

Starting with `v4.0.0` we are following the [Semantic Versioning Standard](http://semver.org).

### Summary

Given a version number `MAJOR`.`MINOR`.`PATCH`, increment the:

* **MAJOR** version when you make incompatible API changes,
* **MINOR** version when you add functionality in a backwards-compatible manner, and
* **PATCH** version when you make backwards-compatible bug fixes.

Additional labels for pre-release (`alpha`, `beta`, `rc`) are available as extensions to the `MAJOR`.`MINOR`.`PATCH` format.

## Contributors

Thanks for everyone [who contributed](https://github.com/Itoufo/Notifer/graphs/contributors) to Notifer and a special thanks for the most active contributors

- [Gummibeer](https://github.com/Gummibeer)

## Services

* [Travis CI](https://travis-ci.org/Itoufo/Notifer)
* [Style CI](https://styleci.io/repos/18425539)
* [Code Climate](https://codeclimate.com/github/Itoufo/Notifer)
* [Scrutinizer](https://scrutinizer-ci.com/g/Itoufo/Notifer)
* [Coveralls](https://coveralls.io/github/Itoufo/Notifer)
