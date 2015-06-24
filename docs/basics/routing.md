Routing refers to the definition of URIs and how an application responds to a client request. A route
is a combination of a [URI](https://en.wikipedia.org/wiki/Uniform_resource_identifier), a 
[request method](http://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol), and one or more handlers.

The following is an example of a very basic route:

```php
$app = new \Tonis\App;

// respond with "hello world" when a GET request is made to "/"
$app->get('/', function ($req, $res) {
    $res->end("hello world");    
});
```

Route Methods
-------------

Tonis supports the following HTTP methods: ```GET```, ```POST```, ```PUT```, ```PATCH```, ```DELETE```, ```OPTIONS```, 
```HEAD```

```php
$app = new \Tonis\App;

$app->get('/', function ($req, $res) {
    $res->end("GET request to homepage");    
});

$app->post('/', function ($req, $res) {
    $res->end("POST request to homepage");    
});
```

Route Parameters
----------------

Route parameters may be used by enclosing part of the route in ```{...}```.

```php
$app = new \Tonis\App;

$app->get('/{name}', function ($req, $res) {
    $res->end('I match /foo, /bar, /foobar, etc.');
});
```

You can specify a custom pattern match by using ```{foo:regex}``` where regex is a regular expression.

```php
$app = new \Tonis\App;

$app->get('/{name:[0-9]+', function ($req, $res) {
    $res->end('I match /123 but not /foo');
});
```

Additionally, parts of the route enclosed in ```[...]``` are considered optional.

```php
$app = new \Tonis\App;

$app->get('/foo[bar]', function ($req, $res) {
    $res->end('I match /foo and /foobar but not /bar');
});
```

Accessing Parameters
--------------------

All matched route parameters are available in the ```$request``` object of the route handler. 

```php
$app = new \Tonis\App;

$app->get('/{name}', function ($req, $res) {
    $res->end('Hi ' . $req->getAttribute('name'));
});
```
