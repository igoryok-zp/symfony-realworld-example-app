<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Follower;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article|null findOneBySlug(string $slug)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    private function createArticlesFeedQueryBuilder(int $followerId): QueryBuilder
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder
            ->from(Article::class, 'a')
            ->join(
                Follower::class,
                'f',
                Join::WITH,
                $queryBuilder->expr()->andX(
                    'a.author = f.profile',
                    $queryBuilder->expr()->eq('f.follower', ':follower_id')
                )
            )
            ->setParameter('follower_id', $followerId);
        return $queryBuilder;
    }

    public function save(Article $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Article $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    public function countArticlesFeed(int $followerId): int
    {
        $queryBuilder = $this->createArticlesFeedQueryBuilder($followerId);
        $queryBuilder->select('COUNT(a)');
        return $queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function findArticlesFeed(int $followerId, int $limit, int $offset): array
    {
        $queryBuilder = $this->createArticlesFeedQueryBuilder($followerId);
        $queryBuilder->select('a');
        $queryBuilder->orderBy('a.createdAt', 'DESC');
        $queryBuilder->setFirstResult($offset);
        $queryBuilder->setMaxResults($limit);
        return $queryBuilder->getQuery()->getResult();
    }
}
