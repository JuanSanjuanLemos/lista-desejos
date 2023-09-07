<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use App\Utils\TreatRequest;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CategoriaController extends AbstractController
{
    /**
     * @Route("/categorias", name="list_categorias", methods={"GET"})
     */
    public function list(CategoriaRepository $categoriaRepository, Security $security): JsonResponse
    {
        try {
            $user = $security->getUser();
            $data = $categoriaRepository->findBy(["usuario" => $user]);
            return $this->json($data,200,[],["groups" => ["list_categoria","list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }

    /**
     * @Route("/categorias/{id}", name="find_categoria", methods={"GET"})
     */
    public function find($id,CategoriaRepository $categoriaRepository, Security $security): JsonResponse
    {
        try {
            $user = $security->getUser();
            $data = $categoriaRepository->findOneBy(['id' => $id, "usuario" => $user]);
            if (!$data) {
                throw new Exception("Categoria não encontrada!", 404);
            }
            return $this->json($data,200,[],["groups" => ["list_categoria","list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }

    /**
     * @Route("/categorias", name="create_categorias", methods={"POST"})
     */
    public function create(Request $request,CategoriaRepository $categoriaRepository, Security $security): JsonResponse
    {
        try {
            $data = TreatRequest::getDataRequest($request);
            $categoria = new Categoria();
            $user = $security->getUser();
            $categoria->setUsuario($user);
            $categoria->setNome($data['nome']);
            $categoriaRepository->add($categoria,true);
            return $this->json($categoria,201, [],["groups" => ["list_categoria","list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }
    
    /**
     * @Route("/categorias/{id}", name="update_categorias", methods={"PUT"})
     */
    public function update($id, Request $request,CategoriaRepository $categoriaRepository, Security $security): JsonResponse
    {
        try {
            $data = TreatRequest::getDataRequest($request);
            $user = $security->getUser();
            $categoria = $categoriaRepository->findOneBy(['id' => $id, "usuario" => $user]);
            if (!$categoria) {
                throw new Exception("Categoria não encontrada!", 404);
            }
            if(isset($data["nome"])){
                $categoria->setNome($data['nome']);
            }
            $categoriaRepository->add($categoria,true);
            return $this->json($categoria,200,[],["groups" => ["list_categoria","list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }

    /**
     * @Route("/categorias/{id}", name="delete_categoria", methods={"DELETE"})
     */
    public function delete($id,CategoriaRepository $categoriaRepository, Security $security): JsonResponse
    {
        try {
            $user = $security->getUser();
            $data = $categoriaRepository->findOneBy(['id' => $id, "usuario" => $user]);
            if (!$data) {
                throw new Exception("Categoria não encontrada!", 404);
            }
            $categoriaRepository->remove($data,true);
            return $this->json("Categoria removida com sucesso!",204);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }
}
