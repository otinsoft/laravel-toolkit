<?php

namespace Otinsoft\Toolkit\Tests;

use Otinsoft\Toolkit\JavaScript\ScriptVariables;

class ScriptVariablesTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        ScriptVariables::clear();
    }

    public function testAddKeyValueVariable()
    {
        ScriptVariables::add('foo', 'bar');

        $this->assertEquals('<script>window.config = {"foo":"bar"};</script>', ScriptVariables::render());
    }

    public function testAddArrayVariable()
    {
        ScriptVariables::add([
            'key1' => 'foo',
            'key2' => 'bar',
        ]);

        $this->assertEquals('<script>window.config = {"key1":"foo","key2":"bar"};</script>', ScriptVariables::render());
    }

    public function testAddNestedArrayVariable()
    {
        $sv = ScriptVariables::add([
            'data.user' => 'foo',
        ]);

        $this->assertEquals('<script>window.config = {"data":{"user":"foo"}};</script>', ScriptVariables::render());
    }

    public function testAddVariableViaClosure()
    {
        ScriptVariables::add(function () {
            return [
                'data.user' => 'foo',
            ];
        });

        $this->assertEquals('<script>window.config = {"data":{"user":"foo"}};</script>', ScriptVariables::render());
    }

    public function testSetNamespace()
    {
        $this->assertEquals('<script>window.custom = [];</script>', ScriptVariables::render('custom'));
    }

    public function testClearVariables()
    {
        ScriptVariables::add('foo', 'bar');

        ScriptVariables::clear();

        $this->assertEquals('<script>window.config = [];</script>', ScriptVariables::render());
    }
}
