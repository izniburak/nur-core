<?php

namespace Nur\Facades;

use Nur\Kernel\Facade;

/**
 * @method static void rules(array $rules)
 * @method static void rule(string $field, string $label, string $rules, array $text = [])
 * @method static bool isValid(array $data = [])
 * @method static array errors()
 * @method static mixed sanitize()
 *
 * @see \Nur\Http\Validation
 */
class Validation extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Nur\Http\Validation::class;
    }
}