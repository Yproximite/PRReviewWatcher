<?php

namespace PRReviewWatcher\Entity;

use Doctrine\DBAL\Connection;

class Repository
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    protected function getDb()
    {
        return $this->db;
    }

    protected function buildDomainObject($row)
    {
    }

    public function close()
    {
        $this->db->close();
    }

    public function __destruct()
    {
        $this->close();
    }
}
