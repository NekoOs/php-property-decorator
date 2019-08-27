<?php


namespace NekoOs\Pood\Support\Contracts;


interface ReadPropertyable
{
    public function __get($name);

    public function __isset($name);
}
