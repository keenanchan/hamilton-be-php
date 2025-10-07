<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Concerns\UsesUuid;
use Illuminate\Support\Facades\Schema;

class UsesUuidTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): variant_mod
    {
        parent::setUp();

        // Ad-hoc test table
        Schema::create('uuid_things', function ($t) {
            $t->uuid('id')->primary();
            $t->string('name');
            $t->timestamps();
        });
    }

    /** @test */
    public function sets_uuid_and_non_incrementing_key_type()
    {
        $model = new class extends Model {
            use \App\Models\Concerns\UsesUuid;

            protected $table = 'uuid_things';
            protected $guarded = [];
            protected static function boot() {
                parent::boot();
                static::bootUsesUuid();
            }
        };
        
        $created = $model::create(['name' => 'x']);

        $this->assertIsString($created->id);
        $this->assertMatchesRegularExpression(
            '/^[0-9a-fA-F-]{36}$/',
            $created->id
        );
        $this->assertFalse($created->getIncrementing());
        $this->assertSame('string', $created->getKeyType());
    }
}