<?php

namespace PRReviewWatcher\Entity;

class CredentialRepository extends Repository
{
    /**
     * Find every credential
     *
     * @return array
     */
    public function findAll()
    {
        $sql    = 'SELECT * FROM credential ORDER BY idCred DESC';
        $result = $this->db->prepare($sql);
        $result->execute();
        $result      = $result->fetchAll();
        $credentials = array();

        foreach ($result as $row) {
            $credentialId               = $row['idCred'];
            $credentials[$credentialId] = $this->buildDomainObject($row);
        }

        return $credentials;
    }

    /**
     * @param integer $id
     *
     * @return Credential
     */
    public function find($id)
    {
        $sql = 'SELECT * FROM credential WHERE idCred= :idCred';
        $row = $this->db->prepare($sql);
        $row->bindValue(':idCred', $id, SQLITE3_TEXT);
        $row->execute();
        $row = $this->db->fetchAssoc($sql, array($id));

        return $this->buildDomainObject($row);
    }

    /**
     * Save credential into db
     *
     * @param Credential $credential
     */
    public function save(Credential $credential)
    {
        $credentialData = array(
            'nameCred' => $credential->getNameCred(),
            'token'    => $credential->getToken(),
        );
        if ($credential->getIdCred()) {
            $this->getDb()->update('credential', $credentialData, array('idCred' => $credential->getIdCred()));
        } else {
            $this->getDb()->insert('credential', $credentialData);
        }
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $this->getDb()->delete('credential', array('idCred' => $id));
    }

    /**
     * @return array
     */
    public function findAllAsArray()
    {
        $sql    = 'SELECT idCred, nameCred FROM credential ORDER BY idCred DESC';
        $result = $this->db->prepare($sql);
        $result->execute();
        $result      = $result->fetchAll();
        $credentials = array();

        foreach ($result as $row) {
            $id               = $row['idCred'];
            $credentials[$id] = $row['nameCred'];
        }

        return $credentials;
    }

    /**
     * @param $repoHook
     * @param $branchHook
     *
     * @return \Doctrine\DBAL\Driver\Statement|mixed
     */
    public function findToken($repoHook, $branchHook)
    {
        if ($branchHook == null) {
            $sql    = "SELECT token FROM credential AS c JOIN project AS P ON c.idcred = p.credential WHERE p.name = :name AND p.branch = 'all';";
            $result = $this->db->prepare($sql);
            $result->bindValue(':name', $repoHook);
        } else {
            $sql    = "SELECT token FROM credential AS c JOIN project AS P ON c.idcred = p.credential WHERE p.name = :name AND p.branch = :branch;";
            $result = $this->db->prepare($sql);
            $result->bindValue(':name', $repoHook);
            $result->bindValue(':branch', $branchHook);
        }

        $result->execute();
        $result = $result->fetch();
        $result = $result['token'];

        return $result;
    }

    /**
     * @param $repoHook
     *
     * @return \Doctrine\DBAL\Driver\Statement|mixed
     */
    public function findNameCredential($repoHook)
    {
        $sql    = 'SELECT nameCred FROM credential AS c JOIN project AS p ON c.idCred = p.credential AND p.name = :name;';
        $result = $this->db->prepare($sql);
        $result->bindValue(':name', $repoHook);
        $result->execute();
        $result = $result->fetch();
        $result = $result['nameCred'];

        return $result;
    }

    /**
     * @param $row
     * @return Credential
     */
    protected function buildDomainObject($row)
    {
        $credential = new Credential();
        $credential->setIdCred($row['idCred']);
        $credential->setNameCred($row['nameCred']);
        $credential->setToken($row['token']);

        return $credential;
    }
}
