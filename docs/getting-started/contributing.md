Tonis and related components are open source and licensed as BSD-3-Clause. Contributions are welcome in the form of issue 
reports and pull requests. 

Getting the Source
------------------

```sh
git clone git@github.com:tonis-io/tonis
composer install
```

Running Tests
-------------

```sh
./vendor/bin/phpunit -c test/phpunit.xml
```

Coding Standards
----------------

Tonis uses PSR-2 coding standards and checks standards using [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer).
To check coding standards:

```sh
vendor/bin/phpcs --standard=PSR2 -s -p src/ test/
```

Additonally, this is automatically checked by [Travis CI](https://travis-ci.org) and will cause a build failure if not valid.

Documentation
-------------

After "Getting the Source" steps above you can find the documentation in in the `docs/` folder. All documentation is written
in markdown. Once you update the documentation, commit your changes and open a pull request.
