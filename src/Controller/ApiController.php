<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Service\ApiRegister;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiController extends AbstractController
{
    #[Route('/api/verif-connexion', name: 'verif_connexion', methods: ['GET'])]
    public function verifConnexion(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ApiRegister $apiRegister,
        UserRepository $userRepository
    ): Response {
        // Récupérer le mail et le mot de passe depuis la requête GET
        $email = $request->query->get('email');
        $password = $request->query->get('password');

        
        if (!$email || !$password) {
            return $this->json(['error' => 'Informations incorrectes'], 400);
        }

        // Vérifier l'authentification
        $isAuthenticated = $apiRegister->authentification($passwordHasher, $userRepository, $email, $password);

        if ($isAuthenticated) {
            // Récupérer la clé de chiffrement
            $secretKey = $this->getParameter('token');

            
            $token = $apiRegister->genToken($email, $secretKey, $userRepository);

            return $this->json(['token' => $token], 200, ['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> '*']);
        } else {
            return $this->json(['error' => 'Invalid datas'], 400, ['Content-Type'=>'application/json',
            'Access-Control-Allow-Origin'=> '*']);
        }
    }
}
