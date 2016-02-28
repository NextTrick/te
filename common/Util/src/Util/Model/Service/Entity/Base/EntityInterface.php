<?php

namespace Util\Model\Service\Entity\Base;

interface EntityInterface
{
    /**
     *  Convierte la data de array a objeto
     * 
     * @return Entity
     * 
     */
    public function exchangeArray($data);

    /**
     *  Retorna el objeto en forma de array
     * 
     * @return array
     * 
     */
    public function toArray();
}