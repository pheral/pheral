<?php

namespace Pheral\Essential\Storage\Database\Relation;

use Pheral\Essential\Storage\Database\Query;
use Pheral\Essential\Storage\Database\Relation\Abstracts\TwoTableRelationAbstract;

/**
 * Class HasOne
 *
 * ОДИН-К-ОДНОМУ прямая связь (от владельца к прямому подчинённому)
 * у текущего владельца есть PRIMARY KEY, на который ссылается FOREIGN KEY прямого подчинённого
 *
 * (holder.id -> target.holder_id) => target
 *   где holder - это текущий экземпляр
 *   а target - это экземпляр искомого прямого подчинённого
 *
 * прямая связь, для обратной self::belongsTo()
 */
class HasOne extends TwoTableRelationAbstract
{
    /**
     * HasOne constructor.
     *
     * @param string $target
     */
    public function __construct($target)
    {
        $this->setTargetTable($target);
    }

    /**
     * @param string $targetKeyToHolder
     * @param string $holderKey
     * @return \Pheral\Essential\Storage\Database\Relation\HasOne
     */
    public function setKeys($targetKeyToHolder, $holderKey = 'id')
    {
        $this->setHolderKey($holderKey)
            ->setTargetKeyToHolder($targetKeyToHolder);

        return $this;
    }

    /**
     * @return \Pheral\Essential\Storage\Database\Query
     */
    public function getQuery()
    {
        $holderPrimaryValues = array_pluck($this->holderRows, $this->holderKey);
        $query = (new Query($this->targetClass, 'target'))
            ->fields(['target.*'])
            ->whereIn('target.' . $this->targetKeyToHolder, array_unique($holderPrimaryValues));
        return $query;
    }

    /**
     * @param string $relationName
     * @param callable|null $callable
     * @return array
     */
    public function apply($relationName, $callable = null)
    {
        $relationQuery = $this->getQuery()
            ->with($this->targetRelations);
        if (is_callable($callable)) {
            $callable($relationQuery);
        }
        $targets = $relationQuery->select()->all();
        $targetsByHolder = [];
        foreach ($targets as $target) {
            $targetsByHolder[$target->{$this->targetKeyToHolder}] = $target;
        }
        foreach ($this->holderRows as $index => $holderRow) {
            $holderRow->{$relationName} = array_get($targetsByHolder, $holderRow->{$this->holderKey});
            $this->holderRows[$index] = $holderRow;
        }
        return $this->holderRows;
    }
}
