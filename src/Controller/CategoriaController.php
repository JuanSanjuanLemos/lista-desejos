<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriaController extends AbstractController
{
    /**
     * @Route("/categorias", name="list_categorias", methods={"GET"})
     */
    public function list(CategoriaRepository $categoriaRepository): JsonResponse
    {
        try {
            $data = $categoriaRepository->findAll();
            return $this->json($data,200);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }

    /**
     * @Route("/categorias/{id}", name="find_categoria", methods={"GET"})
     */
    public function find($id,CategoriaRepository $categoriaRepository): JsonResponse
    {
        try {
            $data = $categoriaRepository->find($id);
            if (!$data) {
                throw new Exception("Categoria não encontrada!", 404);
            }
            return $this->json($data,200);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }

    /**
     * @Route("/categorias", name="create_categorias", methods={"POST"})
     */
    public function create(Request $request,CategoriaRepository $categoriaRepository): JsonResponse
    {
        try {
            $data = $request->request->all();
            $categoria = new Categoria();
            $categoria->setNome($data['nome']);
            $categoriaRepository->add($categoria,true);
            return $this->json($categoria,200);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }
    
    /**
     * @Route("/categorias/{id}", name="update_categorias", methods={"PUT"})
     */
    public function update($id, Request $request,CategoriaRepository $categoriaRepository): JsonResponse
    {
        try {
            $data = $request->request->all();
            $categoria = $categoriaRepository->find($id);
            if (!$categoria) {
                throw new Exception("Categoria não encontrada!", 404);
            }
            $categoria->setNome($data['nome']);
            $categoriaRepository->add($categoria,true);
            return $this->json($categoria,200);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }

    /**
     * @Route("/categorias/{id}", name="delete_categoria", methods={"DELETE"})
     */
    public function delete($id,CategoriaRepository $categoriaRepository): JsonResponse
    {
        try {
            $data = $categoriaRepository->find($id);
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
