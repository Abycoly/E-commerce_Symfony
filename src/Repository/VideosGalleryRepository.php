<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\VideosGallery;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method VideosGallery|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideosGallery|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideosGallery[]    findAll()
 * @method VideosGallery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideosGalleryRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
	{
		parent::__construct($registry, VideosGallery::class);
		$this->paginator = $paginator;
	}

	// /**
	//  * @return VideosGallery[] Returns an array of VideosGallery objects
	//  */
	/*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

	/*
    public function findOneBySomeField($value): ?VideosGallery
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

	/**
	 * Récupere les vides en lien avec une recherche
	 * @return PaginationInterface
	 */

	public function findSearch($search): PaginationInterface
	{
		$query = $this

			->createQueryBuilder('p')
			->where('p.title LIKE :q')
			->setParameter('q', "%{$search->q}%");

		$query = $query->getQuery();
		return $this->paginator->paginate($query, 1);
	}
}