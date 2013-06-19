<?php

namespace StoneAppleDev\PublicSchema;

use StoneAppleDev\PublicSchema\Base\PostMap as BasePostMap;
use StoneAppleDev\PublicSchema\Post;
use \Pomm\Exception\Exception;
use \Pomm\Query\Where;

class PostMap extends BasePostMap
{
    public function truncate()
    {
        $sql = sprintf("TRUNCATE TABLE %s CASCADE",
            $this->getTableName()
        );

        return $this->query($sql);
    }


    public function getOneWithTags($slug)
    {
        $tagMap = $this->connection->getMapFor('StoneAppleDev\PublicSchema\Tag');
        $relationMap = $this->connection->getMapFor('StoneAppleDev\PublicSchema\PostTag');

        $sql = "SELECT
          %s,
          array_agg(tag) AS tags
        FROM
          %s 
            LEFT JOIN %s ON post.id = post_tag.post_id
            LEFT JOIN %s ON post_tag.tag_id = tag.id
        WHERE
            post.slug = ?
        GROUP BY
          %s";

        $sql = sprintf($sql,
            join(', ', $this->getSelectFields('post')),
            $this->getTableName('post'),
            $relationMap->getTableName('post_tag'),
            $tagMap->getTableName('tag'),
            join(', ', $this->getGroupByFields('post'))
        );

        $this->addVirtualField('tags', $tagMap->getTableName().'[]');

        return $this->query($sql, array($slug));
    }
}
