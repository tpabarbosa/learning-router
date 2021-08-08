<?php

namespace tpab\Router;

class Route
{
    private const PARAM_REGEX = '/{(\w+):(\[.+?]\+?)}/';
    private const PARAM = '/{(\w+)}/';
    public const VERBS = ['GET', 'POST', 'PUT', 'UPDATE', 'DELETE', 'PATCH', 'OPTIONS'];

    /**
     * Path to this route.
     *
     * @var string $path
     */

    private $path;

    private $regex_path;

    private $methods = array();

    private $path_parts = array();

    private $callbacks = array();

    public function __construct($methods, string $path, $callback, array $callback_params=[])
    {
        $this->path = self::validatePath($path);
        $this->path_parts = self::setParts($path);
        $this->regex_path = self::setRegex($path);
        $this->addMethods($methods, $callback, $callback_params);
    }

    public function addMethods($methods, $callback, $callback_params)
    {
        if (is_string($methods)) {
            $methods = [$methods];
        }
        $methods = $this->validateMethods($methods);
        $callback = self::validateCallback($callback);
        $this->methods = array_merge($this->methods, $methods);

        $this->callbacks[] = ['methods' => $methods, 'callback_params' => $callback_params, 'callback' => $callback];
    }


    private static function validateCallback($callback)
    {
        if (! is_callable($callback) && ! is_string($callback)) {
            throw new \Exception("Callback must be a callable or a string.");
        }

        return $callback;
    }

    private function validateMethods($methods)
    {
        if (empty($methods)) {
            throw new \Exception("Route method cannot be empty.");
        }
        if (! is_array($methods)) {
            throw new \Exception("Route methods must be strings inside the array.");
        }

        foreach ($methods as $method) {
            if (! is_string($method)) {
                throw new \Exception("Route methods must be strings inside the array.");
            }
            if (! in_array(self::toUpper($method), self::VERBS)) {
                throw new \Exception("Unknown method: $method.");
            }
            if (in_array(self::toUpper($method), $this->methods)) {
                throw new \Exception("Method $method already exists.");
            }
        }

        $methods = array_map('strtoupper', $methods);
        return $methods;
    }

    private static function validatePath(string $path)
    {
        if (empty($path)) {
            throw new \Exception("Route path cannot be empty.");
        }
        return $path;
    }

    private static function setParts($path)
    {
        $parts = explode('/', ltrim($path, '/'));
        preg_match_all(self::PARAM_REGEX, $path, $matchesA);
        preg_match_all(self::PARAM, $path, $matchesB);
        $parts = array_map(function ($part) use ($matchesA, $matchesB) {
            $part = self::setPart($part, $matchesA);
            $part = self::setPart($part, $matchesB);
            return $part;
        }, $parts);
        return $parts;
    }

    private static function setPart($part, $matches)
    {
        if (isset($matches[0]) && !empty($matches[0])) {
            for ($match = 0; $match <= count($matches[0])-1; $match++) {
                if ($part === $matches[0][$match]) {
                    $part = $matches[1][$match];
                }
            }
        }
        return $part;
    }

    private static function setRegex($path)
    {
        $regex = $path;
        $regex = preg_replace(self::PARAM_REGEX, '$2', $regex);
        $regex = preg_replace(self::PARAM, '[\w]+', $regex);
        $regex = str_replace('/', '\/', $regex);
        $regex = '/^' . $regex . '\z/';
        return $regex;
    }

    public function path()
    {
        return $this->path;
    }

    public function parts()
    {
        return $this->path_parts;
    }

    public function regex()
    {
        return $this->regex_path;
    }

    public function methods()
    {
        return $this->methods;
    }

    public function callback($method)
    {
        $method = self::toUpper($method);
        $callback = $this->filterCallback($method);
        return $callback['callback'];
    }

    private function filterCallback($method)
    {
        $callback = array_filter($this->callbacks, function ($callback) use ($method) {
            if (in_array($method, $callback['methods'])) {
                return true;
            }
            return false;
        });
        return reset($callback);
    }

    public function callbackParams($method)
    {
        $method = self::toUpper($method);
        $callback = $this->filterCallback($method);
        return $callback['callback_params'];
    }

    public function hasMethod($method)
    {
        $method = self::toUpper($method);
        return in_array($method, $this->methods);
    }

    private static function toUpper($string)
    {
        return strtoupper($string);
    }
}
