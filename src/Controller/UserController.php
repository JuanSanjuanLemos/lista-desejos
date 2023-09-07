<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
    public function find($id, UserRepository $userRepository): JsonResponse
    {
        try {
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
    public function create(Request $request, UserRepository $userRepository): JsonResponse
    {
        try {
            $data = $request->request->all();

            if($userRepository->findOneBy(['email' => $data['email']])) {
                throw new Exception("Email já cadastrado!",400);
            }
            $user = new User();
            $user->setEmail($data['email']);
            $user->setPassword($data['password']);
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
    public function update($id, Request $request, UserRepository $userRepository): JsonResponse
    {
        try {
            $data = $request->request->all();
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
                $user->setPassword($data['password']);
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
    public function delete($id, UserRepository $userRepository): JsonResponse
    {
        try {
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
