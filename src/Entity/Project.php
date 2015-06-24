<?php

namespace PRReviewWatcher\Entity;

class Project
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $branch;
    /**
     * @var string
     */
    private $credential;
    /**
     * @var string
     */
    private $comment;
    /**
     * @var int
     */
    private $alive;
    /**
     * @var int
     */
    private $numberTaskList;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getBranch()
    {
        return $this->branch;
    }

    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    public function getCredential()
    {
        return $this->credential;
    }

    public function setCredential($credential)
    {
        $this->credential = $credential;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getAlive()
    {
        return (boolean)$this->alive;
    }

    public function setAlive($alive)
    {
        $this->alive = $alive;
    }

    public function getNumberTaskList()
    {
        return $this->numberTaskList;
    }

    public function setNumberTaskList($numberTaskList)
    {
        $this->numberTaskList = $numberTaskList;
    }
}
