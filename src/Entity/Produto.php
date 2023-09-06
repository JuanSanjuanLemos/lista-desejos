<?php

namespace App\Entity;

use App\Repository\ProdutoRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Annotation\Groups;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProdutoRepository::class)
 */
class Produto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("list_produto")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("list_produto")
     */
    private $nome;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("list_produto")
     */
    private $link;
    
    /**
     * @ORM\Column(type="float")
     * @Groups("list_produto")
     */
    private $valor;
    
    /**
     * @ORM\Column(type="boolean")
     * @Groups("list_produto")
     */
    private $isAdquirido;
    
    /**
     * @ORM\ManyToOne(targetEntity=Categoria::class, inversedBy="produtos")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("list_produto")
     */
    private $categoria;
    
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="produtos")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("list_produto")
     */
    private $usuario;

    public function __construct()
    {
        $this->setIsAdquirido(false);

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getValor(): ?float
    {
        return $this->valor;
    }

    public function setValor(float $valor): self
    {
        $this->valor = $valor;

        return $this;
    }

    public function isIsAdquirido(): ?bool
    {
        return $this->isAdquirido;
    }

    public function setIsAdquirido(bool $isAdquirido): self
    {
        $this->isAdquirido = $isAdquirido;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): self
    {
        $this->categoria = $categoria;
        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(User $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
    }
}
