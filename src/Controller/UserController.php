<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use Psr\Http\Message\ServerRequestInterface;
use PDOException;

class UserController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function create(ServerRequestInterface $request): array
    {
        try {
            $data = json_decode($request->getBody()->getContents(), true);

            // Validation des données
            if (empty($data['email']) || empty($data['password']) || empty($data['first_name']) || empty($data['last_name'])) {
                throw new \InvalidArgumentException('Missing required fields');
            }

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