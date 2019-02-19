<?php

namespace App\Models;

use App\DBTables\Example\Dummy;
use App\DBTables\Example\Test;
use App\Models\Abstracts\Model;
use Pheral\Essential\Storage\Database\DB;

class Example extends Model
{
    protected $canCreate = true;
    protected $canInsert = true;
    protected $canUpdate = true;
    protected $canDelete = true;
    protected $canDrop = true;
    protected $canTruncate = true;
    public function test()
    {
        $results = [];

        if ($this->canCreate) {
            $results['CREATE'] = $this->createTables();
        }

        if ($this->canInsert) {
            // INSERT
            $firstTestId = $this->addTestRow('first');
            $firstDummyId = $this->addDummyRow($firstTestId, 'experimental');
            $results['INSERT'] = [
                'firstTestId' => $firstTestId,
                'secondTestId' => $this->addTestRow('second'),
                'thirdTestId' => $this->addTestRow('third'),
                'firstDummyId' => $firstDummyId,
            ];
            $firstTest = $this->getTestRow($firstTestId);
            $firstDummy = $this->getDummyRow($firstDummyId);
        } else {
            $firstTest = $this->getTestRowByTitle('first');
            $firstDummy = $this->getDummyRowByParam('experimental');
        };

        // SELECT
        $testList = $this->getTestList();
        $mixedList = $this->getMixedList($firstTest);
        $results['SELECT'] = [
            'ALL `test`' => $testList,
            'FIRST `test`' => $firstTest,
            'FIRST `dummy`' => $firstDummy,
            'MIXED' => $mixedList,
        ];

        if ($this->canUpdate) {
            // UPDATE
            $updates = [];
            foreach ($mixedList as $mixedRow) {
                $newTitle = $mixedRow->title . '-updated';
                $updates[$mixedRow->title.'Test'] = $this->editTestRow($mixedRow->id, $newTitle);
            }
            $results['UPDATE'] = $updates;
        }

        if ($this->canDelete) {
            // DELETE
            $deletes = [];
            foreach ($testList as $testRow) {
                $deletes[$testRow->title] = $this->deleteTestRow($testRow->id);
            }
            $results['DELETE'] = $deletes;
        }

        if ($this->canDrop) {
            // DROP
            $results['DROP'] = [
                'dummy' => $this->dropTable('dummy'),
            ];
        }

        if ($this->canTruncate) {
            // TRUNCATE
            $results['TRUNCATE'] = [
                'test' => $this->truncateTable('test'),
            ];
        }

        profiler()->database()->debug();

        return $results;
    }
    protected function addTestRow($title = null)
    {
        return Test::query()
            ->insert(['title' => $title ?? microtime()])
            ->lastInsertId();
    }
    protected function getTestRow($testId)
    {
        return Test::query()
            ->where('id', '=', $testId)
            ->select()
            ->row();
    }
    protected function getTestRowByTitle($testTitle)
    {
        return Test::query()
            ->where('title', '=', $testTitle)
            ->select()
            ->row();
    }
    protected function addDummyRow($testId, $param = null)
    {
        return Dummy::query()
            ->insert([
                'test_id' => $testId,
                'param' => $param,
            ])
            ->lastInsertId();
    }
    protected function getDummyRow($dummyId)
    {
        return Dummy::query()
            ->where('id', '=', $dummyId)
            ->select()
            ->row();
    }
    protected function getDummyRowByParam($param)
    {
        return Dummy::query()
            ->where('param', '=', $param)
            ->select()
            ->row();
    }
    protected function getTestList()
    {
        return $this->newQuery()
            ->select(Test::class)
            ->all();
    }
    protected function getMixedList($excludeTestRow = null)
    {
        $query = $this->newQuery()
            ->fields(['t.id', 't.title', 'd.param'])
            ->table(Test::class, 't')
            ->leftJoin(Dummy::class, 'd', 'd.test_id = t.id')
            ->whereIn('t.title', ['second', 'third'])
            ->orWhere('t.title', '!=', 'first')
            ->whereNull('d.id')
            ->limit(2)
            ->orderBy('t.title', 'DESC')
            ->groupBy('t.id');
        if (isset($excludeTestRow->id)) {
            $query->whereNotIn('t.id', [$excludeTestRow->id]);
        }
        $result = $query->select();
        return $result->all();
    }
    protected function editTestRow($testId, $newTitle = 'updated')
    {
        $query = Test::query()
            ->sets(['title' => $newTitle])
            ->where('id', '=', $testId);
        $result = $query->update();
        return $result->count() ? 'updated' : 'not updated';
    }
    protected function deleteTestRow($testId)
    {
        $query = Test::query()->where('id', '=', $testId);
        $result = $query->delete();
        return $result->count() ? 'deleted' : 'not deleted';
    }
    protected function createTables()
    {
        $creates = [];
        $sqlTest = 'CREATE TABLE IF NOT EXISTS test ('
            .'id INT NOT NULL AUTO_INCREMENT,'
            .'title VARCHAR(45) NULL,'
            .'PRIMARY KEY (id)'
            .')';
        $creates['test'] = DB::execute($sqlTest) ? 'created' : 'not created';
        $sqlDummy = 'CREATE TABLE IF NOT EXISTS dummy ('
            .'id INT NOT NULL AUTO_INCREMENT,'
            .'test_id INT NULL,'
            .'param VARCHAR(45) NULL,'
            .'PRIMARY KEY (id),'
            .'INDEX dummy_test_id_index (test_id ASC),'
            .'CONSTRAINT dummy_test_id_foreign'
            .' FOREIGN KEY (test_id)'
            .' REFERENCES test (id)'
            .' ON DELETE CASCADE'
            .' ON UPDATE CASCADE'
            .')';
        $creates['dummy'] = DB::execute($sqlDummy) ? 'created' : 'not created';
        return $creates;
    }
    protected function dropTable($tableName)
    {
        $sql = 'DROP TABLE ' . $tableName;
        return DB::execute($sql) ? 'dropped' : 'not dropped';
    }
    protected function truncateTable($tableName)
    {
        $sql = 'TRUNCATE TABLE ' . $tableName;
        return DB::execute($sql) ? 'truncated' : 'not truncated';
    }
}
