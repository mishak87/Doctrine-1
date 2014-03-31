# Zenify/Doctrine


## Requirements

This package requires PHP 5.4.

- [Kdyby\Doctrine](http://github.com/kdyby/doctrine)
- [Kdyby\DoctrineForms](http://github.com/kdyby/doctrineforms)
- [Kdyby\Validator](http://github.com/kdyby/validator)


## Installation

The best way to install is using [Composer](http://getcomposer.org/):

```sh
$ composer require "zenify/doctrine:@dev"
```

And register the extension in `config.neon`:

```yaml
extensions:
	- Zenify\Doctrine\DI\Extension
```
