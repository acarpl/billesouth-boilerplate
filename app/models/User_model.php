<?php

class User_model {
    private $table = 'users';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Cari user berdasarkan email untuk login
    public function getUserByEmail($email) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE email = :email");
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    // Simpan user baru (Register)
    public function registerUser($data) {
        $query = "INSERT INTO users (name, email, password, phone, role) 
                  VALUES (:name, :email, :password, :phone, 'member')";
        
        $this->db->query($query);
        $this->db->bind('name', $data['name']);
        $this->db->bind('email', $data['email']);
        // Hash password sebelum simpan
        $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind('phone', $data['phone']);

        $this->db->execute();
        return $this->db->rowCount();
    }
}