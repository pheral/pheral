<?php

namespace App\Models;

use App\DataTables\Dummy;
use App\DataTables\Test;
use App\Models\Abstracts\Model;
use Pheral\Essential\Storage\Database\DB;

class Example extends Model
{
    public function test()
    {
        $createTest = DB::query(
            'CREATE TABLE IF NOT EXISTS test ('
            .'id INT NOT NULL AUTO_INCREMENT,'
            .'title VARCHAR(45) NULL,'
            .'PRIMARY KEY (id)'
            .')'
        );
        $createDummy = DB::query(
            'CREATE TABLE IF NOT EXISTS dummy ('
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
            .')'
        );
        // INSERT
        $firstTestId = $this->addTestRow('first');
        $secondTestId = $this->addTestRow('second');
        $thirdTestId = $this->addTestRow('third');
        // SELECT
        $testList = $this->getTestList();
        $firstTest = $this->getTestRow($firstTestId);
        $mixedList = $this->getMixedList($firstTest);
        // UPDATE
        $edits = [];
        foreach ($mixedList as $mixedRow) {
            $newTitle = $mixedRow->title . '-updated';
            $edits[$mixedRow->title.'Test'] = $this->editTestRow($mixedRow->id, $newTitle);
        }
        // DELETE
        $drops = [];
        foreach ($testList as $testRow) {
            $drops[$testRow->title] = $this->deleteTestRow($testRow->id);
        }
        $dropDummy = DB::query('DROP TABLE dummy');
        $truncateTest = DB::query('TRUNCATE TABLE test');
        // results
        return [
            'CREATE test' => $createTest,
            'CREATE dummy' => $createDummy,
            'INSERT' => [
                'firstTestId' => $firstTestId,
                'secondTestId' => $secondTestId,
                'thirdTestId' => $thirdTestId,
            ],
            'SELECT' => [
                'testList' => $testList,
                'firstTest' => $firstTest,
                'mixedList' => $mixedList,
            ],
            'UPDATE' => $edits,
            'DELETE' => $drops,
            'DROP dummy' => $dropDummy,
            'TRUNCATE test' => $truncateTest,
        ];
    }
    protected function addTestRow($title = null)
    {
        $query = Test::query()->values(['title' => $title ?? microtime()]);
        $result = $query->insert();
        return $result->lastInsertId();
    }
    protected function getTestRow($testId)
    {
        return Test::query()
            ->where('id', '=', $testId)
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
        if ($result->count()) {
            return $newTitle;
        }
        return 'not affected';
    }
    protected function deleteTestRow($testId)
    {
        $query = Test::query()->where('id', '=', $testId);
        $result = $query->delete();
        if ($result->count()) {
            return 'deleted';
        }
        return 'not affected';
    }
}
