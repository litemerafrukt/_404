<?php

namespace _404\Database;

use PDO;

class Content
{
    use ContentAccessTrait;

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
     * Check that id exists in db
     *
     * @param int
     * @return bool
     */
    public function idExists($id)
    {
        $statement = $this->db->prepare("SELECT * FROM oophp_content WHERE id='$id'");
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return !$row ? false : true;
    }

    /**
     * Get id for slug or false
     *
     * @param int
     * @return mixed
     */
    public function idFromSlug($slug)
    {
        $statement = $this->db->prepare("SELECT * FROM oophp_content WHERE `slug`='$slug'");
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_OBJ);
        return $row ? $row->id : false;
    }

    /**
     * Get id for path or false
     *
     * @param int
     * @return mixed
     */
    public function idFromPath($path)
    {
        $statement = $this->db->prepare("SELECT * FROM oophp_content WHERE `path`='$path'");
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_OBJ);
        return $row ? $row->id : false;
    }

    /**
     * Update content with id
     *
     * @param  $id
     * @param  $title
     * @param  $path
     * @param  $slug
     * @param  $published
     * @param  $deleted
     * @param  $filter
     * @param  $data
     * @return void
     */
    public function update($id, $title, $path, $slug, $type, $published, $deleted, $filter, $data)
    {
        $sql = "UPDATE oophp_content
            SET
                `title`=?,
                `path`=?,
                `slug`=?,
                `type`=?,
                `published`=?,
                `deleted`=?,
                `filter`=?,
                `updated`=NOW(),
                `data`=?
            WHERE id='$id'
        ";
        $statement = $this->db->prepare($sql);
        $statement->execute([$title, $path, $slug, $type, $published, $deleted, $filter, $data]);
    }

    /**
     * 'Delete' the content by setting data for deletion to now
     *
     * @param  int $id
     * @return void
     */
    public function delete($id)
    {
        $sql = "UPDATE oophp_content
            SET
                `deleted`=NOW()
            WHERE id='$id'
        ";
        $statement = $this->db->prepare($sql);
        $statement->execute();
    }

    /**
     * Create new content with title and return last insert id.
     *
     * @param  $title
     * @return int
     */
    public function newContent($title)
    {
        $sql = "INSERT INTO oophp_content (title) VALUES ('$title')";

        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $this->db->lastInsertId();
    }
}
