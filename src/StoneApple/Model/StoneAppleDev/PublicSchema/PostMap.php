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
}
