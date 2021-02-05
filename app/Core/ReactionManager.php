<?php


namespace App\Core;


class ReactionManager
{
    private static array $reactionsNames = [];
    private static array $reactions = [];

    /**
     * @param string $name
     * @param mixed $handler
     * @param int $priority
     * @param int $argsCount
     * @return void
     */
    public static function addReaction(string $name, $handler, int $priority, int $argsCount) : void {
        if (is_array($handler)) {
            if (!method_exists($handler[0], $handler[1])) return;

            self::$reactionsNames[] = (string)$handler[0] . $handler[1];
        }else {
            if (!function_exists($handler)) return;

            self::$reactionsNames[] = $handler;
        }
    }

    /**
     * @param array $args
     * @return mixed|false
     */
    public static function applyReaction(array $args) : bool {
        if (count($args) <= 0) return false;

        $name = $args[0];

        if (!self::existReaction($name)) return false;

        return call_user_func_array($name, (array_shift($args) === null ? [] : $args));
    }

    /**
     * @param mixed $name
     * @return bool
     */
    public static function existReaction($name) : bool {
        $handlerName = '';

        if (is_array($name)) {
            $handlerName = (string)$name[0] . $name[1];
        }else {
            $handlerName = $name;
        }

        return in_array($handlerName, self::$reactionsNames);
    }

    /**
     * @return array
     */
    public static function getReactions(): array
    {
        return self::$reactions;
    }
}
