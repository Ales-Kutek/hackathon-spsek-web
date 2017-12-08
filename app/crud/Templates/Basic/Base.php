<?php

namespace UW\Core\Crud\Templates;

abstract class Base {
    protected $target_path;

    protected $config;
    
    public function __construct(array $config) {
        $this->target_path = $config["path"];
        $this->config = $config;
    }
    
    public function createTemplate($content, $name) {
        $this->createFolders($this->getTargetPath($name));
        
        file_put_contents(
                $this->getTargetPath($name),
                ($this->isPHP(($this->getTargetPath($name))) ? "<?php\n\n" : "") . $content);
    }
    
    public function isPHP($path) {
        $temp = explode(".", $path);
        
        if (end($temp) == "php") {
            return TRUE;
        }
        
        return FALSE;
    }

    protected function createFolders($path) {
        $path = str_replace("\\", "/", $path);
        
        $dir = explode("/", $path);
        
        unset($dir[count($dir) - 1]);
        
        $dir = implode("/", $dir);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, TRUE);
        }
    }

    public function getFullName($name) {
        if (isset($this->config["name"]) === FALSE) {
            return $name;
        }

        $newName = str_replace('{$name}', $name, $this->config["name"]);

        return $newName;
    }

    public function getTargetPath($name) {
        $path = str_replace('{$name}', $this->getFullName($name), $this->target_path);

        return $path;
    }

    public function getConfig() : array
    {
        return $this->config;
    }
}