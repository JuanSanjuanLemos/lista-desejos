<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="CategoriaRepository::class")
*/
class Categoria
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @Groups("list_categoria")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   * @Groups("list_categoria")
   */
  private $nome;

  /**
   * @ORM\OneToMany(targetEntity=Produto::class, mappedBy="categoria")
   */
  private $produtos;

  public function __construct()
  {
      $this->produtos = new ArrayCollection();
  }

/**
   * Get the value of id
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set the value of id
   */
  public function setId($id): self
  {
    $this->id = $id;

    return $this;
  }

  /**
   * Get the value of nome
   */
  public function getNome()
  {
    return $this->nome;
  }

  /**
   * Set the value of nome
   */
  public function setNome($nome): self
  {
    $this->nome = $nome;

    return $this;
  }

  /**
   * @return Collection<int, Produto>
   */
  public function getProdutos(): Collection
  {
      return $this->produtos;
  }

  public function addProduto(Produto $produto): self
  {
      if (!$this->produtos->contains($produto)) {
          $this->produtos[] = $produto;
          $produto->setCategoria($this);
      }

      return $this;
  }

  public function removeProduto(Produto $produto): self
  {
      if ($this->produtos->removeElement($produto)) {
          // set the owning side to null (unless already changed)
          if ($produto->getCategoria() === $this) {
              $produto->setCategoria(null);
          }
      }

      return $this;
  }

  
}