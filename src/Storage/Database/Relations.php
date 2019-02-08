<?php

namespace Pheral\Essential\Storage\Database;

use Pheral\Essential\Storage\Database\Relation\BelongsTo;
use Pheral\Essential\Storage\Database\Relation\BelongsToNeighbors;
use Pheral\Essential\Storage\Database\Relation\BelongsToThrough;
use Pheral\Essential\Storage\Database\Relation\HasMany;
use Pheral\Essential\Storage\Database\Relation\HasManyMembers;
use Pheral\Essential\Storage\Database\Relation\HasManyThrough;
use Pheral\Essential\Storage\Database\Relation\HasOne;

class Relations
{
    /**
     * ОДИН-К-ОДНОМУ прямая связь (от владельца к прямому подчинённому)
     * у текущего владельца есть PRIMARY KEY, на который ссылается FOREIGN KEY прямого подчинённого
     *
     * (holder.id -> target.holder_id) => target
     *   где holder - это текущий экземпляр
     *   а target - это экземпляр искомого прямого подчинённого
     *
     * прямая связь, для обратной self::belongsTo()
     *
     * @param $target
     * @return \Pheral\Essential\Storage\Database\Relation\HasOne
     */
    public static function hasOne($target)
    {
        return new HasOne($target);
    }

    /**
     * ОДИН-КО-МНОГИМ прямая связь (от владельца ко многим подчинённым)
     * у текущего владельца есть PRIMARY KEY, на который ссылается FOREIGN KEY прямых подчинённых
     *
     * (holder.id -> target.holder_id) => target[]
     *   где holder - это текущий экземпляр
     *   а target[] - это массив искомых прямых подчинённых
     *
     * прямая связь, для обратной self::belongsTo()
     *
     * @param string $target
     * @return \Pheral\Essential\Storage\Database\Relation\HasMany
     */
    public static function hasMany($target)
    {
        return new HasMany($target);
    }

    /**
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
     *
     * @param string $target
     * @param string $pivot таблица промежуточного звена
     * @return \Pheral\Essential\Storage\Database\Relation\HasManyThrough
     */
    public static function hasManyThrough($target, $pivot)
    {
        return new HasManyThrough($target, $pivot);
    }

    /**
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
     *
     * @param string $target
     * @param string $pivot таблица связей
     * @return \Pheral\Essential\Storage\Database\Relation\HasManyMembers
     */
    public static function hasManyMembers($target, $pivot)
    {
        return new HasManyMembers($target, $pivot);
    }

    /**
     * ОДИН-К-ОДНОМУ обратная связь / ОДИН-КО-МНОГИМ обратная связь (от подчинённого к прямому владельцу)
     * у текущего подчинённого есть FOREIGN KEY, ссылающийся на PRIMARY KEY прямого владельца
     *
     * (holder.target_id -> target.id) => target
     *   где holder - это текущий экземпляр
     *   а target - это экземпляр искомого прямого владельца
     *
     * обратная связь для self::hasOne() и self::hasMany()
     *
     * @param string $target
     * @return \Pheral\Essential\Storage\Database\Relation\BelongsTo
     */
    public static function belongsTo($target)
    {
        return new BelongsTo($target);
    }

    /**
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
     *
     * @param string $target
     * @param string $pivot таблица промежуточного звена
     * @return \Pheral\Essential\Storage\Database\Relation\BelongsToThrough
     */
    public static function belongsToThrough($target, $pivot)
    {
        return new BelongsToThrough($target, $pivot);
    }

    /**
     * МНОГИЕ-КО-МНОГИМ через владельца (от подчинённого через общего владельца ко многим смежным подчинённым)
     *
     * (holder.pivot_id -> pivot.id -> target.pivot_id) => target[]
     * где:
     *   holder - это текущий экземпляр
     *   pivot - это прямой общий владелец
     *   target[] - это массив искомых смежных подчинённых
     *
     * обратной связью является эта же связь (симметричная связь)
     *
     * @param string $target
     * @param string $pivot таблица общего владельца
     * @return \Pheral\Essential\Storage\Database\Relation\BelongsToNeighbors
     */
    public static function belongsToNeighbors($target, $pivot)
    {
        return new BelongsToNeighbors($target, $pivot);
    }
}
