<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CategoriaRepository::class")
*/
class Categoria
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
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