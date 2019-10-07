<?php

declare(strict_types=1);

namespace Tabuna\FailBack;

use Error;
use Exception;
use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{

    public function testDefaultValue()
    {
        $result = Action::make(function () {
            throw new Exception();
        }, true)->run();

        $this->assertTrue($result);
    }


    public function testFailBack()
    {
        $result = Action::make(function () {
            throw new Exception();
        })->fallBack(function () {
            throw new Exception();
        })->fallBack(function () {
            return true;
        })
            ->run();

        $this->assertTrue($result);
    }

    public function testContextFailBack()
    {
        $name = 'Tomas';

        $result = Action::make(function () {
            throw new Exception();
        })->fallBack(function () use (&$name) {

            $name = 'Alexandr';

            return $this->returnAlwaysTrue();
        })
            ->run();

        $this->assertTrue($result);
        $this->assertEquals('Alexandr', $name);
    }

    public function testLateCreation()
    {
        $action = new Action();

        $action->set(function () {
            return false;
        });

        $result = $action->run();

        $this->assertFalse($result);
    }


    public function testRunExeptions()
    {
        $action = new Action();

        $action->set(function () {
            try {
                throw new Exception();
            } catch (Exception $exception) {
                return true;
            }
        });

        $result = $action->run();

        $this->assertTrue($result);
    }

    public function testFallExeptions()
    {
        $action = new Action();

        $action->fallBack(function () {
            try {
                throw new Exception();
            } catch (Exception $exception) {
                return true;
            }
        });

        $action->set(function () {
            throw new Exception();
        });

        $result = $action->run();

        $this->assertTrue($result);
    }


    public function testDownAction()
    {
        $result = Action::make(function () {
            throw new Exception();
        }, 'default')
            ->fallBack(function () {
                throw new Exception();
            })
            ->fallBack(function () {
                throw new Error();
            })
            ->run();

        $this->assertEquals($result, 'default');
    }

    public function testInvokeAction()
    {
        $class = new class() {

            /**
             * @return bool
             */
            public function __invoke(): bool
            {
                return true;
            }
        };

        $result = Action::make(function () {
            throw new Exception();
        })->fallBack($class)
            ->run();

        $this->assertTrue($result);

    }

    /**
     * @return bool
     */
    private function returnAlwaysTrue(): bool
    {
        return true;
    }
}
