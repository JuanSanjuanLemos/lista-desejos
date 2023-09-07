<?php

namespace App\Controller;

use App\Entity\Produto;
use App\Repository\CategoriaRepository;
use App\Repository\ProdutoRepository;
use App\Utils\TreatRequest;
use App\Utils\VerifyParams;
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
    public function list(Request $request, ProdutoRepository $produtoRepository, Security $security): JsonResponse
    {
        try {
            $user = $security->getUser();
            $isAdquirido = null;
            if($request->query->get("isAdquirido")){
                $isAdquirido = $request->query->get("isAdquirido");
            }
            $data = $produtoRepository->listAllWhere($user,$isAdquirido);
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
            $data = TreatRequest::getDataRequest($request);
            $produto = new Produto();
            VerifyParams::verifyIsSet(['nome','link','valor','categoria'],$data);
            $user = $security->getUser();
            $produto->setUsuario($user);
            $produto->setNome($data['nome']);
            $produto->setLink($data['link']);
            $produto->setValor($data['valor']);
            $categoria = $categoriaRepository->findOneBy(["id"=>$data['categoria'], "usuario"=>$user]);
            if (!$categoria){
                throw new Exception("Categoria não encontrada!", 404);
            }
            $produto->setCategoria($categoria);
            $produtoRepository->add($produto, true);
            return $this->json($produto, 201, [], ["groups" => ["list_produto", "list_categoria", "list_user"]]);
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
            $data = TreatRequest::getDataRequest($request);
            $user = $security->getUser();
            $produto = $produtoRepository->findOneBy(['id' => $id, "usuario" => $user]);
            $produto->setUpdatedAt(new \DateTime());
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
                throw new Exception("Produto não encontrado!", 404);
            }
            $produtoRepository->remove($data, true);
            return $this->json("Produto removido com sucesso!", 204);
        } catch (Exception $e) {
            return $this->json(array('error' => $e->getMessage()));
        }
    }

    
}