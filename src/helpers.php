<?php

declare(strict_types=1);

use Tabuna\FailBack\Action;

if (!function_exists('failBack')) {
    /**
     * @param callable|null $closure
     * @param null          $default
     *
     * @return Action
     */
    function failBack(callable $closure = null, $default = null)
    {
        return new Action($closure, $default);
    }
}
