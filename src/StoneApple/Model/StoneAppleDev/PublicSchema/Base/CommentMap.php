<?php

namespace StoneAppleDev\PublicSchema\Base;

use \Pomm\Object\BaseObjectMap;
use \Pomm\Exception\Exception;

abstract class CommentMap extends BaseObjectMap
{
    public function initialize()
    {

        $this->object_class =  'StoneAppleDev\PublicSchema\Comment';
        $this->object_name  =  'public.comment';

        $this->addField('id', 'int4');
        $this->addField('post_id', 'int4');
        $this->addField('name', 'varchar');
        $this->addField('email', 'varchar');
        $this->addField('website', 'varchar');
        $this->addField('body', 'text');
        $this->addField('created_at', 'timestamp');

        $this->pk_fields = array('id');
    }
}
