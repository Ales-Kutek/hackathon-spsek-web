<?php

namespace UW\Core\Crud;

class Crud {

    /** @var array $config */
    private $config;

    /** @var array $templates */
    private $templates;

    /** @var \Nette\DI\Container $container */
    private $container;

    public function __construct($config, \Nette\DI\Container $container) {
        $this->config = $config;
        $this->container = $container;
    }

    protected function addTemplate(ITemplate $template) {
        $this->templates[] = $template;
    }

    protected function getTemplates() {
        return $this->templates;
    }
    
    public function templateExists($name) {
        foreach ($this->getTemplates() as $temp) {
            
            echo $temp->getTargetPath($name) . "\n";
            
            if (file_exists($temp->getTargetPath($name))) {
                
                echo "!!! TEMPLATE ALREADY EXIST! !!!\n";
                
                return TRUE;
            }
        }
        
        return FALSE;
    }

    public function init(string $name, string $variant) {
        $this->templates = array();

        foreach ($this->config["settings"]["templates"][$variant] as $k => $v) {
            $this->addTemplate(new $k($v));
        }

        // pro jistotu zvetsit prvni pismenko, at nam pak nezlobi news.php / News.php na linuxu
        $name = \Nette\Utils\Strings::firstUpper($name);
        
        if ($this->templateExists($name) === FALSE) {
            foreach ($this->getTemplates() as $temp) {
                $temp->generate($name);
            }
        }
    }

    public function delete(string $name, string $variant) {
        $this->templates = array();

        foreach ($this->config["settings"]["templates"][$variant] as $k => $v) {
            $this->addTemplate(new $k($v));
        }

        foreach ($this->getTemplates() as $temp) {
            if (file_exists($temp->getTargetPath($name))) {
                unlink($temp->getTargetPath($name));
            }
        }
    }
}
