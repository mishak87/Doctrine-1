# Zenify/Doctrine


## Requirements

See section `require` in [composer.json](composer.json).


## Installation

The best way to install is using [Composer](http://getcomposer.org/).

Add to your `composer.json`:

```yaml
"require": {
        "zenify/doctrine": "~2.0",
        "kdyby/doctrine-forms": "@dev",
        "kdyby/validator": "@dev"
}
```

```sh
$ composer update
```

And register the extension in `config.neon`:

```yaml
extensions:
	- Zenify\Doctrine\DI\Extension
```
