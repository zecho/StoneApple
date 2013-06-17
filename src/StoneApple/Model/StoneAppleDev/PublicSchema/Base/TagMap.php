<?php

namespace StoneAppleDev\PublicSchema\Base;

use \Pomm\Object\BaseObjectMap;
use \Pomm\Exception\Exception;

abstract class TagMap extends BaseObjectMap
{
    public function initialize()
    {

        $this->object_class =  'StoneAppleDev\PublicSchema\Tag';
        $this->object_name  =  'public.tag';

        $this->addField('id', 'int4');
        $this->addField('label', 'varchar');
        $this->addField('slug', 'varchar');

        $this->pk_fields = array('id');
    }
}
