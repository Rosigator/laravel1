<?php

namespace Tests;

trait TestHelpers
{
    protected function assertDatabaseEmpty($table, $connection = null)
    {
        $total = $this->getConnection($connection)->table($table)->count();

        $this->assertSame(0, $total, sprintf(
            "Failed asserting the table [%s] is empty. %s %s found.",
            $table,
            $total,
            str_plural('row', $total)
        ));
    }

    protected function assertDatabaseCount($table, $expected, $connection = null)
    {
        $total = $this->getConnection($connection)->table($table)->count();

        $this->assertSame($expected, $total, sprintf(
            "Failed asserting the table [%s] has %s %s.%s %s found instead.",
            $table,
            $expected,
            str_plural('row', $total),
            $total,
            str_plural('row', $total)
        ));
    }

    public function defaultData()
    {
        return $this->defaultData;
    }

    public function withData(array $custom = [])
    {
        return array_merge($this->defaultData(), $custom);
    }
}
