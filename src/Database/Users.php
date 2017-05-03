<?php
/**
 * Class Users
 * @package _404\Database
 */

namespace _404\Database;

use PDO;

class Users
{
    private $db;

    /**
     * Constructor
     * @param $dbConnection DatabaseConnection
     */
    public function __construct(DatabaseConnection $dbConnection)
    {
        $this->db = $dbConnection->getPDO();
    }

    /**
     * Adds user to the database
     *
     * @param $user string The name of the user
     * @param $pass string The user's password
     * @return void
     */
    public function addUser($user, $pass, $email, $level)
    {
        $statement = $this->db->prepare("INSERT into oophp_users (username, password, email, userlevel) VALUES ('$user', '$pass', '$email', '$level')");
        $statement->execute();
    }

    /**
     * Gets the hashed password from the database
     *
     * @param $user string The user to get password from/for
     * @return string The hashed password
     */
    public function getHash($user)
    {
        $statement = $this->db->prepare("SELECT password FROM oophp_users WHERE username='$user'");
        $statement->execute();

        $res = $statement->fetch(PDO::FETCH_ASSOC);

        return $res["password"];
    }

    /**
     * Get user level.
     *
     * @param $user string
     * @return int user level
     */
    public function getLevel($user)
    {
        $statement = $this->db->prepare("SELECT userlevel FROM oophp_users WHERE username='$user'");
        $statement->execute();

        $res = $statement->fetch(PDO::FETCH_ASSOC);

        return $res["userlevel"];
    }

    /**
     * Get user details in assoc array
     *
     * @param $user string
     * @return array
     */
    public function getDetails($user)
    {
        $statement = $this->db->prepare("SELECT username, email, userlevel FROM oophp_users WHERE username='$user'");
        $statement->execute();

        $res = $statement->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    /**
     * Set user details
     *
     * @param $user string
     * @param $email string
     * @param $level int
     */
    public function setDetails($user, $email, $level)
    {
        $statement = $this->db->prepare("UPDATE oophp_users SET email='$email', userlevel='$level' WHERE username='$user'");
        $statement->execute();
    }

    /**
     * Changes the password for a user
     *
     * @param $user string The usr to change the password for
     * @param $pass string The password to change to
     * @return void
     */
    public function changePassword($user, $pass)
    {
        $statement = $this->db->prepare("UPDATE oophp_users SET password='$pass' WHERE username='$user'");
        $statement->execute();
    }

    /*
     * Check if user exists in the database
     *
     * @param $user string The user to search for
     * @return bool true if user exists, otherwise false
     */
    public function exists($user)
    {
        $statement = $this->db->prepare("SELECT * FROM oophp_users WHERE username='$user'");
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return !$row ? false : true;
    }

    /**
     * Delete user
     *
     * @param $user string
     */
    public function delete($user)
    {
        $statement = $this->db->prepare("DELETE FROM oophp_users WHERE username='$user'");
        $statement->execute();
    }

    /**
     * Fetch all users
     *
     * @return \stdClass
     */
    public function all()
    {
        $statement = $this->db->prepare("SELECT * FROM oophp_users");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Count all users in db
     *
     * @return int
     */
    public function count()
    {
        $statement = $this->db->prepare("SELECT COUNT(id) FROM oophp_users");
        $statement->execute();
        return $statement->fetch(PDO::FETCH_NUM)[0];
    }

    /**
     * Count users filtered on username or email or userlevel
     *
     * @param $filter
     * @return mixed
     */
    public function countWithFilter($filter)
    {
        $sql = "
            SELECT COUNT(id) FROM oophp_users
            WHERE email LIKE '$filter' OR username LIKE '$filter' OR userlevel LIKE '$filter'
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_NUM)[0];
    }

    /**
     * Get users filtered by username, email, userlevel and ordered and with limit and offset.
     *
     * @param $filter
     * @param $orderBy
     * @param $order
     * @param $limit
     * @param $offset
     * @return mixed
     */
    public function getUsers($filter, $orderBy, $order, $limit, $offset)
    {
        $sql = "
            SELECT * FROM oophp_users
            WHERE email LIKE '$filter' OR username LIKE '$filter' OR userlevel LIKE '$filter'
            ORDER BY $orderBy $order
            LIMIT $limit OFFSET $offset
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * Check if a user is admin (userlevel == ADMIN_LEVEL)
     *
     * @param $user
     * @return bool
     */
    public function isAdmin($user)
    {
        return _404_APP_ADMIN_LEVEL == $this->getLevel($user);
    }
}
