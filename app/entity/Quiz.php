<?php

namespace Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Quiz
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity()
 */
class Quiz extends BaseEntity
{
	/** @ORM\Column(type="string") */
	protected $title;

    /**
     * @OneToMany(targetEntity="QuizQuestion", cascade={"persist"}, mappedBy="quiz")
     * @JoinColumn(name="quiz_question", referencedColumnName="quiz")
     */
    protected $quiz_question = array();

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return Quiz
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

       /**
     * @return mixed
     */
    public function getQuizQuestion()
    {
        return $this->quiz_question;
    }

    /**
     * @param mixed $quiz_question
     * @return Quiz
     */
    public function setQuizQuestion($quiz_question)
    {
        $this->quiz_question = $quiz_question;
        return $this;
    }
}
