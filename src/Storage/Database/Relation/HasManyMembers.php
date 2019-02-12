<?php

namespace Pheral\Essential\Storage\Database\Relation;

use Pheral\Essential\Storage\Database\Query;
use Pheral\Essential\Storage\Database\Relation\Abstracts\ThreeTableRelationAbstract;

/**
 * Class HasManyMembers
 *
 * МНОГИЕ-КО-МНОГИМ (равноправные участники, объединённые через общего подчинённого)
 * у общего подчинённого есть два FOREIGN KEY, которые ссылаются на PRIMARY KEY обоих участников
 *
 * (holder.id -> pivot.holder_id && pivot.target_id -> target.id) => target[]
 * где:
 *   holder - это текущий экземпляр
 *   pivot - это прямой общий подчинённый
 *   target[] - это массив искомых равноправных участников
 *
 * обратной связью является эта же связь (симметричная связь)
 */
class HasManyMembers extends ThreeTableRelationAbstract
{
    /**
     * HasManyThrough constructor.
     *
     * @param string $target
     * @param string $pivot таблица связей
     */
    public function __construct($target, $pivot)
    {
        $this->setPivotTable($pivot)
            ->setTargetTable($target);
    }

    /**
     * @param string $pivotKeyToHolder
     * @param string $pivotKeyToTarget
     * @param string $targetKey
     * @param string $holderKey
     * @return \Pheral\Essential\Storage\Database\Relation\HasManyMembers
     */
    public function setKeys($pivotKeyToHolder, $pivotKeyToTarget, $targetKey = 'id', $holderKey = 'id')
    {
        $this->setHolderKey($holderKey)
            ->setPivotKeyToHolder($pivotKeyToHolder)
            ->setPivotKeyToTarget($pivotKeyToTarget)
            ->setTargetKey($targetKey);

        return $this;
    }

    /**
     * @return \Pheral\Essential\Storage\Database\Query
     */
    public function getQuery()
    {
        $holderValues = array_unique(data_pluck($this->holderRows, $this->holderKey));
        $query = (new Query($this->targetClass, 'target'))
            ->fields([
                'target.*',
                'pivot.' . $this->pivotKeyToHolder . ' as pivot_key_to_holder',
            ])
            ->join($this->pivotClass, 'pivot', 'pivot.' . $this->pivotKeyToTarget . ' = target.' . $this->targetKey)
            ->whereIn('pivot.' . $this->pivotKeyToHolder, $holderValues);
        return $query;
    }

    /**
     * @param string $relationName
     * @param callable|null $callable
     * @return array
     */
    public function apply($relationName, $callable = null)
    {
        $targets = $this->getAll($callable);
        $targetsByHolder = [];
        foreach ($targets as $target) {
            $targetsByHolder[$target->pivot_key_to_holder][] = $target;
        }
        foreach ($this->holderRows as $index => $holderRow) {
            if ($targetRows = array_get($targetsByHolder, $holderRow->{$this->holderKey}, [])) {
                foreach ($targetRows as $targetIndex => $targetRow) {
                    $targetRows[$targetIndex] = clone $targetRow;
                }
            }
            $holderRow->{$relationName} = $targetRows;
            $this->holderRows[$index] = $holderRow;
        }
        return $this->holderRows;
    }
}
