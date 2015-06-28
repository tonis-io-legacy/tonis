[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tonis-io/tonis/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tonis-io/tonis/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/tonis-io/tonis/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tonis-io/tonis/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/tonis-io/tonis/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tonis-io/tonis/build-status/master)

Tonis is a PSR-7 middleware framework and was built with an emphasis on simplicity and performance.

Documentation
-------------

For full documentation see the [Tonis docs](http://docs.tonis.io).

Why Tonis?
----------

With the abundance of frameworks and micro-frameworks out there why would you use Tonis?

 * You are using PHP 5.5+ and want to take advantage of modern features.
 * You want 100% unit tested code.
 * You want high quality code.
 * You want to take advantage of PSR-7.
 * You want a middleware ready framework.
 * You want code that runs on HHVM or PHP7 out of the box.
 * You want the freedom to choose your own project structure.
 * You want to get into open source and become a contributor.

If you answered yes to one or more of the above Tonis might be for you.

The Name
--------

I originally got into programming by writing scripts for a game called [Gemstone III](http://www.play.net/gs4) 
(now Gemstone IV). The name comes from [Tonis](https://gswiki.play.net/mediawiki/index.php/Tonis) in Gemstone's world of 
[Elanthia](https://gswiki.play.net/mediawiki/index.php/Elanthia). Tonis is the messenger of the gods and is renowned for 
his speed.

Benchmarks
----------

Benchmarks for simple hello world applications are nearly useless but are provided to prevent the inevitable question.
 
```
Silex: 1.182 ms
Lumen: 0.829 ms
Tonis: 0.816 ms
Slim3: 0.718 ms
 Slim: 0.561 ms
```

These results are generated with ab using 1,000 requests with no concurrency. For more information see the 
[bench project](https://github.com/tonis-io/bench).
