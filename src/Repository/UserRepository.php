<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\User;

final class UserRepository extends AbstractRepository
{
    protected const TABLE = 'users';

    public function fetchResult(array $result): array
    {
        $users = [];
        foreach ($result as $data) {
            $users[] = new User(
                $data['id'],
                $data['email'],
                $data['password'],
                $data['first_name'],
                $data['last_name']
            );
        }
        return $users;
    }

    public function add(AbstractEntity $data): bool
    {
        if (!$data instanceof User) {
            throw new \InvalidArgumentException('Invalid entity type');
        }

        $query = $this->db->prepare("INSERT INTO {$this->table} (email, password, first_name, last_name) VALUES (:email, :password, :first_name, :last_name)");
        $query->execute([
            'email' => $data->getEmail(),
            'password' => $data->getPassword(),
            'first_name' => $data->getFirstName(),
            'last_name' => $data->getLastName()
        ]);

        return true;
    }

    public function getAll(): array
    {
        $query = $this->db->query("SELECT * FROM {$this->table}");
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $this->fetchResult($result);
    }

    
}