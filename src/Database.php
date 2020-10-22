<?php

namespace App;

use PDO;

class Database
{
    public $connection;
    private $DB_SERVER, $DB_USER, $DB_PASS, $DB_NAME;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->DB_SERVER = $_ENV['DB_HOST'];
        $this->DB_NAME = $_ENV['DB_NAME'];
        $this->DB_PASS = $_ENV['DB_PASSWORD'];
        $this->DB_USER = $_ENV['DB_USERNAME'];
        $this->connection = $this->connect();
        $this->loadMigrations();
    }

    /**
     * Connects to the database using PDO
     * @return PDO
     */
    private function connect(): PDO
    {
        $conn = new PDO("mysql:host=$this->DB_SERVER;dbname=$this->DB_NAME", $this->DB_USER, $this->DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }

    /**
     * Load initial migrations for creating tables
     */
    public function loadMigrations()
    {
        $migrations = [
            "create table if not exists users
                (
                    id int auto_increment primary key,
                    username varchar(255) not null,
                    password text         not null,
                    api_key text default (md5(rand())),
                    constraint users_username_uindex unique (username)
                );",
            "CREATE TABLE IF NOT EXISTS `nodes`
                (
                    `mac_id` VARCHAR(255) PRIMARY KEY,
                    `name` VARCHAR(255),
                    `user_id` INT,
                    FOREIGN KEY (`user_id`) REFERENCES users(`id`)
                )",
            "CREATE TABLE IF NOT EXISTS `decibels` 
                (`id` INT PRIMARY KEY AUTO_INCREMENT,
                `mac_id` VARCHAR (255) NOT NULL,
                `sound` INT NOT NULL,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`mac_id`) REFERENCES nodes(`mac_id`)
                )"
        ];

        $this->connection->beginTransaction();
        foreach ($migrations as $migration) {
            $this->connection->exec($migration);
        }
        $this->connection->commit();
    }

    /**
     * Returns the created PDO connection
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

}
