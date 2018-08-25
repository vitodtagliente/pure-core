# Pure Core Component

Main framework core functionality. 

#### Config management

The Config class let to manage application's configuration loading .ini config files.

Set the config base path

```php
Pure\Config::path("mypath...");
```

Wich means that by default config files will be found in that path.

Consider the following ini file, which is placed in *app/Config/app.ini*

```ini
; Common application settings
one = 1
name = "config_test"

```

To retrieve these settings we can type:

```php
Pure\Config::path("app/Config");
// application's code...
$one = Pure\Config::get('app.one');
$name = Pure\Config::get('app.name');
```

Where the syntax **first.name** means:

- **first** refers to the ini filename
- **second** refers to the param inside the config file

Instead of naming the complete namespace, there is the alias function *config*:

```php
Pure\Config::get('app.one') == config('app.one')
```

It is possible to load config from ini file that are not located into the base path, in that case specify the path

```php
Pure\Config::get('other.one', 'app/OtherPath') == config('other.one', 'app/OtherPath')
```

#### Authentication

Pure provides an easy authentication interface that let you manage users and session so easy.

First of all define an user model

```php
<?php

namespace App\Schemas;
use Pure\SchemaHandler;
use App\Models\User;

class UserSchema extends SchemaHandler
{
    public function table(){
        return User::table();
    }

    protected function define($schema){
        $schema->add('id', 'INT');
        $schema->add('email', 'VARCHAR(30)', 'NOT NULL');
        $schema->add('password', 'VARCHAR(30)', 'NOT NULL');
        $schema->unique('email'); // email must be unique
        $schema->increments('id'); // auto_increment
        $schema->primary('id'); // set the primary key
        $schema->add('active', 'BOOLEAN');
        $schema->add('role', 'INTEGER');
    }
}

?>

```

The Auth interface need to know about the User model

```php
Pure\Auth::$class_name = Pure\Config::get(\App\Models\User::class);
```

Once the User model class is registered to the Auth interface, all the functionalities can be used

```php
// the check method returns true if the user is logged in
$is_logged_in = Pure\Auth::check();

// the authenticate method let the user to login
// authenticate($condition, $remember = false)
if(Pure\Auth::authenticate("email = '$email' AND password = '$password'"))
{
    // authenticated
}
else 
{
    // authentication failed
}

// The user method let to retireve the user model
$user_model = Pure\Auth::user();

// call logout to end the user session
Pure\Auth::logout();
```

#### Schemas generation

The SchemaHandler is an easy interface that let to automatically generate schemas at the application startup.

```php
<?php

namespace App\Schemas;
use Pure\SchemaHandler;

class ExampleSchema extends SchemaHandler
{
    public function table(){
        return "schema_name";
    }

    protected function define($schema){
        $schema->add('id', 'INT');
        $schema->increments('id'); // auto_increment
        $schema->primary('id'); // set the primary key
        // other schema fields... 
    }
}

?>
```

Let's take an example, this schema handler generates the User's schema

```php
<?php

namespace App\Schemas;
use Pure\SchemaHandler;
use App\Models\User;

class UserSchema extends SchemaHandler
{
    public function table(){
        return User::table();
    }

    protected function define($schema){
        $schema->add('id', 'INT');
        $schema->add('email', 'VARCHAR(30)', 'NOT NULL');
        $schema->add('password', 'VARCHAR(30)', 'NOT NULL');
        $schema->unique('email'); // email must be unique
        $schema->increments('id'); // auto_increment
        $schema->primary('id'); // set the primary key
        $schema->add('active', 'BOOLEAN');
        $schema->add('role', 'INTEGER');
    }
}

?>
```

#### HTTP requests

The class Request can be used to access HTTP paramas. 

Use the method get for GET HTTP requests

```php
// url = localhost:8000/show_posts&category=books
$category = Pure\Request::get('category'); // equals books
```

Otherside use the post method for POST HTTP requests

```php
$id = Pure\Request::post('id');
```

Use the method input to let the application automatically use get or post according to the request type.

```php
$param = Pure\Request::input('param');
```

Instead of naming the complete namespace, there are alias functions:

```php
Pure\Request::get('param') == get('param');
Pure\Request::post('param') == post('param');
Pure\Request::input('param') == request('param');
```

#### Application

TODO

#### Dev fast API

TODO
