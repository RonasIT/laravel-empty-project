<?php

namespace App\Tests;

use App\Models\User;
use App\Tests\Support\MediaTestTrait;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RonasIT\Support\Traits\FilesUploadTrait;

class MediaTest extends TestCase
{
    use FilesUploadTrait;
    use MediaTestTrait;

    protected $admin;
    protected $user;
    protected $file;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::find(1);
        $this->user = User::find(2);
        $this->file = UploadedFile::fake()->image('file.png', 600, 600);

        $this->mockGenerateFilename();
    }

    public function testCreate()
    {
        $response = $this->actingAs($this->admin)->json('post', '/media', ['file' => $this->file]);

        $response->assertOk();

        $responseData = $response->json();

        $this->assertDatabaseHas('media', [
            'id' => $responseData['id'],
            'name' => 'file.png',
            'owner_id' => $this->admin->id,
            'is_public' => false,
            'link' => '/storage/file.png'
        ]);
    }

    public function testCreatePublic()
    {
        $response = $this->actingAs($this->user)->json('post', '/media', [
            'file' => $this->file,
            'is_public' => true
        ]);

        $responseData = $response->json();

        $this->assertDatabaseHas('media', [
            'id' => $responseData['id'],
            'name' => 'file.png',
            'owner_id' => $this->user->id,
            'is_public' => true,
            'link' => '/storage/file.png'
        ]);

        $response->assertOk();
    }

    public function testCreateCheckResponse()
    {
        $response = $this->actingAs($this->admin)->json('post', '/media', ['file' => $this->file]);

        $responseData = $response->json();

        $this->assertDatabaseHas('media', [
            'id' => $responseData['id'],
            'link' => $responseData['link']
        ]);

        Storage::disk('local')->assertExists($this->getFilePathFromUrl($responseData['link']));

        $this->clearUploadedFilesFolder();
    }

    public function testCreateNoAuth()
    {
        $response = $this->json('post', '/media', ['file' => $this->file]);

        $response->assertUnauthorized();
    }

    public function testDelete()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/media/1');

        $response->assertNoContent();

        $this->assertSoftDeleted('media', [
            'id' => 1
        ]);
    }

    public function testDeleteNotExists()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/media/0');

        $response->assertNotFound();

        $this->assertDatabaseMissing('media', [
            'id' => 0
        ]);
    }

    public function testDeleteNoPermission()
    {
        $response = $this->actingAs($this->user)->json('delete', '/media/1');

        $response->assertForbidden();

        $this->assertDatabaseHas('media', [
            'id' => 1
        ]);
    }

    public function testDeleteNoAuth()
    {
        $response = $this->json('delete', '/media/1');

        $response->assertUnauthorized();

        $this->assertDatabaseHas('media', [
            'id' => 1
        ]);
    }

    public function getSearchFilters()
    {
        return [
            [
                'filter' => ['all' => true],
                'result' => 'get_by_all.json'
            ],
        ];
    }

    public function getAdminSearchFilters()
    {
        return [
            [
                'filter' => ['query' => 'product'],
                'result' => 'get_by_query_as_admin.json'
            ],
            [
                'filter' => [
                    'query' => 'photo',
                    'order_by' => 'name',
                    'desc' => false,
                    'per_page' => 3
                ],
                'result' => 'get_complex_as_admin.json'
            ]
        ];
    }

    public function getUserSearchFilters()
    {
        return [
            [
                'filter' => ['query' => 'product'],
                'result' => 'get_by_query_as_user.json'
            ],
            [
                'filter' => [
                    'query' => 'photo',
                    'order_by' => 'name',
                    'desc' => false,
                    'per_page' => 3
                ],
                'result' => 'get_complex_as_user.json'
            ]
        ];
    }

    /**
     * @dataProvider  getSearchFilters
     *
     * @param  array $filter
     * @param  string $fixture
     */
    public function testSearch($filter, $fixture)
    {
        $response = $this->json('get', '/media', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    /**
     * @dataProvider  getAdminSearchFilters
     *
     * @param  array $filter
     * @param  string $fixture
     */
    public function testSearchByAdmin($filter, $fixture)
    {
        $response = $this->actingAs($this->admin)->json('get', '/media', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    /**
     * @dataProvider  getUserSearchFilters
     *
     * @param  array $filter
     * @param  string $fixture
     */
    public function testSearchByUser($filter, $fixture)
    {
        $response = $this->actingAs($this->user)->json('get', '/media', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    public function getBadFiles()
    {
        return [
            [
                'filter' => ['fileName' => 'notAVirus.exe']
            ],
            [
                'filter' => ['fileName' => 'notAVirus.psd']
            ]
        ];
    }

    /**
     * @dataProvider  getBadFiles
     *
     * @param  array $filter
     */
    public function testUploadingBadFiles($filter)
    {
        $this->file = UploadedFile::fake()->create($filter['fileName'], 1024);

        $response = $this->actingAs($this->user)->json('post', '/media', ['file' => $this->file]);

        $response->assertUnprocessable();

        $response->assertJson([
            'errors' => [
                'file' => ['The file must be a file of type: jpg, jpeg, bmp, png.']
            ]
        ]);
    }

    public function getGoodFiles()
    {
        return [
            [
                'filter' => ['fileName' => 'image.jpg']
            ],
            [
                'filter' => ['fileName' => 'image.png']
            ],
            [
                'filter' => ['fileName' => 'image.bmp']
            ]
        ];
    }

    /**
     * @dataProvider  getGoodFiles
     *
     * @param  array $filter
     */
    public function testUploadingGoodFiles($filter)
    {
        $this->file = UploadedFile::fake()->image($filter['fileName'], 600, 600);

        $response = $this->actingAs($this->user)->json('post', '/media', ['file' => $this->file]);

        $responseData = $response->json();

        $this->assertDatabaseHas('media', [
            'id' => $responseData['id'],
        ]);
    }
}
