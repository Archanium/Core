<?php


namespace Core;


use Core\Exceptions\External;

class Controller
{
    private $parameters;
    private $routerParameters;

    public function serve()
    {
        $this->loadParameters();

        $method = $_SERVER['REQUEST_METHOD'];
        if ($this->routerParameters) {
            $method .= "_PARAMS";
        }
        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            throw new External("Not implemented - $method", 501);
        }
    }

    private function loadParameters()
    {
        if ($this->parameters === null) {
            $this->parameters = json_decode(file_get_contents('php://input'));
        }
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getParameter($name, $default = null)
    {
        if (isset($this->parameters->$name)) {
            return $this->parameters->$name;
        } elseif ($default !== null) {
            return $default;
        } else {
            return null;
        }
    }

    public function getRouterParameters()
    {
        return $this->routerParameters;
    }

    public function getRouterParameter($name, $default = null)
    {
        if (isset($this->routerParameters[$name])) {
            return $this->routerParameters[$name];
        } elseif ($default !== null) {
            return $default;
        } else {
            return null;
        }
    }

    public function setRouterParameters($params)
    {
        $this->routerParameters = $params;
    }
}