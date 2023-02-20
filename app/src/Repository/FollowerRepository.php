<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Follower;
use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Follower>
 *
 * @method Follower|null find($id, $lockMode = null, $lockVersion = null)
 * @method Follower|null findOneBy(array $criteria, array $orderBy = null)
 * @method Follower[]    findAll()
 * @method Follower[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Follower::class);
    }

    public function exists(Profile $profile, Profile $follower): bool
    {
        return null !== $this->find([
            'profile' => $profile,
            'follower' => $follower,
        ]);
    }

    public function add(Profile $profile, Profile $follower): void
    {
        if (!$this->exists($profile, $follower)) {
            $entity = new Follower();
            $entity->setProfile($profile);
            $entity->setFollower($follower);
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Profile $profile, Profile $follower): void
    {
        $entity = $this->find([
            'profile' => $profile,
            'follower' => $follower,
        ]);
        if ($entity !== null) {
            $this->getEntityManager()->remove($entity);
            $this->getEntityManager()->flush();
        }
    }
}
