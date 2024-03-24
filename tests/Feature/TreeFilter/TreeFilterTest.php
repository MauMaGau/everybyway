<?php

namespace Tests\Feature\TreeFilter;

use App\Models\HomeArea;
use App\Models\Ping;
use App\Models\User;
use App\Services\TreeFilter\TreeFilter;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TreeFilterTest extends TestCase
{
    public function test_item_is_created()
    {
        $treeFilter = new TreeFilter();

        $treeFilter->createOrUpdateItem('name', collect('item'), null, collect('child'));

        $this->assertEquals('name', $treeFilter->items->first()->name);
        $this->assertCount(1, $treeFilter->items->first()->items);
        $this->assertCount(1, $treeFilter->items->first()->children);
    }

    public function test_item_is_updated()
    {
        $treeFilter = new TreeFilter();

        $treeFilter->createOrUpdateItem('name', collect('item'), null, collect('child'));
        $treeFilter->createOrUpdateItem('name', collect('item2'), null, collect('child2'));

        $this->assertCount(1, $treeFilter->items);
        $this->assertEquals('name', $treeFilter->items->first()->name);
        $this->assertEquals('item2', $treeFilter->items->first()->items->last());
        $this->assertEquals('child2', $treeFilter->items->first()->children->last());
    }

    public function test_children_are_created()
    {
        $treeFilter = new TreeFilter();

        $parent = $treeFilter->createOrUpdateItem('name', collect('item'), null, collect('child'));
        $child = $treeFilter->createOrUpdateItem('child', collect('item'), $parent, collect('child'));

        $this->assertEquals('child', $parent->children->first()->name('child'));
    }

    public function test_children_are_set_on_parent()
    {
        $treeFilter = new TreeFilter();

        $parent = $treeFilter->createOrUpdateItem('name', collect('item'), null, );
        $treeFilter->createOrUpdateItem('child', collect('item'), $parent);

        $this->assertCount(1, $parent->children);
        $this->assertEquals('child', $parent->children->first()->name);
    }

    public function test_children_are_references()
    {
        $treeFilter = new TreeFilter();

        $parent = $treeFilter->createOrUpdateItem('name', collect('item'), null, );
        $child = $treeFilter->createOrUpdateItem('child', collect('item'), $parent);

        $child->name= 'child2';

        $this->assertEquals('child2', $parent->children->first()->name);
    }
}
