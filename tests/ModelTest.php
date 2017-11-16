<?php

namespace Otinsoft\Toolkit\Tests;

use PHPUnit\Framework\TestCase;
use Otinsoft\Toolkit\Database\Model;

class ModelTest extends TestCase
{
    /** @test */
    public function make_attributes_mass_assignable()
    {
        $model = new DummyModel(['name' => 'Foo']);

        $this->assertContains('id', $model->getGuarded());
    }

    /** @test */
    public function serialize_date_to_iso_8601_string()
    {
        $model = new DummyModel(['created_at' => $date = now()]);

        $this->assertEquals($date->toIso8601String(), $model->toArray()['created_at']);
    }
}

class DummyModel extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';
}
