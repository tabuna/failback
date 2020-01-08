<?php

declare(strict_types=1);

namespace Tabuna\FailBack;

/**
 * Class Action
 */
class Action
{
    /**
     * @var callable[]
     */
    private $backs = [];

    /**
     * @var callable|null
     */
    private $closure;

    /**
     * @var mixed
     */
    private $default;

    /**
     * Create a new Action Instance
     *
     * @param callable $closure
     * @param null          $default
     */
    public function __construct(callable $closure = null, $default = null)
    {
        $this->closure = $closure;
        $this->default = $default;
    }

    /**
     * @param callable $closure
     * @param null     $default
     *
     * @return $this
     */
    public function set(callable $closure, $default = null): Action
    {
        $this->closure = $closure;
        $this->default = $default;

        return $this;
    }

    /**
     * @param callable $closure
     * @param null     $default
     *
     * @return Action
     */
    public static function make(callable $closure, $default = null): Action
    {
        return new static($closure, $default);
    }

    /**
     * @param callable $closure
     *
     * @return Action
     */
    public function fail(callable $closure): Action
    {
        $this->backs[] = $closure;

        return $this;
    }

    /**
     * @return mixed|null
     */
    private function handler()
    {
        foreach ($this->backs as $back) {
            try {
                $this->default = $back();
            } catch (\Throwable $throwable) {
                continue;
            }
        }

        return $this->default;
    }

    /**
     * @return mixed|void
     */
    public function run()
    {
        try {
            return call_user_func($this->closure);
        } catch (\Throwable $throwable) {
            return $this->handler();
        }
    }

    /**
     * @return mixed|void
     */
    public function __invoke()
    {
        return $this->run();
    }

    /**
     * @return mixed|void
     */
    public function __toString(): string
    {
        return (string) $this->run();
    }
}
