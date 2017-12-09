<?php
/**
 * Created by PhpStorm.
 * User: ales
 * Date: 09/12/17
 * Time: 11:42
 */

namespace AdminModule\Presenters;


use Repository\Visitor;

class RequestPresenter extends BasePresenter
{
    /** @var Visitor @inject */
    public $user;

    public function handleName($name, $score = 0)
    {
        $entity = $this->user->getSingleOrNullBy(array("title" => $name));

        if ($entity !== NULL) {
            $this->user->updateForm(array("title" => $name, "hash_code" => rand(1, 2000), "id" => $entity->getId(), "score" => $score));
        } else {
            $this->user->insertForm(array("title" => $name, "hash_code" => rand(1, 2000), "score" => $score));
        }

        exit();
    }
}