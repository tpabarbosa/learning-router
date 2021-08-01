<?php

function path()
{
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $position = strpos($path, '?');
    if ($position === false) {
        return $path;
    }

    return substr($path, 0, $position);
}

function method()
{
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    return strtolower($method);
}

