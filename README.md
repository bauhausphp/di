# Bauhaus PHP Package - DI

[![Latest Stable Version](https://poser.pugx.org/bauhaus/di/v/stable?format=flat-square)](https://packagist.org/packages/bauhaus/di)
[![Latest Unstable Version](https://poser.pugx.org/bauhaus/di/v/unstable?format=flat-square)](https://packagist.org/packages/bauhaus/di)
[![Total Downloads](https://poser.pugx.org/bauhaus/di/downloads?format=flat-square)](https://packagist.org/packages/bauhaus/di)
[![License](https://poser.pugx.org/bauhaus/di/license?format=flat-square)](LICENSE)

[![Build Status](https://img.shields.io/travis/bauhausphp/package-di/master.svg?style=flat-square)](https://travis-ci.org/bauhausphp/package-di)
[![Coverage Status](https://img.shields.io/coveralls/bauhausphp/package-di/master.svg?style=flat-square)](https://coveralls.io/github/bauhausphp/package-di?branch=master)
[![Codacy Badge](https://img.shields.io/codacy/e9884ae8a00f46f3bea9cdb565104569.svg?style=flat-square)](https://www.codacy.com/app/fefas/bauhausphp-package-di)

# Introduction

The goal of this package is to provide a simple way to register and to load
services that are the dependencies of other parts of your project code. In other
words, it is a  pragmatic implementation of the *Dependency Injection Container*
pattern that supports `shared`, `lazy` and `not shared`.

To understand how to use this *Dependency Injection Container*, read the
[unit tests](https://github.com/bauhausphp/package-di/blob/master/tests/unit/DependencyInjectionTest.php).

> You can have a behavior summary of this *Dependency Injection Container* by
> runing the tests using the `testdox` option:
>
> ```
> $ vendor/bin/phpunit -c tests/config/phpunit.xml --testdox
> ```
>
> See the *[Code Together](#code-together)* section for more details.

## Install

The easiest way to install is by using [composer](https://getcomposer.org/):

```
$ composer require bauhaus/di:dev-master
```

## Contribute

Did you find some problem or do you want to make this project better?

1. Did you find some problem? You can easy open an issue here
   [here](https://github.com/bauhausphp/package-di/issues)
2. Do you want to help coding? Read the next section and let's code together :)

### Code Together

First you will need to clone this repository:

```
$ git clone git@github.com:bauhausphp/package-di.git bauhausphp-package-di
$ cd bauhausphp-package-di
```

Second, you have to install the dependencies which are already with the versions
locked by the composer.lock. So, you just have to install them using
[composer](https://getcomposer.org/):

```
composer install
```

Third, before starting code, you need to make sure that the tests pass. There
are unit that were implemented using [phpunit](https://phpunit.de/) framework.
To run them, use the following command:

```
$ vendor/bin/phpunit -c tests/config/phpunit.xml
```
