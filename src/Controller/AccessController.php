<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AccessController extends BaseController
{

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function __invoke(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        ): JsonResponse
    {
        $payload = json_decode($request->getContent(), false);
        $email = $payload->email;
        $plainPassword = $payload->password;


        $userRegistered = $this->getManager()->getRepository(User::Class)->findOneBy(['email' => $email]);

        if($userRegistered != null){
            return $this->json([
                'violations'  => [
                    [
                        'property' =>  "email",
                        "title" => "The email ".$email." already exist."
                    ]
                ],
            ], Response::HTTP_BAD_REQUEST);
        } 
        
        $user = new User();
        $user->setEmail($email);
        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_USER']);

        $errors = $this->getValidator()->validate($user);
        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
        $this->getManager()->persist($user);
        $this->getManager()->flush();

        return $this->json($user, 201);
    }
}
