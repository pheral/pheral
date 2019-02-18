<?php

namespace Pheral\Essential\Storage\Database\Relation;

use Pheral\Essential\Storage\Database\Relation\Abstracts\ThreeTableRelationAbstract;

/**
 * Class HasManyThrough
 *
 * ОДИН-КО-МНОГИМ по цепочке (от владельца через многих прямых подчинённых ко многим косвенным подчинённым)
 * у текущего владельца есть PRIMARY KEY, на который ссылается FOREIGN KEY прямых подчинённых
 * и, в свою очередь, у прямых подчинённых есть PRIMARY KEY, на которые ссылаются FOREIGN KEY косвенных подчинённых
 *
 * (holder.id -> pivot.holder_id && pivot.id -> target.pivot_id) => target[]
 * где:
 *   holder - это текущий экземпляр
 *   pivot - это связующее звено прямых подчинённых
 *   target[] - это массив искомых косвенных подчинённых
 *
 * обратная связь для self::belongsToThrough()
 */
class HasManyThrough extends ThreeTableRelationAbstract
{
    /**
     * HasManyThrough constructor.
     *
     * @param string $target
     * @param string $pivot таблица промежуточного звена
     */
    public function __construct($target, $pivot)
    {
        $this->setPivot($pivot)
            ->setTarget($target);
    }

    /**
     * @param string $pivotKeyToHolder
     * @param string $targetKeyToPivot
     * @param string $pivotKey
     * @param string $holderKey
     * @return \Pheral\Essential\Storage\Database\Relation\HasManyThrough
     */
    public function setKeys($pivotKeyToHolder, $targetKeyToPivot, $pivotKey = 'id', $holderKey = 'id')
    {
        $this->setHolderKey($holderKey)
            ->setPivotKeyToHolder($pivotKeyToHolder)
            ->setPivotKey($pivotKey)
            ->setTargetKeyToPivot($targetKeyToPivot);

        return $this;
    }

    /**
     * @return \Pheral\Essential\Storage\Database\Query
     */
    public function getQuery()
    {
        $holderValues = array_unique(data_pluck($this->holderRows, $this->holderKey));
        $query = $this->getConnection()
            ->query($this->getTarget(), 'target')
            ->fields([
                'target.*',
                'pivot.' . $this->pivotKeyToHolder . ' as pivot_key_to_holder',
            ])
            ->join($this->getPivot(), 'pivot', 'pivot.' . $this->pivotKey . ' = target.' . $this->targetKeyToPivot)
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
