<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\TreatRequest;
use App\Utils\VerifyParams;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    /**
     * @Route("/usuarios", name="list_users", methods={"GET"})
     */
    public function list(UserRepository $userRepository): JsonResponse
    {
        try {
            $data = $userRepository->findAll();
            return $this->json($data,200,[],["groups" => ["list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }

    /**
     * @Route("/usuarios/{id}", name="find_users", methods={"GET"})
     */
    public function find($id, UserRepository $userRepository, Security $security): JsonResponse
    {
        try {
            $user = $security->getUser();
            $userRepository->verifyIdUser($id, $user);
            
            $data = $userRepository->find($id);
            if (!$data) {
                throw new Exception("Usuário não encontrado!", 404);
            }
            return $this->json($data,200,[],["groups" => ["list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }

    /**
     * @Route("/cadastrar-usuario", name="create_users", methods={"POST"})
     */
    public function create(Request $request, UserRepository $userRepository,UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        try {
            $data = TreatRequest::getDataRequest($request);

            if($userRepository->findOneBy(['email' => $data['email']])) {
                throw new Exception("Email já cadastrado!",400);
            }
            VerifyParams::verifyIsSet(['email','password'], $data);
            $user = new User();
            $user->setEmail($data['email']);
            $user->setPassword($data['password'], $passwordHasher);
            if(isset($data['roles'])){
                $user->setRoles($data['roles']);
            }
            $userRepository->add($user,true);

            return $this->json($user,201,[],["groups" => ["list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }

    /**
     * @Route("/usuarios/{id}", name="update_users", methods={"PUT","PATCH"})
     */
    public function update($id, Security $security, Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        try {
            $data = TreatRequest::getDataRequest($request);
            $user = $security->getUser();
            $userRepository->verifyIdUser($id, $user);

            $user = $userRepository->find($id);
            
            if (!$user) {
                throw new Exception("Usuário não encontrado!", 404);
            }
            
            if(isset($data['email'])) {
                if($userRepository->findOneBy(['email' => $data['email']])) {
                    throw new Exception("Email já cadastrado!",400);
                }
                $user->setEmail($data['email']);
            }
            if(isset($data['password'])) {
                $user->setPassword($data['password'], $passwordHasher);
            }
            if(isset($data['roles'])){
                $user->setRoles($data['roles']);
            }
            $userRepository->add($user,true);

            return $this->json($user,200,[],["groups" => ["list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }
    
    /**
     * @Route("/usuarios/{id}", name="delete_users", methods={"DELETE"})
     */
    public function delete($id, Security $security, UserRepository $userRepository): JsonResponse
    {
        try {
            $user = $security->getUser();
            $userRepository->verifyIdUser($id, $user);
            $data = $userRepository->find($id);
            if (!$data) {
                throw new Exception("Usuário não encontrado!", 404);
            }
            $userRepository->remove($data,true);
            return $this->json("Usuário deletado com sucesso!",204);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }
}