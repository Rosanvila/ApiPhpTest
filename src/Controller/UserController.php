<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use PDOException;

class UserController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function create(array $data): array
    {
        try {
            // Validation des données
            if (empty($data['email']) || empty($data['password']) || empty($data['first_name']) || empty($data['last_name'])) {
                throw new \InvalidArgumentException('Missing required fields');
            }

            // Création de l'utilisateur
            $user = new User(
                null,
                $data['email'],
                password_hash($data['password'], PASSWORD_DEFAULT),
                $data['first_name'],
                $data['last_name']
            );

            // Ajout de l'utilisateur à la base de données
            $this->userRepository->add($user);
            return ['message' => 'User created successfully'];
        } catch (\InvalidArgumentException $e) {
            return ['error' => $e->getMessage()];
        } catch (PDOException $e) {
            return ['error' => $e->getMessage()];
        } catch (\Exception $e) {
            return ['error' => 'An unexpected error occurred'];
        }
    }
}