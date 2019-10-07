# Actions for Fail Back

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Total Downloads][ico-downloads]][link-downloads]

This is a small wrapper for creating a branch of inaccessibility. 

## Install

Via Composer

``` bash
$ composer require tabuna/failback
```

## Usage


Default Value:
```php
use Tabuna\FailBack\Action;

// $result = 'default';
$result = Action::make(function () {
    throw new \Exception();
}, 'default')->run();
```

Fallback Features:
```php
use Tabuna\FailBack\Action;

// $result = true;
$result = Action::make(function () {
    throw new \Exception();
})->fallBack(function () {
    throw new \Error();
})->fallBack(function () {
    return true;
})->run();
```


As classes:
```php
use Tabuna\FailBack\Action;

$class = new class() {

    /**
     * @return bool
     */
    public function __invoke(): bool
    {
        return true;
    }
};

// $result = true;
$result = Action::make(function () {
    throw new Exception();
})->fallBack($class)->run();
```

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
