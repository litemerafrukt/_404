<?php

namespace _404\Database;

use PDO;

class Blog
{
    private $db;

    /**
     * Thou Constructor
     *
     * @param $dbConnection DatabaseConnection
     */
    public function __construct(DatabaseConnection $dbConnection)
    {
        $this->db = $dbConnection->getPDO();
    }

    /**
     * Get all published blog posts. Sorted on published date.
     *
     * @return array
     */
    public function allPublishedPosts()
    {
        $sql = "
            SELECT * FROM oophp_content
            WHERE `type` LIKE 'post' 
            ORDER BY 'published'
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
}
