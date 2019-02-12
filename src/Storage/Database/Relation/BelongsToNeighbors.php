<?php

namespace Pheral\Essential\Storage\Database\Relation;

use Pheral\Essential\Storage\Database\Query;
use Pheral\Essential\Storage\Database\Relation\Abstracts\ThreeTableRelationAbstract;

/**
 * Class BelongsToNeighbors
 *
 * МНОГИЕ-КО-МНОГИМ через владельца (от подчинённого через общего владельца ко многим смежным подчинённым)
 * у текущего подчинённого есть FOREIGN KEY, ссылающийся на PRIMARY KEY прямого владельца
 * и, в свою очередь, на этот PRIMARY KEY владельца, ссылаются FOREIGN KEY смежные подчинённые
 *
 * (holder.pivot_id -> pivot.id -> target.pivot_id) => target[]
 * где:
 *   holder - это текущий экземпляр
 *   pivot - это прямой общий владелец
 *   target[] - это массив искомых смежных подчинённых
 *
 * обратной связью является эта же связь (симметричная связь)
 */
class BelongsToNeighbors extends ThreeTableRelationAbstract
{
    /**
     * BelongsToNeighbors constructor.
     *
     * @param string $target
     * @param string $pivot таблица общего владельца
     */
    public function __construct($target, $pivot)
    {
        $this->setPivotTable($pivot)
            ->setTargetTable($target);
    }

    /**
     * @param string $holderKeyToPivot
     * @param string $targetKeyToPivot
     * @param string $pivotKey
     * @return \Pheral\Essential\Storage\Database\Relation\BelongsToNeighbors
     */
    public function setKeys($holderKeyToPivot, $targetKeyToPivot, $pivotKey = 'id')
    {
        $this->setHolderKeyToPivot($holderKeyToPivot)
            ->setPivotKey($pivotKey)
            ->setTargetKeyToPivot($targetKeyToPivot);

        return $this;
    }

    /**
     * @return \Pheral\Essential\Storage\Database\Query
     */
    public function getQuery()
    {
        $holderValues = array_unique(data_pluck($this->holderRows, $this->holderKeyToPivot));
        $query = (new Query($this->targetClass, 'target'))
            ->fields([
                'target.*',
                'pivot.' . $this->pivotKey . ' as pivot_key',
            ])
            ->join($this->pivotClass, 'pivot', 'pivot.' . $this->pivotKey . ' = target.' . $this->targetKeyToPivot)
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
        $targets = $this->getAll($callable);
        $targetsByPivots = [];
        foreach ($targets as $target) {
            $targetsByPivots[$target->pivot_key][] = $target;
        }
        foreach ($this->holderRows as $holderIndex => $holderRow) {
            if ($targetRows = array_get($targetsByPivots, $holderRow->{$this->holderKeyToPivot}, [])) {
                foreach ($targetRows as $targetIndex => $targetRow) {
                    $targetRows[$targetIndex] = clone $targetRow;
                }
            }
            $holderRow->{$relationName} = $targetRows;
            $this->holderRows[$holderIndex] = $holderRow;
        }
        return $this->holderRows;
    }
}
