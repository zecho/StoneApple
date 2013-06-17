<?php

namespace StoneAppleDev\PublicSchema;

use StoneAppleDev\PublicSchema\Base\TagMap as BaseTagMap;
use StoneAppleDev\PublicSchema\Tag;
use \Pomm\Exception\Exception;
use \Pomm\Query\Where;

class TagMap extends BaseTagMap
{
    public function truncate()
    {
        $sql = sprintf("TRUNCATE TABLE %s CASCADE",
            $this->getTableName()
        );

        return $this->query($sql);
    }

    public function getOneWithPosts($slug)
    {
        $postMap = $this->connection->getMapFor('StoneAppleDev\PublicSchema\Post');
        $relationMap = $this->connection->getMapFor('StoneAppleDev\PublicSchema\PostTag');

        $sql = "SELECT
          %s,
          array_agg(post) AS posts
        FROM
          %s 
            LEFT JOIN %s ON tag.id = post_tag.tag_id
            LEFT JOIN %s ON post_tag.post_id = post.id
        WHERE
            tag.slug = ?
        GROUP BY
          %s";

        $sql = sprintf($sql,
            join(', ', $this->getSelectFields('tag')),
            $this->getTableName('tag'),
            $relationMap->getTableName('post_tag'),
            $postMap->getTableName('post'),
            join(', ', $this->getGroupByFields('tag'))
        );

        $this->addVirtualField('posts', $postMap->getTableName().'[]');

        return $this->query($sql, array($slug));
    }
}
