<?php

namespace Pheral\Essential\Storage\Database\Relation;

use Pheral\Essential\Storage\Database\Query;
use Pheral\Essential\Storage\Database\Relation\Abstracts\TwoTableRelationAbstract;

/**
 * Class BelongsTo
 *
 * ОДИН-К-ОДНОМУ обратная связь / ОДИН-КО-МНОГИМ обратная связь (от подчинённого к прямому владельцу)
 * у текущего подчинённого есть FOREIGN KEY, ссылающийся на PRIMARY KEY прямого владельца
 *
 * (holder.target_id -> target.id) => target
 *   где holder - это текущий экземпляр
 *   а target - это экземпляр искомого прямого владельца
 *
 * обратная связь для self::hasOne() и self::hasMany()
 */
class BelongsTo extends TwoTableRelationAbstract
{
    /**
     * BelongsTo constructor.
     *
     * @param string $target
     */
    public function __construct($target)
    {
        $this->setTargetTable($target);
    }

    /**
     * @param string $holderKeyToTarget
     * @param string $targetKey
     * @return \Pheral\Essential\Storage\Database\Relation\BelongsTo
     */
    public function setKeys($holderKeyToTarget, $targetKey = 'id')
    {
        $this->setHolderKeyToTarget($holderKeyToTarget)
            ->setTargetKey($targetKey);

        return $this;
    }

    /**
     * @return \Pheral\Essential\Storage\Database\Query
     */
    public function getQuery()
    {
        $holderValues = array_unique(data_pluck($this->holderRows, $this->holderKeyToTarget));
        $query = (new Query($this->targetClass, 'target'))
            ->fields(['target.*'])
            ->whereIn('target.' . $this->targetKey, $holderValues);
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
        $targetsByKey = [];
        foreach ($targets as $target) {
            $targetsByKey[$target->{$this->targetKey}] = $target;
        }
        foreach ($this->holderRows as $index => $holderRow) {
            if ($targetRow = array_get($targetsByKey, $holderRow->{$this->holderKeyToTarget})) {
                $targetRow = clone $targetRow;
            }
            $holderRow->{$relationName} = $targetRow;
            $this->holderRows[$index] = $holderRow;
        }
        return $this->holderRows;
    }
}
