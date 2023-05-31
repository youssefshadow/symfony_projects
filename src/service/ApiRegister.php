<?php
namespace App\Service;

use App\Repository\UserRepository;
use App\Service\Utils;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ApiRegister
{
    public function authentification(UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository, string $email, string $password): bool
    {
        // Nettoyer les informations avec le cleanInput
        $email = Utils::cleanInputStatic($email);
        $password = Utils::cleanInputStatic($password);

        // Récupérer le user avec le mail 
        $user = $userRepository->findOneBy(['email' => $email]);

        // Tester si le compte existe
        if (!$user) {
            return false;
        }

        // Tester si le mot de passe est valide
        if (!$passwordHasher->isPasswordValid($user, $password)) {
            return false;
        }
        // je return true
        return true;
    }


    public function genToken(string $email, string $secretKey,$repo): string
    {
        
       //autolaod composer
        require_once('../vendor/autoload.php');
        //Variables pour le token
        $issuedAt   = new \DateTimeImmutable();
        $expire     = $issuedAt->modify('+1 minutes')->getTimestamp();
        $serverName = "your.domain.name";
        $username   = $repo->findOneBy(['email'=>$email])->getNom();
        //Contenu du token
        $data = [
            'iat'  => $issuedAt->getTimestamp(),         // Timestamp génération du token
            'iss'  => $serverName,                       // Serveur
            'nbf'  => $issuedAt->getTimestamp(),         // Timestamp empécher date antérieure
            'exp'  => $expire,                           // Timestamp expiration du token
            'userName' => $username,                     // Nom utilisateur
        ];

        $token = JWT::encode($data, $secretKey,'HS512');
        

        // Retourner le token
        return $token;
    }

    //fonction pour véfifier si le token JWT est valide
    public function verifyToken($jwt, $secretKey){
        require_once('../vendor/autoload.php');
        try {
        //Décodage du token
        $token = JWT::decode($jwt, new Key($secretKey, 'HS512'));
        return true;
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    


}
?>


