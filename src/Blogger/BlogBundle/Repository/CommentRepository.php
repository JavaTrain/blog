<?php

namespace Blogger\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{

    /**
     * @param      $blogId
     * @param bool $approved
     *
     * @return array
     */
    public function getCommentsForBlog($blogId, $approved = true)
    {
        $qb = $this->createQueryBuilder('c')
                   ->select('c')
                   ->where('c.blog = :blog_id')
                   ->addOrderBy('c.created')
                   ->setParameter('blog_id', $blogId);

        if (!is_null($approved))
            $qb->andWhere('c.approved = :approved')
               ->setParameter('approved', $approved);

        return $qb->getQuery()
                  ->getResult();
    }

    /**
     * @param string $alias
     * @param null   $indexBy
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
//    public function createQueryBuilder($alias, $indexBy = null)
//    {
//        return $this->_em->createQueryBuilder()
//                         ->select($alias)
//                         ->from($this->_entityName, $alias, $indexBy);
//    }
}