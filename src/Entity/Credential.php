<?php

namespace PRReviewWatcher\Entity;

class Credential
{
    /**
     * @var int
     */
    private $idCred;
    /**
     * @var string
     */
    private $nameCred;
    /**
     * @var string
     */
    private $token;

    public function getIdCred()
    {
        return $this->idCred;
    }

    public function setIdCred($idCred)
    {
        $this->idCred = $idCred;
    }

    public function getNameCred()
    {
        return $this->nameCred;
    }

    public function setNameCred($nameCred)
    {
        $this->nameCred = $nameCred;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }
}
