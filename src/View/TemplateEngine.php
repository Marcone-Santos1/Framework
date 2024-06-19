<?php

namespace MiniRestFramework\View;

use Exception;

class TemplateEngine {
    protected $variables = [];
    protected $directives = [];
    protected $openDelimiter = '{{';
    protected $closeDelimiter = '}}';
    private string $templateDirectory;

    public function __construct($templateDirectory = 'views') {
        $this->templateDirectory = rtrim($templateDirectory, '/') . '/';
        $this->loadDirectives();
    }

    protected function loadDirectives() {
        $directivesConfig = require __DIR__ . '/../config/directives.php';

        foreach ($directivesConfig as $name => $config) {
            $this->addDirective($name, $config['callback'], $config['pattern']);
        }
    }

    public function assign($key, $value) {
        $this->variables[$key] = $value;
    }

    public function addDirective($name, $callback, $pattern) {
        $this->directives[$name] = [
            'callback' => $callback,
            'pattern' => $pattern,
        ];
    }

    public function setDelimiters($open, $close) {
        $this->openDelimiter = $open;
        $this->closeDelimiter = $close;
    }

    /**
     * @throws Exception
     */
    public function render($template, $variables = []): bool|string
    {
        $filePath = $this->templateDirectory . $template . '.view.php';
        if (!file_exists($filePath)) {
            throw new Exception("Template file not found: $filePath");
        }

        $content = file_get_contents($filePath);
        $content = $this->processDirectives($content);
        extract(array_merge($this->variables, $variables));
        ob_start();
        eval('?>' . $content);
        return ob_get_clean();
    }

    protected function processDirectives($content) {
        // Substituições para variáveis Blade {{ $variavel }}
        $content = preg_replace_callback(
            '/{{\s*(\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*}}/',
            function ($matches) {
                return '<?php echo htmlspecialchars(' . $matches[1] . ', ENT_QUOTES, \'UTF-8\'); ?>';
            },
            $content
        );

        // Substituições para estruturas de controle Blade
        foreach ($this->directives as $name => $config) {
            $content = preg_replace_callback(
                $config['pattern'],
                function ($matches) use ($config) {
                    $expression = isset($matches[1]) ? trim($matches[1]) : '';
                    return call_user_func($config['callback'], $expression);
                },
                $content
            );
        }

        // Adicionar substituição para @else
        $content = preg_replace('/@else\s*/', '<?php else: ?>', $content);
        // Adicionar substituição para @endif
        return preg_replace('/@endif\s*/', '<?php endif; ?>', $content);
    }
}
