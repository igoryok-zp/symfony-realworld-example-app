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
}
