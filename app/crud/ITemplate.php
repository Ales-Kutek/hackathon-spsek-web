<?php

namespace UW\Core\Crud;

interface ITemplate {

    public function __construct(array $config);

    public function getConfig() : array;

    public function generate($name);
    
    public function getTargetPath($name);
    
    public function createTemplate($content, $name);
}
