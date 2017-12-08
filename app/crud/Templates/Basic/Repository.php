<?php

namespace UW\Core\Crud\Templates;

use UW\Core\Crud\ITemplate;

class Repository extends Base implements ITemplate {
    public function generate($name) {
        $namespace = new \Nette\PhpGenerator\PhpNamespace("Repository");
        
        $namespace->addUse('UW\Core\ORM\Repository');
        $namespace->addUse('Kdyby\Doctrine\EntityManager');
        $namespace->addUse('Kdyby\Doctrine\EntityDao');

        $lower = lcfirst($name);
        
        $class = $namespace->addClass($name);
        
        $class
                ->setExtends('UW\Core\ORM\Repository')
                ->addComment($name . " Repository class")
                ->addComment("");

        $class->addProperty("entityManager")->setVisibility("private")->addComment("@var EntityManager");
        $class->addProperty("dao")->setVisibility("private")->addComment("@var EntityDao");

        $class->addMethod("__construct")
            ->setVisibility("public")
            ->addComment("$name constructor.")
            ->addComment("@param EntityManager \$entityManager")
            ->addBody("\$this->entityManager = \$entityManager;\n\$this->dao = \$this->entityManager->getRepository(\\Entity\\$name::getClassName());")

            ->addParameter("entityManager")->setTypeHint("Kdyby\Doctrine\EntityManager");

        $class->addMethod("getEntityManager")
                ->setVisibility("protected")
                ->addComment("@return EntityManager")
                ->addBody('return $this->entityManager;');

        $class->addMethod("getDao")
                ->setVisibility("public")
                ->addComment("@return EntityDao")
                ->addBody('return $this->dao;');

        $this->createTemplate((string) $namespace, $name);
    }
}
