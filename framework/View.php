<?php namespace framework;

use framework\vendor\LightnCandy;

/**
 * Class View
 * @package common
 */
final class View
{

    const VIEW_PATH = '/../application/view';

    public function __construct($template)
    {

    }

    public static function render($template, $scope = [])
    {

        $renderer = self::loadRenderer($template);

        if (!$renderer || !is_callable($renderer)) {
            return 'Template not found: ' . $template . "\n";
        }

        return $renderer($scope);

    }

    protected static function compileTemplate($template)
    {

        if (!self::templateExists($template)) {
            return false;
        }

        if (false === file_put_contents(self::getRendererPath($template), LightnCandy::compile(self::loadTemplate($template)))) {
            throw new \Exception ("Can not save compiled templates...");
        }

        return true;
    }

    protected static function loadTemplate($template)
    {
        return self::templateExists($template) ? file_get_contents(self::getTplPath($template)) : '';
    }

    protected static function templateExists($template)
    {
        return file_exists(self::getTplPath($template));
    }

    protected static function compiledRendererExists($template)
    {
        return file_exists(self::getRendererPath($template));
    }

    protected static function getTplPath($template)
    {
        return __DIR__ . self::VIEW_PATH . '/templates/' . $template . '.tpl';
    }

    protected static function getRendererPath($template)
    {
        return __DIR__ . self::VIEW_PATH . '/compiled/' . $template . '.php';
    }

    protected static function loadRenderer($template)
    {

        $renderer_exists = true;

        if (!self::compiledRendererExists($template)) {
            $renderer_exists = self::compileTemplate($template);
        }

        return $renderer_exists ? self::getCompiledRenderer($template) : false;
    }

    protected static function getCompiledRenderer($template)
    {
        return self::compiledRendererExists($template) ? include(self::getRendererPath($template)) : false;
    }
}