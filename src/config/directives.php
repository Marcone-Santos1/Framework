<?php

return [
    'if' => [
        'pattern' => '/@if\s*\((.*s?)\)\s*/',
        'callback' => function($expression) {
            return "<?php if ($expression): ?>";
        }
    ],
    'elseif' => [
        'pattern' => '/@elseif\s*\((.*s?)\)\s*/',
        'callback' => function($expression) {
            return "<?php elseif ($expression): ?>";
        }
    ],
    'else' => [
        'pattern' => '/@else\s*/',
        'callback' => function() {
            return "<?php else: ?>";
        }
    ],
    'endif' => [
        'pattern' => '/@endif\s*/sU',
        'callback' => function() {
            return "<?php endif; ?>";
        }
    ],
    'foreach' => [
        'pattern' => '/@foreach\s*\((.*s?)\)\s*/',
        'callback' => function($expression) {
            return "<?php foreach ($expression): ?>";
        }
    ],
    'endforeach' => [
        'pattern' => '/@endforeach\s*/',
        'callback' => function() {
            return "<?php endforeach; ?>";
        }
    ],
    'include' => [
        'pattern' => '/@include\s*\((.*s?)\)\s*/',
        'callback' => function($expression) {
            return "<?php include($expression); ?>";
        }
    ],
    'dd' => [
        'pattern' => '/@dd\s*\((.*s?)\)\s*/',
        'callback' => function($expression) {
            return "<?php dd($expression); ?>";
        }
    ],
    'dump' => [
        'pattern' => '/@dump\s*\((.*s?)\)\s*/',
        'callback' => function($expression) {
            return "<?php dump($expression); ?>";
        }
    ],
];
