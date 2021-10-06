<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\ImagesGallery;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ImagesGallery|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagesGallery|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagesGallery[]    findAll()
 * @method ImagesGallery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesGalleryRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, ImagesGallery::class);
	}

	// /**
	//  * @return ImagesGallery[] Returns an array of ImagesGallery objects
	//  */
	/*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

	/*
    public function findOneBySomeField($value): ?ImagesGallery
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
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

	public function findSearch(SearchData $search): PaginationInterface
	{
		$query = $this

			->createQueryBuilder('p')
			->select('c', 'p')
			->join('p.product', 'c');


		if (!empty($search->products)) {
			$query = $query
				->andWhere('c.id IN (:product)')
				->setParameter('product', $search->products);
		}

		$query = $query->getQuery();
		return $this->paginator->paginate($query, $search->page, 9);
	}
}
