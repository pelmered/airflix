<?php

namespace Support\BaseClasses;

use App\Rules\IsValidEnum;
use Illuminate\Support\Str;

abstract class BaseEnum
{
    public static function getFormattedOptions(?array $enumKeys = null): ?array
    {
        if (empty($enumKeys)) {
            if (is_array($enumKeys)) {
                return null;
            }

            $enumKeys = static::optionKeys();
        }

        return array_map(fn ($enumKey) => static::getFormattedOption($enumKey), $enumKeys);
    }

    public static function only(array $only): ?array
    {
        $options = static::options();
        $filtered = [];

        foreach ($only as $key) {
            if (array_key_exists($key, $options)) {
                $filtered[$key] = $options[$key];
            }
        }

        return $filtered;
    }

    public static function filteredOptions(callable $filter): ?array
    {
        return array_filter(static::optionKeys(), $filter);
    }

    /**
     * @return array<(int|string)>
     *
     * @psalm-return list<array-key>
     */
    public static function optionKeys(): array
    {
        return array_keys(static::options());
    }

    abstract public static function options();

    /**
     * @param  string|null  $enumKey
     *
     * @return array<(mixed|null)>
     *
     * @psalm-return array{key: mixed|null, name: mixed|null}
     */
    public static function getFormattedOption(?string $enumKey): array
    {
        if (empty($enumKey)) {
            return [
                'key' => null,
                'name' => null,
            ];
        }

        return [
            'key' => $enumKey,
            'name' => static::optionName($enumKey),
        ];
    }

    public static function optionName(string $enumValue)
    {
        return static::options()[$enumValue];
    }

    public static function optionNames(array $enumValues): array
    {
        return array_map(fn ($enumValue) => static::options()[$enumValue], $enumValues);
    }

    public static function lowercaseName($enumValue): string
    {
        return Str::lower(static::optionName($enumValue));
    }

    /**
     * @return array<(int|string)>
     *
     * @psalm-return list<array-key>
     */
    public static function all(): array
    {
        return array_keys(static::options());
    }

    public static function validationRule(): IsValidEnum
    {
        return new IsValidEnum(new static());
    }
}
