<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 *
 * @method Tag|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tag|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tag[]    findAll()
 * @method Tag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function findOrCreate(array $tagNames): array
    {
        $tags = $this->findBy(['name' => $tagNames]);
        $createTags = array_diff(
            array_unique($tagNames),
            array_map(fn (Tag $tag) => $tag->getName(), $tags)
        );
        if (!empty($createTags)) {
            foreach ($createTags as $tagName) {
                $tag = new Tag();
                $tag->setName($tagName);
                $this->getEntityManager()->persist($tag);
                $tags[] = $tag;
            }
            $this->getEntityManager()->flush();
        }
        return $tags;
    }
}
