<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\PersistentCollection;
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

    /**
     * @param string[] $tagNames
     * @return Tag[]
     */
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

    /**
     * @param Article[] $articles
     * @return void
     */
    public function loadTags(array $articles): void
    {
        $articleIds = array_map(
            fn (Article $article) => $article->getId(),
            array_filter($articles, function (Article $article) {
                /** @var PersistentCollection<int, Tag> */
                $tags = $article->getTags();
                return !$tags->isInitialized();
            })
        );
        if (!empty($articleIds)) {
            $queryBuilder = $this->getEntityManager()->createQueryBuilder();
            $queryBuilder->from(Article::class, 'a');
            $queryBuilder->join('a.tags', 'at');
            $queryBuilder->where('a.id IN (:article_ids)');
            $queryBuilder->setParameter('article_ids', $articleIds);
            $queryBuilder->select('a', 'at');
            $queryBuilder->getQuery()->getResult();
        }
    }
}
