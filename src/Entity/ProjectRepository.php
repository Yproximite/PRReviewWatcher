<?php

namespace PRReviewWatcher\Entity;

class ProjectRepository extends Repository
{
    /**
     * @return array
     */
    public function findAll()
    {
        $sql     = 'SELECT * FROM project AS p LEFT JOIN credential AS c ON p.credential = c.idCred ORDER BY p.id DESC';
        $results = $this->db->prepare($sql);
        $results->execute();
        $results  = $results->fetchAll();
        $projects = array();

        foreach ($results as $row) {
            $projectId            = $row['id'];
            $projects[$projectId] = $this->buildDomainObject($row);
        }

        return $projects;
    }

    /**
     * @param $id
     *
     * @return Project
     */
    public function find($id)
    {
        $sql = 'SELECT p.*,c.idCred FROM project AS p, credential AS c WHERE p.id= :id';
        $row = $this->db->prepare($sql);
        $row->bindValue(':id', $id, SQLITE3_TEXT);
        $row->execute();
        $row = $this->db->fetchAssoc($sql, array($id));

        return $this->buildDomainObject($row);
    }

    /**
     * @param Project $project
     */
    public function save(Project $project)
    {
        $projectData = array(
            'name'       => $project->getName(),
            'branch'     => $project->getBranch(),
            'credential' => $project->getCredential(),
            'comment'    => $project->getComment(),
            'alive'      => $project->getAlive(),
        );

        if ($project->getId()) {
            $projectData['numberTaskList'] = $project->getNumberTaskList();
            $this->getDb()->update('project', $projectData, array('id' => $project->getId()));
        } else {
            $projectData['numberTaskList'] = 0;
            $this->getDb()->insert('project', $projectData);
        }
    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        $this->getDb()->delete('project', array('id' => $id));
    }

    /**
     * @param $repoHook
     *
     * @return array
     */
    public function findBranch($repoHook)
    {
        $sql    = 'SELECT branch FROM project WHERE name = :name AND alive = 1;';
        $result = $this->db->prepare($sql);
        $result->bindValue(':name', $repoHook);
        $result->execute();
        $result   = $result->fetchAll();

        $branches = array();

        foreach ($result as $row) {
            $branch     = $row['branch'];
            $branches[] = $branch;
        }

        return $branches;
    }

    /**
     * @param $repoHook
     * @param $branchHook
     *
     * @return \Doctrine\DBAL\Driver\Statement|mixed
     */
    public function findComment($repoHook, $branchHook)
    {
        if ($branchHook == null) {
            $sql    = 'SELECT comment FROM project WHERE name = :name AND alive = 1;';
            $result = $this->db->prepare($sql);
            $result->bindValue(':name', $repoHook);
        } else {
            $sql    = 'SELECT comment FROM project WHERE name = :name AND branch = :branch AND alive = 1';
            $result = $this->db->prepare($sql);
            $result->bindValue(':name', $repoHook);
            $result->bindValue(':branch', $branchHook);
        }

        $result->execute();
        $result = $result->fetch();
        $result = $result['comment'];

        return $result;
    }

    /**
     * @param $repoHook
     * @param $userHook
     * @param $branchHook
     *
     * @return \Doctrine\DBAL\Driver\Statement|mixed
     */
    public function findId($repoHook, $userHook, $branchHook)
    {
        if ($branchHook == null) {
            $sql    = "SELECT id FROM project AS p , credential AS c WHERE name = :name AND nameCred = :nameCred AND branch = 'all';";
            $result = $this->db->prepare($sql);
            $result->bindValue(':name', $repoHook);
            $result->bindValue(':nameCred', $userHook);
        } else {
            $sql    = "SELECT id FROM project AS p , credential AS c WHERE name = :name AND nameCred = :nameCred AND branch = :branch;";
            $result = $this->db->prepare($sql);
            $result->bindValue(':name', $repoHook);
            $result->bindValue(':nameCred', $userHook);
            $result->bindValue(':branch', $branchHook);
        }

        $result->execute();
        $result = $result->fetch();
        $result = $result['id'];

        return $result;
    }

    /**
     * @param $id
     */
    public function incrementNumber($id)
    {
        $sql    = 'SELECT numberTaskList FROM project WHERE id = :id';
        $result = $this->db->prepare($sql);
        $result->bindValue(':id', $id);
        $result->execute();
        $result = $result->fetch();
        $result = $result['numberTaskList'];
        $result = $result + 1;

        $sql    = 'UPDATE project SET numberTaskList = :numberTaskList where id = :id;';
        $update = $this->db->prepare($sql);
        $update->bindValue(':numberTaskList', $result);
        $update->bindValue(':id', $id);
        $update->execute();
    }

    /**
     * @param $row
     * @return Project
     */
    protected function buildDomainObject($row)
    {
        $credential = new Credential();

        $project = new Project();
        $project->setId($row['id']);
        $project->setName($row['name']);
        $project->setBranch($row['branch']);
        if (array_key_exists('nameCred', $row)) {
            $credential->setNameCred($row['nameCred']);
            $project->setCredential($credential->getNameCred());
        } else {
            $project->setCredential($row['credential']);
        }
        $project->setComment($row['comment']);
        $project->setAlive($row['alive']);
        $project->setNumberTaskList($row['numberTaskList']);

        return $project;
    }
}
