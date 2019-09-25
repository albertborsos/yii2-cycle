<?php

namespace albertborsos\cycle;

class Factory
{
    public static function schema(string $entityClass, string $table, string $primaryKey, array $columns, array $typeCast, array $relations)
    {
        return [
            \Cycle\ORM\Schema::ENTITY => $entityClass,
            \Cycle\ORM\Schema::MAPPER => Mapper::class,
            \Cycle\ORM\Schema::DATABASE => 'default',
            \Cycle\ORM\Schema::TABLE => $table,
            \Cycle\ORM\Schema::PRIMARY_KEY => $primaryKey,
            \Cycle\ORM\Schema::COLUMNS => $columns,
            \Cycle\ORM\Schema::TYPECAST => $typeCast,
            \Cycle\ORM\Schema::RELATIONS => $relations,
        ];
    }

    public static function relation($type, $target, $innerKey, $outerKey, $load = null)
    {
        return [
            \Cycle\ORM\Relation::TYPE =>  $type,
            \Cycle\ORM\Relation::TARGET =>  $target,
            \Cycle\ORM\Relation::LOAD =>  $load,
            \Cycle\ORM\Relation::SCHEMA =>  [
                \Cycle\ORM\Relation::CASCADE => true,
                \Cycle\ORM\Relation::INNER_KEY => $innerKey,
                \Cycle\ORM\Relation::OUTER_KEY => $outerKey,
            ],
        ];
    }
}
