<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Favorite;
use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favorite>
 *
 * @method int           countByArticle(Article $article)
 * @method Favorite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favorite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favorite[]    findAll()
 * @method Favorite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favorite::class);
    }

    public function exists(Article $article, Profile $profile): bool
    {
        return null !== $this->find([
            'article' => $article,
            'profile' => $profile,
        ]);
    }

    public function add(Article $article, Profile $profile): void
    {
        if (!$this->exists($article, $profile)) {
            $entity = new Favorite();
            $entity->setArticle($article);
            $entity->setProfile($profile);
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $article, Profile $profile): void
    {
        $entity = $this->find([
            'article' => $article,
            'profile' => $profile,
        ]);
        if ($entity !== null) {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Profile $profile
     * @param Article[] $articles
     * @return Favorite[]
     */
    public function findByProfile(Profile $profile, array $articles = []): array
    {
        $criteria = ['profile' => $profile];
        if (!empty($articles)) {
            $criteria['article'] = $articles;
        }
        return $this->findBy($criteria);
    }

    /**
     * @param Article[] $articles
     * @return array<int, int>
     */
    public function countByArticles(array $articles): array
    {
        $result = [];
        $articleIds = array_map(fn(Article $article) => $article->getId(), $articles);
        if (!empty($articleIds)) {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->from(Favorite::class, 'f');
            $queryBuilder->where('f.article IN (:article_ids)');
            $queryBuilder->setParameter('article_ids', $articleIds);
            $queryBuilder->groupBy('f.article');
            $queryBuilder->select(
                'IDENTITY(f.article) AS id',
                $queryBuilder->expr()->count('IDENTITY(f.profile)') . ' AS qty',
            );
            $counts = $queryBuilder->getQuery()->getScalarResult();
            $result = array_combine(
                array_column($counts, 'id'),
                array_column($counts, 'qty'),
            );
        }
        return $result;
    }
}
