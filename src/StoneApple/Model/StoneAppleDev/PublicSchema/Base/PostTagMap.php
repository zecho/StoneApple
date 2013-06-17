<?php

namespace StoneAppleDev\PublicSchema\Base;

use \Pomm\Object\BaseObjectMap;
use \Pomm\Exception\Exception;

abstract class PostTagMap extends BaseObjectMap
{
    public function initialize()
    {

        $this->object_class =  'StoneAppleDev\PublicSchema\PostTag';
        $this->object_name  =  'public.post_tag';

        $this->addField('id', 'int4');
        $this->addField('post_id', 'int4');
        $this->addField('tag_id', 'int4');

        $this->pk_fields = array('id');
    }
}
