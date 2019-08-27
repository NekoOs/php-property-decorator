<?php


namespace NekoOs\Pood\Reflections;

use ArrayAccess;
use Closure;

function default_data_get($target, $key, $default)
{
    if (is_array($target) || $target instanceof ArrayAccess) {
        $response = $target[$key] ?? $default;
    } else {
        $response = $target->$key ?? $default;
    }
    return $response;
}

function default_studly_case(string $value): string
{
    static $cache = [];
    $key = $value;

    if (isset($cache[$key])) {
        return $cache[$key];
    }

    $value = ucwords(str_replace(['-', '_'], ' ', $value));

    return $cache[$key] = str_replace(' ', '', $value);
}

function call($function, ...$arguments)
{
    static $cache = [];

    if (empty($cache[$function])) {
        $cache[$function] = Closure::fromCallable(__NAMESPACE__ . "\\default_$function");

        if (function_exists('custom_nekoos_reflection_functions')) {
            $override = custom_nekoos_reflection_functions();
            $cache = array_merge($override, $cache);
        }

        if (empty($override[$function]) && function_exists($function)) {
            $cache[$function] = $function;
        }
    }
    return call_user_func_array($cache[$function], $arguments);
}

/**
 * Get an item from an array or object with custom function support.
 *
 * @param mixed            $target
 * @param array|int|string $key
 * @param null             $default
 *
 * @return mixed
 */
function data_get($target, $key, $default = null)
{
    return call('data_get', $target, $key, $default);
}

function data_as_dictionary(array $items, $keygen): array
{
    $dictionary = [];

    $isClosure = $keygen instanceof Closure;

    foreach ($items as $key => $item) {
        if ($isClosure) {
            $pair = $keygen($item, $key);
            $root = key($pair);
            $item = reset($pair);
        } else {
            $root = data_get($item, $keygen);
        }
        $dictionary[$root][] = $item;
    }

    return $dictionary;
}

function studly_case(string $value): string
{
    return call('studly_case', $value);
}