<?php

namespace Tests\Feature;

use App\Models\Armament;
use App\Models\Fleet;
use App\Models\Spacecraft;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use DatabaseSeeder;
use UserSeeder;
use FleetSeeder;

//@todo: tests for validation rules can be added. I just excluded them just for the time-being.
//@todo: there are more scenarios to be added too
//@todo: some of the tests can be broken down to smaller test methods. I just put them in the one method to save time.
//@todo: there are also some repeating code that can be refactored too.
class SpacecraftControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    private $fleetGeneral;

    protected function setUp(): void
    {
        parent::setUp();
        app(DatabaseSeeder::class)->call(FleetSeeder::class);
        app(DatabaseSeeder::class)->call(UserSeeder::class);
    }

    /** @test */
    public function userCanGetSpacecrafts()
    {
        $spacecrafts = factory(Spacecraft::class, 12)->create();

        $this->json('GET', route('api.spacecrafts'))
            ->assertSuccessful()
            ->assertJsonCount(count($spacecrafts), 'data');


    }

    /** @test */
    public function spacecraftListCanBeFilteredByName()
    {
        Passport::actingAs(User::first());
        $names = [
            'tESt'.$this->faker->md5,
            $this->faker->md5.'test',
            $this->faker->md5.'TEST'.$this->faker->md5
        ];
        foreach ($names as $name) {
            factory(Spacecraft::class)->create([
                'name' => $name,
            ]);
        }
        factory(Spacecraft::class, 3)->create();

        $this->json('GET', route('api.spacecrafts.filter', [ 'test', '_', 0 ])) //parameter order - name, class, status
            ->assertSuccessful()
            ->assertJsonCount(count($names), 'data');
    }

    /** @test */
    public function spacecraftListCanBeFilteredByStatus()
    {
        factory(Spacecraft::class, 4)->state(Spacecraft::STATUS_DAMAGED)->create();
        factory(Spacecraft::class, 3)->state(Spacecraft::STATUS_OPERATIONAL)->create();

        //filter by damaged status
        $this->json('GET', route('api.spacecrafts.filter', [ '_', '_', Spacecraft::STATUS_DAMAGED ])) //parameter order - name, class, status
        ->assertSuccessful()
            ->assertJsonCount(4, 'data');
        //filter by operational status
        $this->json('GET', route('api.spacecrafts.filter', [ '_', '_', Spacecraft::STATUS_OPERATIONAL ])) //parameter order - name, class, status
        ->assertSuccessful()
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function spacecraftListCanBeFilteredByClass()
    {
        $classes = [
            'tESt'.$this->faker->md5,
            $this->faker->md5.'test',
            $this->faker->md5.'TEST'.$this->faker->md5
        ];
        foreach ($classes as $class) {
            factory(Spacecraft::class)->create([
                'class' => $class,
            ]);
        }
        factory(Spacecraft::class, 3)->create();

        $this->json('GET', route('api.spacecrafts.filter', [ '_', 'test', 0 ])) //parameter order - name, class, status
        ->assertSuccessful()
            ->assertJsonCount(count($classes), 'data');
    }

    /** @test */
    public function spacecraftsReturnsCorrectJsonStructure()
    {
        $spacecrafts = factory(Spacecraft::class, 2)->create();

        $this->json('GET', route('api.spacecrafts'))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    [
                        'id' => $spacecrafts[0]->id,
                        'name' => $spacecrafts[0]->name,
                        'status' => $spacecrafts[0]->status_label
                    ],
                    [
                        'id' => $spacecrafts[1]->id,
                        'name' => $spacecrafts[1]->name,
                        'status' => $spacecrafts[1]->status_label
                    ]
                ],
            ]);
    }

    /** @test */
    public function fleetGeneralCanGetSpacecraftDetailsWithCorrectJsonStructure()
    {
        $spacecraft = factory(Spacecraft::class)->create();
        $armaments = factory(Armament::class, 3)->create([
            'spacecraft_id' => $spacecraft->id,
        ]);

        $this->json('GET', route('api.spacecrafts.show', $spacecraft))
            ->assertSuccessful()
            ->assertJson([
                'id' => $spacecraft->id,
                'name' => $spacecraft->name,
                'status' => $spacecraft->status_label,
                'class' => $spacecraft->class,
                'crew' => $spacecraft->crew,
                'image' => $spacecraft->image,
                'value' => $spacecraft->value,
                'armament' => [
                    [
                        'title' => $armaments[0]->title,
                        'qty' => $armaments[0]->qty
                    ],
                    [
                        'title' => $armaments[1]->title,
                        'qty' => $armaments[1]->qty
                    ],
                    [
                        'title' => $armaments[2]->title,
                        'qty' => $armaments[2]->qty
                    ]
                ]
            ]);
    }

    /** @test */
    public function unauthorisedToCreateSpacecraftIfUserIsNotLoggedIn()
    {
        $this->json('POST', route('api.spacecrafts.store'))->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function unauthorisedToCreateSpacecraftIfUserDoesNotBelongToAFleet()
    {
        $user = factory(User::class)->create([ 'fleet_id' => null ]);
        Passport::actingAs($user);

        $this->post(route('api.spacecrafts.store'))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function fleetGeneralCanCreateSpacecraft()
    {
        Storage::fake();
        $fleetGeneral = User::first();
        $body = $this->requestBody();
        Passport::actingAs($fleetGeneral);
        $this->json('POST', route('api.spacecrafts.store'), $body)
            ->assertSuccessful();

        $this->assertDatabaseHas('spacecrafts', [
            'name' => $body['name'],
            'class' => $body['class'],
            'crew'=> $body['crew'],
            'value' => $body['value'],
            'status' => $body['status'],
        ]);

        $spacecraft = Spacecraft::first();
        Storage::assertExists($spacecraft->image);
        $armaments = $spacecraft->armaments()->get();
        $this->assertEquals(2, $armaments->count());
        $this->assertEquals($body['armaments'][0]['title'], $armaments[0]->title);
        $this->assertEquals($body['armaments'][0]['qty'], $armaments[0]->qty);
        $this->assertEquals($spacecraft->id, $armaments[0]->spacecraft_id);
        $this->assertEquals($body['armaments'][1]['title'], $armaments[1]->title);
        $this->assertEquals($body['armaments'][1]['qty'], $armaments[1]->qty);
        $this->assertEquals($spacecraft->id, $armaments[1]->spacecraft_id);
    }

    /** @test */
    public function unauthorisedToDeleteSpacecraftIfTheUserDoesNotHaveSameFleetIdAsSpacecraft()
    {
        Storage::fake();
        $fleetGeneral = User::first();
        Passport::actingAs($fleetGeneral);
        $imageFilename = $this->faker->md5 . '.png';
        Storage::put($imageFilename, $this->faker->md5);//mocking the file. But not image file. We are just testing the logic
        $spacecraft = factory(Spacecraft::class)->create([
            'fleet_id' => factory(Fleet::class)->create(),
            'image' => $imageFilename,
        ]);

        $this->json('DELETE', route('api.spacecrafts.destroy', $spacecraft))->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /** @test */
    public function unauthorisedToDeleteSpacecraftIfTheUserIsNotLoggedIn()
    {
        //no actingAs
        Storage::fake();
        $fleetGeneral = User::first();
        $imageFilename = $this->faker->md5 . '.png';
        Storage::put($imageFilename, $this->faker->md5);//mocking the file. But not image file. We are just testing the logic
        $spacecraft = factory(Spacecraft::class)->create([
            'fleet_id' => $fleetGeneral->fleet_id,
            'image' => $imageFilename,
        ]);

        $this->json('DELETE', route('api.spacecrafts.destroy', $spacecraft))->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function fleetGeneralCanUpdateSpacecraft()
    {
        Storage::fake();
        $fleetGeneral = User::first();
        $imageFilename = $this->faker->md5 . '.png';
        Storage::put($imageFilename, $this->faker->md5);//mocking the file. But not image file. We are just testing the logic
        $spacecraft = factory(Spacecraft::class)->create([
            'fleet_id' => $fleetGeneral->fleet_id,
            'image' => $imageFilename,
        ]);
        Passport::actingAs($fleetGeneral);
        $body = $this->requestBody();
        $body['armaments'] = [
            [
                'title' => $this->faker->name,
                'qty' => $this->faker->randomNumber(2),
            ]
        ];

        $this->json('PUT', route('api.spacecrafts.update', $spacecraft), $body)->assertSuccessful();

        $spacecraft->refresh();
        $this->assertEquals($body['name'], $spacecraft->name);
        $this->assertEquals($body['class'], $spacecraft->class);
        $this->assertEquals($body['crew'], $spacecraft->crew);
        $this->assertEquals($body['value'], $spacecraft->value);
        $this->assertFalse(Storage::exists($imageFilename));
        Storage::assertExists($spacecraft->image);
        $this->assertEquals(1, $spacecraft->armaments()->count());
    }

    /** @test */
    public function unauthorisedToUpdateSpacecraftIfUserIsNotLoggedIn()
    {
        //no acting as
        Storage::fake();
        $fleetGeneral = User::first();
        $spacecraft = factory(Spacecraft::class)->create([
            'fleet_id' => $fleetGeneral->fleet_id,
        ]);
        $body = $this->requestBody();
        $this->json('PUT', route('api.spacecrafts.update', $spacecraft), $body)->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function unauthorisedToUpdateSpacecraftIfUserDoesNotHaveSameFleetIdAsSpacecraft()
    {
        //no acting as
        Storage::fake();
        $spacecraft = factory(Spacecraft::class)->create([
            'fleet_id' => factory(Fleet::class)->create()
        ]);
        $body = $this->requestBody();
        $this->json('PUT', route('api.spacecrafts.update', $spacecraft), $body)->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function fleetGeneralCanDeleteSpacecraft()
    {
        Storage::fake();
        $fleetGeneral = User::first();
        Passport::actingAs($fleetGeneral);
        $imageFilename = $this->faker->md5 . '.png';
        Storage::put($imageFilename, $this->faker->md5);//mocking the file. But not image file. We are just testing the logic
        $spacecraft = factory(Spacecraft::class)->create([
            'fleet_id' => $fleetGeneral->fleet_id,
            'image' => $imageFilename,
        ]);

        $this->json('DELETE', route('api.spacecrafts.destroy', $spacecraft))->assertSuccessful();

        $this->assertDatabaseMissing('spacecrafts', [
           'id' => $spacecraft->id,
        ]);
        $this->assertFalse(Storage::exists($imageFilename));
    }

    private function requestBody()
    {
        return [
            'name' => $this->faker->unique()->name,
            'class' => $this->faker->randomElement(Spacecraft::CLASSES),
            'crew' => $this->faker->randomNumber(4),
            'image' => UploadedFile::fake()->create($this->faker->md5 . '.png'),//did not use image because it needs some extension on some environment
            'value' => $this->faker->randomFloat(2),
            'status' => $this->faker->randomElement([ Spacecraft::STATUS_OPERATIONAL, Spacecraft::STATUS_DAMAGED ]),
            'armaments' => [
                [
                    'title' => $this->faker->name,
                    'qty' => $this->faker->randomNumber(2),
                ],
                [
                    'title' => $this->faker->name,
                    'qty' => $this->faker->randomNumber(2),
                ]
            ]
        ];
    }
}
