# Laragistry - a minimal Laravel key/value registry

This Package contains a minimal registry logic for your Laravel 7+/8+ application.
The aim was to create a simple key/value provider for configuration purpose. For e.g. multi-domain applications, a `scope` can be used to separate entries.

This package does also work with the **Laravel MongoDB** driver from **Jens Segers** ([jenssegers/laravel-mongodb](https://github.com/jenssegers/laravel-mongodb))

## Installation

Install the package via composer.

```sh
composer require spaaleks/laragistry
```

Run the artisan migration command to create the laragistry table.

```sh
php artisan migrate
```


## Usage

After installing, you can use the `Spaaleks\Laragistry\Laragistry` class or it's alias (`\Laragistry`) in your project.
The scope argument is optional for every command. 

|Method|Examples|Returns|Description|
|---|---|---|---|
|**Create/update entry**<br /><br />\Laragistry::set(array \$data, string \$scope = null)|`\Laragistry::set(['key', 'value']);`<br/><br/>`\Laragistry::set(['key', 'value'], 'default');`|entry object|This command will create a new entry or update a existing by key and scope.|
|**Create/update multiple entries**<br /><br />\Laragistry::set(array \$data, string \$scope = null)|`\Laragistry::set([`<br/>&nbsp;&nbsp;&nbsp;&nbsp;`['key1', 'value1'],`<br/>&nbsp;&nbsp;&nbsp;&nbsp;`['key2', 'value2']`<br/>`]);`| entry object or collection of entries|The same *set* command can be used to create/update multiple entries by providing a multidimensional array.|
|**Get a single entry or multiple entries**<br /><br />\Laragistry::get(\$data, string \$scope = null)|`\Laragistry::get('key');`<br/><br>`\Laragistry::get(['key1', 'key2']);`<br/><br>`\Laragistry::get('key_*');`<br/><br>`\Laragistry::get(['key1_*', 'key2_*']);`|entry object or collection of entries|Get a single entry or multiple entries by providden key(s).|
|**Check**<br /><br />\Laragistry::check(string \$key, string \$scope = null)|`\Laragistry::check('key1');`|boolean|This command checks whether a key exists.|
|**Get all in scope**<br /><br />\Laragistry::getByScope(string \$scope)|`\Laragistry::getByScope('my_scope');`|collection of entries|Return all key's in a scope.|
|**Remove a single entry or multiple entries**<br /><br />\Laragistry::remove(\$data, string \$scope = null)|`\Laragistry::remove('key');`<br/><br>`\Laragistry::remove(['key1', 'key2']);`|boolean|Remove a single entry or multiple entries by providden key(s).|
