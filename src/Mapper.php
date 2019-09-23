<?php

namespace albertborsos\cycle;

class Mapper extends \Cycle\ORM\Mapper\Mapper
{
    /**
     * @inheritdoc
     */
    public function init(array $data): array
    {
        $class = $this->resolveClass($data);

        return [\Yii::createObject($class), $data];
    }
}
