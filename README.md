# Actions for failback



<a href="https://travis-ci.org/tabuna/failback/"><img src="https://travis-ci.org/tabuna/failback.svg?branch=master"></a>
<a href="https://packagist.org/packages/tabuna/failback"><img alt="Packagist" src="https://img.shields.io/packagist/dt/tabuna/failback.svg"></a>
<a href="https://packagist.org/packages/tabuna/failback"><img alt="Packagist Version" src="https://img.shields.io/packagist/v/tabuna/failback.svg"></a>


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

// or short helper
$result = failBack(function (){
    throw new \Exception();
}, 'default')();
```

Fallback Features:
```php
// $result = true;
$result = failBack(function () {
    throw new \Exception();
})->fail(function () {
    throw new \Error();
})->fail(function () {
    return true;
})();
```


As classes:
```php
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
$result = failBack(function () {
    throw new Exception();
})->fail($class)->run();
```

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
