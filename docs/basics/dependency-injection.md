Overview
--------

Tonis using a dependency container to manage and inject dependencies. Tonis uses a container based on the 
[League Container](http://container.thephpleague.com/) but any container that implements 
`Interop\Container\ContainerInterface` may be used.

For more information on the concepts see [Dependency Injection](https://en.wikipedia.org/wiki/Dependency_injection) and 
[Inversion of Control](https://en.wikipedia.org/wiki/Inversion_of_control) on Wikipedia.

Usage
-----

To use a container you must inject it when you create the `Tonis\App` object.

```php
$container = new \Tonis\Container;
$app       = new \Tonis\App($container);
```

If no container is specified, Tonis creates the default one for you.

```php
// this is identical to the example above
$app = new \Tonis\App;
```

The container is available from `Tonis\App` through the `getContainer()` method. 
 
```php
$container = $app->getContainer();
$fooClass  = $container->get('FooClass');
```

Required Services
-----------------

If you choose to use your own container you MUST provide the following services:

<dl>
    <dt>
        Tonis\Router
    </dt>
    <dd>
        MUST return a NEW instance of <code>Tonis\Router</code> on each get. 
    </dd>
    <dt>
        Tonis\Handler\ErrorInterface
    </dt>
    <dd>
        An instance of <code>Tonis\Handler\ErrorInterface</code> which handles exceptions.
    </dd>
    <dt>
        Tonis\Handler\NotFoundInterface
    </dt>
    <dd>
        An instance of <code>Tonis\Handler\NotFoundInterface</code> which handles missing routes.
    </dd>
    <dt>
        Tonis\View\Manager
    </dt>
    <dd>
        An instance of <code>Tonis\View\Manager</code> which handles rendering templates.
    </dd>
</dl>
