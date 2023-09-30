<?php

namespace App\Modules\Media\Tests;

use App\Models\User;
use App\Modules\Media\Models\Media;
use App\Tests\Support\MediaTestTrait;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RonasIT\Support\Traits\FilesUploadTrait;

class MediaTest extends ModuleTestCase
{
    use FilesUploadTrait;
    use MediaTestTrait;

    protected $admin;
    protected $user;
    protected File $file;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::find(1);
        $this->user = User::find(2);
        $this->file = UploadedFile::fake()->image('file.png', 600, 600);

        $this->mockGenerateFilename();
    }

    public function testCreate(): void
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

    public function testCreatePublic(): void
    {
        $response = $this->actingAs($this->user)->json('post', '/media', [
            'file' => $this->file,
            'is_public' => true,
        ]);

        $responseData = $response->json();

        $this->assertDatabaseHas('media', [
            'id' => $responseData['id'],
            'name' => 'file.png',
            'owner_id' => $this->user->id,
            'is_public' => true
        ]);

        $response->assertOk();
    }

    public function testCreateCheckUrls(): void
    {
        $this->actingAs($this->admin)->json('post', '/media', ['file' => $this->file]);

        $this->assertEquals(1, Media::where('link', 'like', '/%')->count());
    }

    public function testCreateCheckResponse(): void
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

    public function testCreateNoAuth(): void
    {
        $response = $this->json('post', '/media', ['file' => $this->file]);

        $response->assertUnauthorized();
    }

    public function testBulkCreate(): void
    {
        $response = $this->actingAs($this->admin)->json('post', '/media/bulk', [
            'media' => [
                [
                    'file' => $this->file,
                    'meta' => 'test1'
                ],
                [
                    'file' => $this->file,
                    'meta' => 'test2'
                ]
            ]
        ]);

        $response->assertOk();

        $responseData = $response->json();

        $this->assertDatabaseHas('media', [
            'id' => $responseData[0]['id'],
            'name' => 'file.png',
            'owner_id' => $this->admin->id,
            'meta' => 'test1',
            'is_public' => false
        ]);

        $this->assertDatabaseHas('media', [
            'id' => $responseData[1]['id'],
            'name' => 'file.png',
            'owner_id' => $this->admin->id,
            'meta' => 'test2',
            'is_public' => false
        ]);
    }

    public function testDelete(): void
    {
        $response = $this->actingAs($this->admin)->json('delete', '/media/1');

        $response->assertNoContent();

        $this->assertSoftDeleted('media', [
            'id' => 1
        ]);
    }

    public function testDeleteNotExists(): void
    {
        $response = $this->actingAs($this->admin)->json('delete', '/media/0');

        $response->assertNotFound();
    }

    public function testDeleteNoPermission(): void
    {
        $response = $this->actingAs($this->user)->json('delete', '/media/1');

        $response->assertForbidden();

        $this->assertDatabaseHas('media', [
            'id' => 1
        ]);
    }

    public function testDeleteNoAuth(): void
    {
        $response = $this->json('delete', '/media/1');

        $response->assertUnauthorized();

        $this->assertDatabaseHas('media', [
            'id' => 1
        ]);
    }

    public function getSearchFilters(): array
    {
        return [
            [
                'filter' => ['query' => 'main'],
                'result' => 'get_medias_by_name.json'
            ],
            [
                'filter' => ['query' => 'product'],
                'result' => 'get_medias_by_query.json'
            ],
            [
                'filter' => [
                    'query' => 'photo',
                    'order_by' => 'name',
                    'desc' => false,
                    'per_page' => 2
                ],
                'result' => 'get_medias_complex.json'
            ]
        ];
    }

    public function getUserSearchFilters(): array
    {
        return [
            [
                'filter' => ['query' => 'main'],
                'result' => 'get_by_name.json'
            ],
            [
                'filter' => ['query' => 'product'],
                'result' => 'get_by_query.json'
            ],
            [
                'filter' => [
                    'query' => 'photo',
                    'order_by' => 'name',
                    'desc' => false,
                    'per_page' => 2
                ],
                'result' => 'get_complex.json'
            ]
        ];
    }

    /**
     * @dataProvider  getSearchFilters
     *
     * @param array $filter
     * @param string $fixture
     */
    public function testSearch(array $filter, string $fixture): void
    {
        $response = $this->actingAs($this->admin)->json('get', '/media', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    /**
     * @dataProvider  getUserSearchFilters
     *
     * @param array $filter
     * @param string $fixture
     */
    public function testSearchByUser(array $filter, string $fixture): void
    {
        $response = $this->actingAs($this->user)->json('get', '/media', $filter);

        $response->assertOk();

        $this->assertEqualsFixture($fixture, $response->json());
    }

    public function getBadFiles(): array
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
     * @param array $filter
     */
    public function testUploadingBadFiles(array $filter): void
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

    public function getGoodFiles(): array
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
     * @param array $filter
     */
    public function testUploadingGoodFiles(array $filter): void
    {
        $this->file = UploadedFile::fake()->image($filter['fileName'], 600, 600);

        $response = $this->actingAs($this->user)->json('post', '/media', ['file' => $this->file]);

        $responseData = $response->json();

        $this->assertDatabaseHas('media', [
            'id' => $responseData['id'],
        ]);
    }
}
