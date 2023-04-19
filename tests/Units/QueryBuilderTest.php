<?php

namespace App\Units;


use App\Database\QueryBuilder;
use App\Exception\DatabaseConnexionException;
use App\Exception\InvalidArgumentException;
use App\Exception\MissingArgumentException;
use App\Exception\NotFoundException;
use App\Helpers\DbQueryBuilderFactory;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    private QueryBuilder $queryBuilder;

    /**
     * @throws DatabaseConnexionException
     * @throws NotFoundException
     * @throws MissingArgumentException
     */
    public function setUp(): void
    {
        $this->queryBuilder = DbQueryBuilderFactory::make(
            'database',
            'mysqli',
            ['db_name' => 'bug_app_testing']
        );

        $this->queryBuilder->beginTransaction();
        parent::setUp();
    }

    public function testItCanCreateRecord()
    {
        $id = $this->insertIntoTable();
        self::assertNotNull($id);
    }

    public function testItCanPerformRowQuery()
    {
        $id = $this->insertIntoTable();

        $result = $this->queryBuilder->raw('SELECT * FROM reports')->get();
        self::assertNotNull($result);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testItCanPerformSelectQuery()
    {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder
            ->table('reports')
            ->select('*')
            ->where('id', $id)
            ->runQuery()
            ->first();

        self::assertNotNull($result);
        self::assertSame($id, $result->id);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testItCanPerformSelectQueryWithMultipleWhereClause()
    {
        $id = $this->insertIntoTable();

        $result = $this->queryBuilder
            ->table('reports')
            ->select('*')
            ->where('id', $id)
            ->where('report_type', '=', 'Report type 1')
            ->runQuery()
            ->first();

        self::assertNotNull($result);
        self::assertSame($id, $result->id);
        self::assertSame('Report type 1', $result->report_type);

    }


    /**
     * @throws InvalidArgumentException
     */
    public function testItCanFindOneRecordById()
    {
        $id = $this->insertIntoTable();

        $result = $this->queryBuilder
            ->table('reports')
            ->select()
            ->find($id);

        self::assertNotNull($result);
        self::assertSame($id, $result->id);
        self::assertSame('Report type 1', $result->report_type);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testItCanFindByGivenValue()
    {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder
            ->table('reports')
            ->select()
            ->findOneBy('report_type', 'Report type 1');

        self::assertNotNull($result);
        self::assertSame($id, $result->id);
        self::assertSame('Report type 1', $result->report_type);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testItCanUpdateGivenRecord()
    {
        $id = $this->insertIntoTable();
        $count = $this->queryBuilder->table('reports')->update([
            'report_type' => 'Report Type 1 updated'
        ])->where('id', $id)->runQuery()->affected();

        self::assertEquals(1, $count);
        $result = $this->queryBuilder->select('*')->find($id);
        self::assertNotNull($result);
        self::assertSame($id, $result->id);
        self::assertSame('Report Type 1 updated', $result->report_type);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testItCanDeleteGivenRecord()
    {
        $id = $this->insertIntoTable();
        $count = $this->queryBuilder
            ->table('reports')
            ->delete()
            ->where('id', $id)
            ->runQuery()
            ->affected();

        self::assertEquals(1, $count);

        $result = $this->queryBuilder->select('*')->find($id);
        self::assertNull($result);
    }


    public function tearDown(): void
    {
        $this->truncateTables();
        $this->queryBuilder->rollBack();
        parent::tearDown();
    }

    /**
     * @return int
     */
    public function insertIntoTable(): int
    {
        $data = [
            'report_type' => 'Report type 1',
            'message' => 'This is a dummy message',
            'email' => 'support@lucasdev',
            'link' => 'https://lucasdev.com',
            'created_at' => date('Y-m-d H:i:s'),
        ];
        return $this->queryBuilder->table('reports')->create($data);
    }

    public function truncateTables(): void
    {
        $tables = ['reports'];
        foreach ($tables as $table) {
            $this->queryBuilder->raw("DELETE FROM $table");
        }
    }

}