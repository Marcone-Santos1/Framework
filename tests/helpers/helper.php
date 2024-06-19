<?php

use MiniRestFramework\View\TemplateEngine;

if (!function_exists("view")) {
    function view($template, $variables = [], $templateDirectory = 'views') {
        $templateEngine = new TemplateEngine($templateDirectory);

        return $templateEngine->render($template, $variables);
    }
}

if (!function_exists("dd")) {
    /**
     * Dump and Die - Output the given variables and terminate the script.
     *
     * @param mixed ...$vars Variables to dump
     * @return void
     */
    function dd(...$vars) {
        foreach ($vars as $var) {
            var_dump($var);
        }
        die();
    }

}

if (!function_exists("dump")) {
    /**
     * Dump - Output the given variables without terminating the script.
     *
     * @param mixed ...$vars Variables to dump
     * @return void
     */
    function dump(...$vars) {
        foreach ($vars as $var) {
            var_dump($var);
        }
    }
}
