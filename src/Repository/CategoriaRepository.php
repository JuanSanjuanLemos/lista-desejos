<?php
namespace App\Repository;

use App\Entity\Categoria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoriaRepository extends ServiceEntityRepository
{
  /**
   * @extends ServiceEntityRepository<Categoria>
   *
   * @method Categoria|null find($id, $lockMode = null, $lockVersion = null)
   * @method Categoria|null findOneBy(array $criteria, array $orderBy = null)
   * @method Categoria[]    findAll()
   * @method Categoria[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
   */
  public function __construct(ManagerRegistry $registry)
  {
      parent::__construct($registry, Categoria::class);
  }
  public function add(Categoria $entity, bool $flush = false): void
  {
      $this->getEntityManager()->persist($entity);

      if ($flush) {
          $this->getEntityManager()->flush();
      }
  }

  public function remove(Categoria $entity, bool $flush = false): void
  {
      $this->getEntityManager()->remove($entity);

      if ($flush) {
          $this->getEntityManager()->flush();
      }
  }
}