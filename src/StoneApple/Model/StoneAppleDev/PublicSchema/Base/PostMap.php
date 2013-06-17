<?php

namespace StoneAppleDev\PublicSchema\Base;

use \Pomm\Object\BaseObjectMap;
use \Pomm\Exception\Exception;

abstract class PostMap extends BaseObjectMap
{
    public function initialize()
    {

        $this->object_class =  'StoneAppleDev\PublicSchema\Post';
        $this->object_name  =  'public.post';

        $this->addField('id', 'int4');
        $this->addField('title', 'varchar');
        $this->addField('slug', 'varchar');
        $this->addField('body', 'text');
        $this->addField('created_at', 'timestamp');
        $this->addField('updated_at', 'timestamp');

        $this->pk_fields = array('id');
    }
}
