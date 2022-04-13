<?php

namespace Tests;

use App\Models\User;
use ArrayAccess;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use PHPUnit\Framework\Constraint\ArrayHasKey;
use PHPUnit\Framework\InvalidArgumentException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function authorize()
    {
        $user = User::first();
        $this->actingAs($user);
    }

    /***
     * @param array $array
     * @param array $keys
     */
    public function assertArrayHasKeys(array $array, array $keys)
    {
        foreach ($keys as $key) {
            self::assertArrayHasKey($key, $array);
        }
    }

    public static function assertArrayHasKey($key, $array, string $message = ''): void
    {
        if (!(is_int($key) || is_string($key))) {
            throw InvalidArgumentException::create(
                1,
                'integer or string'
            );
        }

        if (!(is_array($array) || $array instanceof ArrayAccess)) {
            throw InvalidArgumentException::create(
                2,
                'array or ArrayAccess'
            );
        }

        $constraint = new ArrayHasKey($key);

        static::assertThat($array, $constraint, $message);
    }
}
