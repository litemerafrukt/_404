<?php

namespace _404\Database;

use PDO;

class Products
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
     * Fetch all products
     *
     * @return \stdClass
     */
    public function all()
    {
        $statement = $this->db->prepare("SELECT * FROM oophp_VAdminInventory");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }

    /**
    * Fetch a product by id.
    *
    * @param int $id
    * @return \stdClass
    */
    public function fetch($id)
    {
        $sql = "SELECT * FROM oophp_VAdminInventory WHERE id LIKE '$id'";

        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Find and return first row where $column is LIKE $value.
     *
     * Observe that a user supplied $column might open up to sql-injection.
     *
     * @param  string $column
     * @param  mixed $value
     * @return \stdClass
     */
    public function find($column, $value)
    {
        $sql = "SELECT * FROM oophp_VAdminInventory WHERE $column LIKE ?";

        $statement = $this->db->prepare($sql);
        $statement->execute([$value]);

        return $statement->fetch(PDO::FETCH_OBJ);
    }

    /**
     * Check if $column with $value exists in database.
     *
     * @param  string $column
     * @param  mixed $value
     * @return bool
     */
    public function exists($column, $value)
    {
        return $this->find($column, $value)
            ? true
            : false;
    }

    /**
     * Update product in db.
     *
     * Touches three tables.
     *
     * @param  int $id
     * @param  string $productDescription
     * @param  string $imagePath
     * @param  int $price
     * @param  string $categoryDescription
     * @param  int $inventory
     * @return void
     */
    public function update($id, $productDescription, $imagePath, $price, $categoryDescription, $inventory)
    {
        // Get category row from description
        $categoryId = $this->getOrCreateProductCategory($categoryDescription);

        // Update product
        $sql = "UPDATE oophp_products
            SET
                `description`=?,
                `image_path`=?,
                `category`=?,
                `price`=?
            WHERE
                id='$id'
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute([$productDescription, $imagePath, $categoryId, $price]);

        // Update inventory
        $statement = $this->db->prepare("UPDATE oophp_inventory SET `items`='$inventory' WHERE `prod_id`=$id");
        $statement->execute();
    }

    /**
     * Delete product
     *
     * @param string $productId
     */
    public function delete($productId)
    {
        $statement = $this->db->prepare("DELETE FROM oophp_inventory WHERE prod_id='$productId'");
        $statement->execute();
        $statement = $this->db->prepare("DELETE FROM oophp_products WHERE id='$productId'");
        $statement->execute();
    }

    /**
     * Create a new product with description and return last insert id.
     *
     * @param  string $description
     * @param  string $categoryDescription
     * @return int
     */
    public function newProduct($description, $categoryDescription)
    {
        // Get or create category
        $categoryId = $this->getOrCreateProductCategory($categoryDescription);

        // Create product
        $sql = "INSERT INTO oophp_products (description, category) VALUES ('$description', '$categoryId')";

        $statement = $this->db->prepare($sql);
        $statement->execute();

        $productId = $this->db->lastInsertId();

        // Create inventory
        $sql = "INSERT INTO oophp_inventory (items, prod_id) VALUES (0, '$productId')";

        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $productId;
    }

    /**
     * Returns product category from description, create product category if not found.
     *
     * @param  string $categoryDescription
     * @return int
     */
    public function getOrCreateProductCategory($categoryDescription)
    {
        // Get category row from description
        // $category = $this->find("category_description", $categoryDescription);
        $statement = $this->db->prepare("SELECT * FROM oophp_prodCategories WHERE description = '$categoryDescription'");
        $statement->execute();
        $category = $statement->fetch(PDO::FETCH_OBJ);

        // Check if category exists else create
        if ($category) {
            return $category->id;
        } else {
            $statement = $this->db->prepare("INSERT INTO oophp_prodCategories (`description`) VALUES ('$categoryDescription')");
            $statement->execute();
            return $this->db->lastInsertId();
        }
    }
}
