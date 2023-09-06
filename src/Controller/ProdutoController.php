<?php

namespace App\Controller;

use App\Entity\Produto;
use App\Repository\CategoriaRepository;
use App\Repository\ProdutoRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ProdutoController extends AbstractController
{
    /**
     * @Route("/produtos", name="list_produtos", methods={"GET"})
     */
    public function list(ProdutoRepository $produtoRepository): JsonResponse
    {
        try {
            $data = $produtoRepository->findAll();
            return $this->json($data,200,[],["groups"=>["list_produto","list_categoria","list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }

    /**
     * @Route("/produtos/{id}", name="find_produto", methods={"GET"})
     */
    public function find($id,ProdutoRepository $produtoRepository): JsonResponse
    {
        try {
            $data = $produtoRepository->find($id);
            if (!$data) {
                throw new Exception("Produto não encontrado!", 404);
            }
            return $this->json($data,200,[],["groups"=>["list_produto","list_categoria","list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }

    /**
     * @Route("/produtos", name="create_produtos", methods={"POST"})
     */
    public function create(Request $request, CategoriaRepository $categoriaRepository, ProdutoRepository $produtoRepository, Security $security): JsonResponse
    {
        try {
            $data = $request->request->all();
            $produto = new Produto();
            $user = $security->getUser();
            if($user){
                $produto->setUsuario($user);
                $produto->setNome($data['nome']);
                $produto->setLink($data['link']);
                $produto->setValor($data['valor']);
                $categoria = $categoriaRepository->find($data['categoria']);
                $produto->setCategoria($categoria);
                $produtoRepository->add($produto,true);
                return $this->json($produto,201,[],["groups"=>["list_produto","list_categoria","list_user"]]);
            }
            throw new Exception("Nenhum usuário logado");
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }
    
    /**
     * @Route("/produtos/{id}", name="update_produtos", methods={"PUT"})
     */
    public function update($id, Request $request,ProdutoRepository $produtoRepository): JsonResponse
    {
        try {
            // $data = $request->request->all();
            // $categoria = $produtoRepository->find($id);
            // if (!$categoria) {
            //     throw new Exception("Categoria não encontrada!", 404);
            // }
            // $categoria->setNome($data['nome']);
            // $produtoRepository->add($categoria,true);
            return $this->json($categoria,200,[],["groups"=>["list_produto","list_categoria","list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }

    /**
     * @Route("/produtos/{id}", name="delete_produto", methods={"DELETE"})
     */
    public function delete($id,ProdutoRepository $produtoRepository): JsonResponse
    {
        try {
            $data = $produtoRepository->find($id);
            if (!$data) {
                throw new Exception("Categoria não encontrada!", 404);
            }
            $produtoRepository->remove($data,true);
            return $this->json("Categoria removida com sucesso!",204);
        } catch (Exception $e) {
            return $this->json(array('error' =>$e->getMessage()));
        }
    }
}
