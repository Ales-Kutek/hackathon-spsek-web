<?php

namespace UW\Core\Crud\Templates;

use UW\Core\Crud\ITemplate;

class Entity extends Base implements ITemplate {
    public function generate($name) {
        $namespace = new \Nette\PhpGenerator\PhpNamespace("Entity");
        
        $namespace->addUse('Doctrine\ORM\Mapping as ORM');
        $namespace->addUse('Doctrine\Common\Collections\ArrayCollection');
        $namespace->addUse('Doctrine\ORM\Mapping\ManyToMany');
        $namespace->addUse('Doctrine\ORM\Mapping\ManyToOne');
        $namespace->addUse('Doctrine\ORM\Mapping\OneToMany');
        $namespace->addUse('Doctrine\ORM\Mapping\OneToOne');
        $namespace->addUse('Doctrine\Common\Collections\Criteria');
        $namespace->addUse('Doctrine\ORM\Mapping\JoinColumn');
        $namespace->addUse('Doctrine\ORM\Mapping\JoinTable');
        $namespace->addUse('Gedmo\Mapping\Annotation as Gedmo');
        $namespace->addUse('UW\Core\ORM\TIdentifer');

        $class = $namespace->addClass($name);
        
        $class
                ->setExtends('\UW\Core\ORM\BaseEntity')
                ->addComment($name)
                ->addComment("")
                ->addComment("@ORM\HasLifecycleCallbacks()")
                ->addComment("@ORM\Entity()");

        $class->addTrait("UW\Core\ORM\TIdentifer");

        $class->addProperty("title")
                    ->setVisibility("protected")
                    ->addComment("@ORM\Column(type=\"string\")");
        
        $this->createTemplate((string) $namespace, $name);
    }
}
