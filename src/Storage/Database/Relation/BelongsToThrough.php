<?php

namespace Pheral\Essential\Storage\Database\Relation;

use Pheral\Essential\Storage\Database\Query;
use Pheral\Essential\Storage\Database\Relation\Abstracts\ThreeTableRelationAbstract;

/**
 * Class BelongsToThrough
 *
 * ОДИН-К-ОДНОМУ по цепочке (от подчинённого через прямого владельца к косвенному владельцу)
 * у текущего подчинённого есть FOREIGN KEY, ссылающийся на PRIMARY KEY прямого владельца
 * и, в свою очередь, у прямого владельца есть FOREIGN KEY, ссылающийся на PRIMARY KEY косвенного владельца
 *
 * (holder.pivot_id -> pivot.id && pivot.target_id -> target.id) => target
 * где:
 *   holder - это текущий экземпляр
 *   pivot - это прямой владелец
 *   target - это экземпляр искомого косвенного владельца
 *
 * обратная связь для self::hasManyThrough()
 */
class BelongsToThrough extends ThreeTableRelationAbstract
{
    /**
     * BelongsToThrough constructor.
     *
     * @param string $target
     * @param string $pivot таблица промежуточного звена
     */
    public function __construct($target, $pivot)
    {
        $this->setPivotTable($pivot)
            ->setTargetTable($target);
    }

    /**
     * @param string $holderKeyToPivot
     * @param string $pivotKeyToTarget
     * @param string $pivotKey
     * @param string $targetKey
     * @return \Pheral\Essential\Storage\Database\Relation\BelongsToThrough
     */
    public function setKeys($holderKeyToPivot, $pivotKeyToTarget, $pivotKey = 'id', $targetKey = 'id')
    {
        $this->setHolderKeyToPivot($holderKeyToPivot)
            ->setPivotKey($pivotKey)
            ->setPivotKeyToTarget($pivotKeyToTarget)
            ->setTargetKey($targetKey);

        return $this;
    }

    /**
     * @return \Pheral\Essential\Storage\Database\Query
     */
    public function getQuery()
    {
        $holderValues = data_pluck($this->holderRows, $this->holderKeyToPivot);
        $query = (new Query($this->targetClass, 'target'))
            ->fields([
                'target.*',
                'pivot.' . $this->pivotKey . ' as pivot_key',
            ])
            ->join($this->pivotClass, 'pivot', 'pivot.' . $this->pivotKeyToTarget . ' = target.' . $this->targetKey)
            ->whereIn('pivot.' . $this->pivotKey, $holderValues);
        return $query;
    }

    /**
     * @param string $relationName
     * @param callable|null $callable
     * @return array
     */
    public function apply($relationName, $callable = null)
    {
        $query = $this->getQuery()
            ->with($this->targetRelations);
        if (is_callable($callable)) {
            $callable($query);
        }
        $targets = $query->select()->all();
        $targetsByPivots = [];
        foreach ($targets as $target) {
            $targetsByPivots[$target->pivot_key] = $target;
        }
        foreach ($this->holderRows as $index => $holderRow) {
            $holderRow->{$relationName} = array_get($targetsByPivots, $holderRow->{$this->holderKeyToPivot});
            $this->holderRows[$index] = $holderRow;
        }
        return $this->holderRows;
    }
}
