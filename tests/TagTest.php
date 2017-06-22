<?php

namespace Otinsoft\Toolkit\Tests;

use Otinsoft\Toolkit\Tags\Tag;
use Otinsoft\Toolkit\Tags\Taggable;
use Otinsoft\Toolkit\Database\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TagTest extends TestCase
{
    use DatabaseTransactions;

    protected $testModel;

    public function setUp()
    {
        parent::setUp();

        include_once __DIR__.'/../migrations/create_tags_table.php.stub';
        (new \CreateTagsTable())->up();

        Schema::create('test_models', function ($table) {
            $table->increments('id');
            $table->string('name')->nullable();
        });

        $this->testModel = TestModel::create(['name' => 'default']);
    }

    /** @test */
    public function it_can_create_a_tag()
    {
        $tag = Tag::findOrCreateFromString('string');

        $this->assertCount(1, Tag::all());
        $this->assertSame('string', $tag->name);
    }

    /** @test */
    public function it_will_not_create_a_tag_if_the_tag_already_exists()
    {
        Tag::findOrCreate('string');
        Tag::findOrCreate('string');

        $this->assertCount(1, Tag::all());
    }

    /** @test */
    public function it_can_create_tags_using_an_array()
    {
        Tag::findOrCreate(['tag1', 'tag2', 'tag3']);

        $this->assertCount(3, Tag::all());
    }

    /** @test */
    public function it_can_create_tags_using_a_collection()
    {
        Tag::findOrCreate(collect(['tag1', 'tag2', 'tag3']));

        $this->assertCount(3, Tag::all());
    }

    /** @test */
    public function its_name_can_be_changed_by_setting_its_name_property_to_a_new_value()
    {
        $tag = Tag::findOrCreate('my tag');
        $tag->name = 'new name';
        $tag->save();

        $this->assertEquals('new name', $tag->name);
    }

    /** @test */
    public function it_provides_a_tags_relation()
    {
        $this->assertInstanceOf(MorphToMany::class, $this->testModel->tags());
    }

    /** @test */
    public function it_can_attach_a_tag()
    {
        $this->testModel->attachTag('tagName');

        $this->assertCount(1, $this->testModel->tags);

        $this->assertEquals(['tagName'], $this->testModel->tags->pluck('name')->toArray());
    }

    /** @test */
    public function it_can_attach_a_tag_multiple_times_without_creating_duplicate_entries()
    {
        $this->testModel->attachTag('tagName');
        $this->testModel->attachTag('tagName');

        $this->assertCount(1, $this->testModel->tags);
    }

    /** @test */
    public function it_can_use_a_tag_model_when_attaching_a_tag()
    {
        $tag = Tag::findOrCreate('tagName');

        $this->testModel->attachTag($tag);

        $this->assertEquals(['tagName'], $this->testModel->tags->pluck('name')->toArray());
    }

    /** @test */
    public function it_can_attach_a_tag_via_the_tags_mutator()
    {
        $this->testModel->tags = ['tag1'];

        $this->assertCount(1, $this->testModel->tags);
    }

    /** @test */
    public function it_can_attach_multiple_tags_via_the_tags_mutator()
    {
        $this->testModel->tags = ['tag1', 'tag2'];

        $this->assertCount(2, $this->testModel->tags);
    }

    /** @test */
    public function it_can_attach_multiple_tags()
    {
        $this->testModel->attachTags(['test1', 'test2']);

        $this->assertCount(2, $this->testModel->tags);
    }

    /** @test */
    public function it_can_attach_a_existing_tag()
    {
        $this->testModel->attachTag(Tag::findOrCreate('test'));

        $this->assertCount(1, $this->testModel->tags);
    }

    /** @test */
    public function it_can_detach_a_tag()
    {
        $this->testModel->attachTags(['test1', 'test2', 'test3']);

        $this->testModel->detachTag('test2');

        $this->assertEquals(['test1', 'test3'], $this->testModel->tags->pluck('name')->toArray());
    }

    /** @test */
    public function it_can_detach_multiple_tags()
    {
        $this->testModel->attachTags(['test1', 'test2', 'test3']);

        $this->testModel->detachTags(['test1', 'test3']);

        $this->assertEquals(['test2'], $this->testModel->tags->pluck('name')->toArray());
    }

    /** @test */
    public function it_provides_as_scope_to_get_all_models_that_have_any_of_the_given_tags()
    {
        TestModel::create(['name' => 'model1'])->attachTag('tagA');
        TestModel::create(['name' => 'model2'])->attachTag('tagB');
        TestModel::create(['name' => 'model3'])->attachTags(['tagA', 'tagB', 'tagC']);

        $testModels = TestModel::withAnyTags(['tagB', 'tagC']);

        $this->assertEquals(['model2', 'model3'], $testModels->pluck('name')->toArray());
    }

    /** @test */
    public function it_provides_a_scope_to_get_all_models_that_have_any_of_the_given_tag_instances()
    {
        $tag = Tag::findOrCreate('tagA', 'typeA');

        TestModel::create(['name' => 'model1'])->attachTag($tag);

        $testModels = TestModel::withAnyTags([$tag]);

        $this->assertEquals(['model1'], $testModels->pluck('name')->toArray());
    }

    /** @test */
    public function it_can_sync_a_single_tag()
    {
        $this->testModel->attachTags(['tag1', 'tag2', 'tag3']);

        $this->testModel->syncTags(['tag3']);

        $this->assertEquals(['tag3'], $this->testModel->tags->pluck('name')->toArray());
    }

    /** @test */
    public function it_can_sync_multiple_tags()
    {
        $this->testModel->attachTags(['tag1', 'tag2', 'tag3']);

        $this->testModel->syncTags(['tag3', 'tag4']);

        $this->assertEquals(['tag3', 'tag4'], $this->testModel->tags->pluck('name')->toArray());
    }

    /** @test */
    public function it_can_attach_a_tag_inside_a_static_create_method()
    {
        $testModel = TestModel::create([
            'name' => 'testModel',
            'tags' => ['tag', 'tag2'],
        ]);

        $this->assertCount(2, $testModel->tags);
    }
}

class TestModel extends Model
{
    use Taggable;

    public $timestamps = false;
}
