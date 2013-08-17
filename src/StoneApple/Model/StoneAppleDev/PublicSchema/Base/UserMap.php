<?php

namespace StoneAppleDev\PublicSchema\Base;

use \Pomm\Object\BaseObjectMap;
use \Pomm\Exception\Exception;

abstract class UserMap extends BaseObjectMap
{
    public function initialize()
    {

        $this->object_class =  'StoneAppleDev\PublicSchema\User';
        $this->object_name  =  'public.user';

        $this->addField('id', 'int4');
        $this->addField('username', 'varchar');
        $this->addField('password', 'varchar');
        $this->addField('email', 'varchar');
        $this->addField('created_at', 'timestamp');
        $this->addField('updated_at', 'timestamp');

        $this->pk_fields = array('id');
    }
}
