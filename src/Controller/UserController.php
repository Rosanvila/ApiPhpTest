<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use Psr\Http\Message\ServerRequestInterface;
use PDOException;
use App\Helpers\ResponseHelper;
use GuzzleHttp\Psr7\Response;

class UserController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function create(ServerRequestInterface $request): Response
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
            return ResponseHelper::jsonResponse(['message' => 'User created successfully'], 201);
        } catch (\InvalidArgumentException $e) {
            return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 400);
        } catch (PDOException $e) {
            return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(['error' => 'An unexpected error occurred'], 500);
        }
    }

    public function index(): Response
    {
        try {
            $users = $this->userRepository->findAll();
            return ResponseHelper::jsonResponse($users, 200);
        } catch (PDOException $e) {
            return ResponseHelper::jsonResponse(['error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(['error' => 'An unexpected error occurred'], 500);
        }
    }
}