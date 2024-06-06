<?php

namespace MiniRestFramework\DI;

class Container {
    private array $instances = [];
    private array $bindings = [];

    /**
     * Resolve uma instância do serviço solicitado.
     *
     * @param string|object $abstract A interface ou classe a ser resolvida.
     * @return mixed A instância resolvida.
     * @throws \Exception Se o serviço não for encontrado ou não puder ser instanciado.
     */
    public function make($abstract) {
        if (is_string($abstract)) {
            if (!class_exists($abstract)) {
                throw new \Exception("Class {$abstract} not found.");
            }
            $reflection = new \ReflectionClass($abstract);
        } elseif (is_object($abstract)) {
            $reflection = new \ReflectionClass($abstract);
        } else {
            throw new \Exception("Invalid type provided for make method.");
        }

        if (!$reflection->isInstantiable()) {
            throw new \Exception("Class {$abstract} cannot be instantiated.");
        }

        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            return $this->instances[$abstract] = $reflection->newInstance();
        }

        $parameters = $constructor->getParameters();
        $dependencies = [];

        foreach ($parameters as $param) {
            $dependencyClass = $param->getType() ? $param->getType()->getName() : null;
            if ($dependencyClass) {
                $dependencies[] = $this->make($dependencyClass);
            } else {
                throw new \Exception("Unable to resolve dependency for parameter {$param->getName()} in class {$abstract}.");
            }
        }

        return $this->instances[$abstract] = $reflection->newInstanceArgs($dependencies);
    }
}
