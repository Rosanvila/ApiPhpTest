<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use Exception;
use PDO;
use App\Database\DBconnect;


abstract class AbstractRepository
{
    protected const TABLE = '';

    protected PDO $db;
    protected string $table;

    public function __construct()
    {
        $this->table = static::TABLE;
        $this->db = DBconnect::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->fetchResult($result);
    }

    public function findOneById(int $id): ?array {
        $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $query->execute(['id' => $id]);

        return $this->fetchResult($query->fetchAll(PDO::FETCH_ASSOC));
    }

    public function findBy(string $field, string $value): array
    {
        $query = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$field} = :{$field}");
        $query->execute([$field => $value]);

        return $this->fetchResult($query->fetchAll(PDO::FETCH_ASSOC));
    }

    
    abstract public function fetchResult(array $result): array;
    abstract public function add(AbstractEntity $data): bool;
}
