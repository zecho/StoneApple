<?php

namespace StoneAppleDev\PublicSchema;

use StoneAppleDev\PublicSchema\Base\UserMap as BaseUserMap;
use StoneAppleDev\PublicSchema\User;
use \Pomm\Exception\Exception;
use \Pomm\Query\Where;

class UserMap extends BaseUserMap
{
    public function truncate()
    {
        $sql = sprintf("TRUNCATE TABLE %s CASCADE",
            $this->getTableName()
        );

        return $this->query($sql);
    }
}
