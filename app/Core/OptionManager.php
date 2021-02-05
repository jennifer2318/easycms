<?php


namespace App\Core;

use App\Models\Option;

class OptionManager
{
    private static array $options = [];

    /**
     * @return void
     */
    public static function boot() : void {
        foreach (Option::where('autoload', '=', 1)->get() as $option) {
            self::$options[$option->getName()] = $option->toArray();
        }
    }

    /**
     * @param array $args
     * @return Option
     */
    public static function getOptionBy(array $args) : Option {
        return Option::where($args)->first();
    }

    /**
     * @param string $name
     * @return array
     */
    public static function getOption(string $name) : array {
        if (array_key_exists($name, self::$options)) return self::$options[$name];

        return self::getOptionBy(['name', '=', $name])->toArray();
    }

    /**
     * @param int $id
     * @return Option
     */
    public static function getOptionById(int $id) : Option {
        return Option::find($id);
    }

    /**
     * @param string $name
     * @param array $fields
     * @return bool
     */
    public static function updateOption(string $name, array $fields) : bool {
        $option = self::getOptionBy(['name', '=', $name]);

        foreach ($fields as $fieldName => $fieldValue) {
            $func = 'set' . ucfirst($fieldName);

            if (method_exists($option, $func)) {
                $option->{$func}($fieldValue);
            }
        }

        return $option->save();
    }

    /**
     * @return array
     */
    public static function getOptions(): array
    {
        return self::$options;
    }
}
