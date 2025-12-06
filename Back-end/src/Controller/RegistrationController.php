<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/api/users', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        // check data
        if (!isset($data['email']) || !isset($data['username']) || !isset($data['password'])) {
            return $this->json([
                'message' => 'Email, username et password sont requis'
            ], Response::HTTP_BAD_REQUEST);
        }

        // check existing email
        $existingUser = $entityManager->getRepository(User::class)
            ->findOneBy(['email' => $data['email']]);

        if ($existingUser) {
            return $this->json([
                'message' => 'Cet email est déjà utilisé'
            ], Response::HTTP_CONFLICT);
        }

        // check existing username
        $existingUsername = $entityManager->getRepository(User::class)
            ->findOneBy(['username' => $data['username']]);

        if ($existingUsername) {
            return $this->json([
                'message' => 'Ce nom d\'utilisateur est déjà utilisé'
            ], Response::HTTP_CONFLICT);
        }

        // Create new user
        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);

        // Hash pswd
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $data['password']
        );
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        // validate entity
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json([
                'message' => 'Erreur de validation',
                'errors' => $errorMessages
            ], Response::HTTP_BAD_REQUEST);
        }

        // Save in db
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'username' => $user->getUsername()
            ]
        ], Response::HTTP_CREATED);
    }
}
