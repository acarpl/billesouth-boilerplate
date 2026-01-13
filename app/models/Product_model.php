<?php
class Product_model {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllProducts() {
        // Get products with stock information for default branch (1) if no branch is specified
        $this->db->query("
            SELECT
                p.*,
                COALESCE(ps.stock, 0) as stock,
                p.is_active as status
            FROM products p
            LEFT JOIN product_stocks ps ON p.id = ps.product_id AND ps.branch_id = 1
            WHERE p.is_active = 1
        ");
        return $this->db->resultSet();
    }

    public function getAll() {
        $this->db->query("
            SELECT
                p.*,
                COALESCE(ps.stock, 0) as stock,
                p.is_active as status
            FROM products p
            LEFT JOIN product_stocks ps ON p.id = ps.product_id AND ps.branch_id = 1
            WHERE p.is_active = 1
            ORDER BY p.id DESC
        ");
        return $this->db->resultSet();
    }

    public function getProductById($id) {
        $this->db->query("
            SELECT
                p.*,
                COALESCE(ps.stock, 0) as stock,
                p.is_active as status
            FROM products p
            LEFT JOIN product_stocks ps ON p.id = ps.product_id AND ps.branch_id = 1
            WHERE p.id = :id
        ");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getById($id) {
        $this->db->query("
            SELECT
                p.*,
                COALESCE(ps.stock, 0) as stock,
                p.is_active as status
            FROM products p
            LEFT JOIN product_stocks ps ON p.id = ps.product_id AND ps.branch_id = 1
            WHERE p.id = :id
        ");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getLatestProduct() {
        $this->db->query("
            SELECT
                p.*,
                COALESCE(ps.stock, 0) as stock,
                p.is_active as status
            FROM products p
            LEFT JOIN product_stocks ps ON p.id = ps.product_id AND ps.branch_id = 1
            ORDER BY p.id DESC
            LIMIT 1
        ");
        return $this->db->single();
    }

    // Method specifically for inventory management that joins with related tables
    public function getAllForInventory() {
        // Get products with stock information from product_stocks table
        $this->db->query("
            SELECT
                p.*,
                COALESCE(ps.stock, 0) as stock,
                p.is_active as status
            FROM products p
            LEFT JOIN product_stocks ps ON p.id = ps.product_id
            WHERE p.is_active = 1
            ORDER BY p.id DESC
        ");
        return $this->db->resultSet();
    }

    // Method to get all products with stock for a specific branch
    public function getAllForBranch($branch_id) {
        $this->db->query("
            SELECT
                p.*,
                COALESCE(ps.stock, 0) as stock,
                p.is_active as status
            FROM products p
            LEFT JOIN product_stocks ps ON p.id = ps.product_id AND ps.branch_id = :branch_id
            WHERE p.is_active = 1
            ORDER BY p.id DESC
        ");
        $this->db->bind('branch_id', $branch_id);
        return $this->db->resultSet();
    }

    // Method to get stock for a specific product and branch
    public function getStock($product_id, $branch_id) {
        $this->db->query("
            SELECT COALESCE(ps.stock, 0) as stock
            FROM product_stocks ps
            WHERE ps.product_id = :product_id AND ps.branch_id = :branch_id
        ");
        $this->db->bind('product_id', $product_id);
        $this->db->bind('branch_id', $branch_id);
        $result = $this->db->single();
        return $result ? $result->stock : 0;
    }

    // Method to update stock for a product at a specific branch
    public function updateStock($product_id, $branch_id, $stock) {
        // Check if a record already exists
        $this->db->query("
            SELECT id FROM product_stocks
            WHERE product_id = :product_id AND branch_id = :branch_id
        ");
        $this->db->bind('product_id', $product_id);
        $this->db->bind('branch_id', $branch_id);
        $existing = $this->db->single();

        if ($existing) {
            // Update existing record
            $this->db->query("
                UPDATE product_stocks
                SET stock = :stock
                WHERE product_id = :product_id AND branch_id = :branch_id
            ");
        } else {
            // Insert new record
            $this->db->query("
                INSERT INTO product_stocks (product_id, branch_id, stock)
                VALUES (:product_id, :branch_id, :stock)
            ");
        }

        $this->db->bind('product_id', $product_id);
        $this->db->bind('branch_id', $branch_id);
        $this->db->bind('stock', $stock);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function create($data, $branch_id = null) {
        // First, insert the product
        $this->db->query("INSERT INTO products (category_id, name, description, price, image, is_active) VALUES (:category_id, :name, :description, :price, :image, :is_active)");
        $this->db->bind('category_id', $data['category_id']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('price', $data['price']);
        $this->db->bind('image', $data['image']);
        $this->db->bind('is_active', $data['is_active']);

        $this->db->execute();

        if($this->db->rowCount() > 0) {
            // Get the inserted product ID
            $product_id = $this->db->lastInsertId();

            // Create initial stock records
            if ($branch_id) {
                // Only create stock for the specified branch
                $this->updateStock($product_id, $branch_id, 0);
            } else {
                // Create initial stock records for all branches (default to 0)
                $branchModel = new Branch_model();
                $branches = $branchModel->getAll();

                foreach($branches as $branch) {
                    $this->updateStock($product_id, $branch->id, 0);
                }
            }

            return true;
        }

        return false;
    }

    public function update($id, $data) {
        $this->db->query("UPDATE products SET category_id = :category_id, name = :name, description = :description, price = :price, image = :image, is_active = :is_active WHERE id = :id");
        $this->db->bind('category_id', $data['category_id']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('description', $data['description']);
        $this->db->bind('price', $data['price']);
        $this->db->bind('image', $data['image']);
        $this->db->bind('is_active', $data['is_active']);
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    public function getTotalProducts() {
        $this->db->query("SELECT COUNT(*) as total FROM products WHERE is_active = 1");
        $result = $this->db->single();
        return $result->total;
    }

    public function delete($id) {
        $this->db->query("UPDATE products SET is_active = 0 WHERE id = :id");
        $this->db->bind('id', $id);

        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
}