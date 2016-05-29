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
words, it is a simple, but pragmatic, implementation of the *Dependency
Injection Container* pattern.

To understand how to use this DI, read the
[tests](https://github.com/bauhausphp/package-di/blob/master/tests/acceptance/features/dependency_injection.feature).

> If you have issues about the test frameworks, see the
> [references](https://github.com/bauhausphp/package-di#references).

## Contributing

Did you find some problem or do you want to make this project better?

1. You can open an issue [here](https://github.com/bauhausphp/package-di/issues)
2. Or read the next section to code together :)

### Coding

To start coding in this project, you will need first to clone this repository:

```
$ git clone git@github.com:bauhausphp/package-di.git bauhausphp-package-di
$ cd bauhausphp-package-di
```

Second, you will need to get the dependencies which are already with the
versions locked in the `composer.lock`. So, you just have to install them:

```
composer install
```

Third, the tests! We have *unit* and *acceptance* tests that were implemented
using `phpunit` and `behat` frameworks respectively:

```
$ vendor/bin/phpunit -c tests/phpunit.xml
$ vendor/bin/behat --config tests/behat.yml
```

And finally, explore it and may the force be with you!

## References

- [composer](https://getcomposer.org/)
- [phpunit](https://phpunit.de/)
- [behat](http://docs.behat.org/en/v3.0/)
