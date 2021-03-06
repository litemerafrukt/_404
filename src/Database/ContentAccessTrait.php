<?php

namespace _404\Database;

use PDO;

trait ContentAccessTrait
{

    public function searchPost($slug)
    {
        $sql = "SELECT * FROM oophp_content
            WHERE `slug` = '$slug' AND `type`='post'
            AND published <= NOW()
            AND (deleted IS NULL OR deleted > NOW())
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_OBJ);
    }

    public function searchPage($path)
    {
        $sql = "SELECT * FROM oophp_content
            WHERE `path` = '$path' AND `type`='page'
            AND published <= NOW()
            AND (deleted IS NULL OR deleted > NOW())
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Get first published block by title that isn't deleted
     *
     * @param string
     * @return \stdClass
     */
    public function getBlock($title)
    {
        $sql = "SELECT * FROM oophp_content
            WHERE published <= NOW()
            AND (deleted IS NULL OR deleted > NOW())
            AND type = 'block'
            AND `title` = '$title'
            ORDER BY published
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_OBJ);
    }

    /**
    * Get all content.
    *
    * @return array
    */
    public function all()
    {
        $sql = "SELECT * FROM oophp_content ORDER BY 'id'";

        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
    * Get all published content of type.
    *
    * @param string
    * @return array
    */
    public function allPublished($type)
    {
        $sql = "SELECT * FROM oophp_content
            WHERE published <= NOW()
            AND (deleted IS NULL OR deleted > NOW())
            AND type = '$type'
            ORDER BY published
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
    * Fetch a content row by id.
    *
    * @param int
    * @return \stdClass
    */
    public function fetch($id)
    {
        $sql = "SELECT * FROM oophp_content WHERE id LIKE '$id'";

        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_OBJ);
    }
}
