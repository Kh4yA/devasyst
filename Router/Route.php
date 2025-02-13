<?php

namespace App\Router;

#[\Attribute] // Ceci transforme cette classe en attribut PHP 8+
class Route
{
    public string $path;
    public array $methods;

    public function __construct(string $path, array $methods = ['GET'])
    {
        $this->path = $path;
        $this->methods = $methods;
    }
}