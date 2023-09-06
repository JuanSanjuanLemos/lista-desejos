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
    public function list(ProdutoRepository $produtoRepository, Security $security): JsonResponse
    {
        try {
            $user = $security->getUser();
            $data = $produtoRepository->findBy(['usuario' => $user]);
            return $this->json($data, 200, [], ["groups" => ["list_produto", "list_categoria", "list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' => $e->getMessage()));
        }
    }

    /**
     * @Route("/produtos/{id}", name="find_produto", methods={"GET"})
     */
    public function find($id, ProdutoRepository $produtoRepository, Security $security): JsonResponse
    {
        try {
            $user = $security->getUser();
            $data = $produtoRepository->findOneBy(['id' => $id, "usuario" => $user]);
            if (!$data) {
                throw new Exception("Produto não encontrado!", 404);
            }
            return $this->json($data, 200, [], ["groups" => ["list_produto", "list_categoria", "list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' => $e->getMessage()));
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
            if ($user) {
                $this->verifyIsSetAndIsNull(['nome','link','valor','categoria'],$data);
                $produto->setUsuario($user);
                $produto->setNome($data['nome']);
                $produto->setLink($data['link']);
                $produto->setValor($data['valor']);
                $categoria = $categoriaRepository->find($data['categoria']);
                $produto->setCategoria($categoria);
                $produtoRepository->add($produto, true);
                return $this->json($produto, 201, [], ["groups" => ["list_produto", "list_categoria", "list_user"]]);
            }
            throw new Exception("Nenhum usuário logado");
        } catch (Exception $e) {
            return $this->json(array('error' => $e->getMessage()));
        }
    }

    /**
     * @Route("/produtos/{id}", name="update_produtos", methods={"PUT","PATCH"})
     */
    public function update($id, Request $request, CategoriaRepository $categoriaRepository, ProdutoRepository $produtoRepository, Security $security): JsonResponse
    {
        try {
            $data = $request->request->all();
            $user = $security->getUser();
            $produto = $produtoRepository->findOneBy(['id' => $id, "usuario" => $user]);
            if (!$produto) {
                throw new Exception("Produto não encontrada!", 404);
            }
            if(isset($data['nome'])){
                $produto->setNome($data['nome']);
            }
            if(isset($data['link'])){
                $produto->setLink($data['link']);
            }
            if(isset($data['valor'])){
                $produto->setValor($data['valor']);
            }
            if(isset($data['categoria'])){
                $categoria = $categoriaRepository->find($data['categoria']);
                $produto->setCategoria($categoria);
            }
            if(isset($data['isAdquirido'])){
                $produto->setIsAdquirido($data['isAdquirido']);
            }
            $produtoRepository->add($produto, true);
            return $this->json($produto, 200, [], ["groups" => ["list_produto", "list_categoria", "list_user"]]);
        } catch (Exception $e) {
            return $this->json(array('error' => $e->getMessage()));
        }
    }

    /**
     * @Route("/produtos/{id}", name="delete_produto", methods={"DELETE"})
     */
    public function delete($id, ProdutoRepository $produtoRepository, Security $security): JsonResponse
    {
        try {
            $user = $security->getUser();
            $data = $produtoRepository->findOneBy(['id' => $id, "usuario" => $user]);
            if (!$data) {
                throw new Exception("Categoria não encontrada!", 404);
            }
            $produtoRepository->remove($data, true);
            return $this->json("Categoria removida com sucesso!", 204);
        } catch (Exception $e) {
            return $this->json(array('error' => $e->getMessage()));
        }
    }

    public function verifyIsSetAndIsNull($array = array(), $data = array())
    {
        foreach ($array as $value) {
            if (!array_key_exists($value, $data)) {
                throw new \InvalidArgumentException("Valores inválidos!");
            }
        }
    }
}