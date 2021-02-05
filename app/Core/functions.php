<?php

use App\Core\Resource;
use App\Models\Option;
use App\Core\ResourceManager;
use App\Core\OptionManager;
use App\Core\ReactionManager;

/**
 * @param string $handle
 * @param string $src
 * @param int $order
 * @param array $deps
 * @return Resource|null
 */
function enqueue_script(string $handle, string $src, int $order, array $deps = []): ?Resource
{
    return ResourceManager::addResource($handle, $src, Resource::RESOURCE_TYPE_SCRIPT, $order, $deps);
}

/**
 * @param string $handle
 * @param string $src
 * @param int $order
 * @param array $deps
 * @return Resource|null
 */
function enqueue_style(string $handle, string $src, int $order, array $deps = []): ?Resource
{
    return ResourceManager::addResource($handle, $src, Resource::RESOURCE_TYPE_STYLE, $order, $deps);
}

/**
 * @return void
 */
function get_footer() : void {
    echo ResourceManager::renderResources();
}

/**
 * @return void
 */
function get_header() : void {

    $html = '
    <meta charset="utf-8">
    <meta name="csrf-token" content="' . csrf_token() . '">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <meta name="generator" content="EasyCms 0.0.1">
    <title>EasyCms</title>';

    $themeFile = 'css/theme.min.css';
    $theme = file_exists(public_path($themeFile));
    if (!$theme) {
        $themeFile = 'css/theme.css';
        $theme = file_exists(public_path($themeFile));
    }

    if ($theme) {
        $html .= "<link rel='stylesheet' id='theme-style' href='/" . $themeFile ."'>\n";
    }

    $themeFile = 'js/theme.min.js';
    $theme = file_exists(public_path($themeFile));
    if (!$theme) {
        $themeFile = 'js/theme.js';
        $theme = file_exists(public_path($themeFile));
    }

    if ($theme) {
        $html .= "<script id='theme-script' src='/" . $themeFile ."'></script>\n";
    }

    echo $html;
}

/**
 * @param string $name
 * @return array<Option>
 */
function get_option(string $name) : array {
   return OptionManager::getOption($name);
}

/**
 * @param string $name
 * @param array $fields
 * @return void
 */
function update_option(string $name, array $fields) : void {
    OptionManager::updateOption($name, $fields);
}

/**
 * @param string $name
 * @param $handler
 * @param int $priority
 * @param int $argsCount
 * @return void
 */
function add_reaction(string $name, $handler, int $priority, int $argsCount) : void {
    ReactionManager::addReaction($name, $handler, $priority, $argsCount);
}

/**
 * @param string $name
 * @param mixed ...$args
 * @return mixed
 */
function apply_reaction(string $name, ...$args) {
    return ReactionManager::applyReaction(func_get_args());
}
