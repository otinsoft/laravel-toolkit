<?php

namespace Otinsoft\Toolkit\Tests;

use PHPUnit\Framework\TestCase;
use Otinsoft\Toolkit\JavaScript\ScriptVariables;

class ScriptVariablesTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        ScriptVariables::clear();
    }

    /** @test */
    public function add_basic_variable()
    {
        ScriptVariables::add('foo', 'bar');

        $this->assertEquals('<script>window.config = {"foo":"bar"};</script>', ScriptVariables::render());
    }

    /** @test */
    public function add_array_variable()
    {
        ScriptVariables::add([
            'key1' => 'foo',
            'key2' => 'bar',
        ]);

        $this->assertEquals('<script>window.config = {"key1":"foo","key2":"bar"};</script>', ScriptVariables::render());
    }

    /** @test */
    public function add_nested_array_variable()
    {
        $sv = ScriptVariables::add([
            'data.user' => 'foo',
        ]);

        $this->assertEquals('<script>window.config = {"data":{"user":"foo"}};</script>', ScriptVariables::render());
    }

    /** @test */
    public function add_variable_via_closure()
    {
        ScriptVariables::add(function () {
            return [
                'data.user' => 'foo',
            ];
        });

        $this->assertEquals('<script>window.config = {"data":{"user":"foo"}};</script>', ScriptVariables::render());
    }

    /** @test */
    public function set_namespace()
    {
        $this->assertEquals('<script>window.custom = [];</script>', ScriptVariables::render('custom'));
    }

    /** @test */
    public function clear_variables()
    {
        ScriptVariables::add('foo', 'bar');

        ScriptVariables::clear();

        $this->assertEquals('<script>window.config = [];</script>', ScriptVariables::render());
    }
}
