<?php

namespace App\Controller;

use App\Repository\CategoriaRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategoriaController extends AbstractController
{
    /**
     * @Route("/categorias", name="app_categoria")
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
}
