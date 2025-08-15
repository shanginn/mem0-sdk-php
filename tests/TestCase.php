<?php

namespace Tests;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    private static Generator $faker;

    protected static function fake(): Generator
    {
        if (!isset(static::$faker)) {
            static::$faker = Factory::create();
        }

        return static::$faker;
    }
}
