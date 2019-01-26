<?php

namespace App\Tests;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RonasIT\Support\Traits\FilesUploadTrait;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class MediaTest extends TestCase
{
    use FilesUploadTrait;

    protected $admin;
    protected $user;
    protected $file;

    public function setUp()
    {
        parent::setUp();

        $this->admin = User::find(1);
        $this->user = User::find(2);
        $this->file = UploadedFile::fake()->image('file.png', 600, 600);
    }

    public function testCreate()
    {
        $response = $this->actingAs($this->admin)->json('post', '/media', ['file' => $this->file]);

        $response->assertStatus(Response::HTTP_OK);

        $responseData = $response->json();

        $this->assertDatabaseHas('media', [
            'id' => $responseData['id'],
            'is_public' => false
        ]);
    }


    public function testCreatePublic()
    {
        $response = $this->actingAs($this->admin)->json(
            'post',
            '/media',
            ['file' => $this->file, 'is_public' => true]
        );

        $responseData = $response->json();

        $this->assertDatabaseHas('media', [
            'id' => $responseData['id'],
            'is_public' => true
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCreateCheckUrls()
    {
        $this->actingAs($this->admin)->json('post', '/media', ['file' => $this->file]);

        $this->assertEquals(1, Media::where('link', 'like', '/%')->count());
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

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testDelete()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/media/1');

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteNotExists()
    {
        $response = $this->actingAs($this->admin)->json('delete', '/media/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteNoPermission()
    {
        $response = $this->actingAs($this->user)->json('delete', '/media/1');

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testDeleteNoAuth()
    {
        $response = $this->json('delete', '/media/1');

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function getSearchFilters()
    {
        return [
            [
                'filter' => ['query' => 'Deleted photo'],
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

    public function getUserSearchFilters()
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
     * @param  array $filter
     * @param  string $fixture
     */
    public function testSearch($filter, $fixture)
    {
        $response = $this->actingAs($this->admin)->json('get', '/media', $filter);

        $response->assertStatus(Response::HTTP_OK);

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

        $response->assertStatus(Response::HTTP_OK);

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
     * @param  string $fixture
     */
    public function testUploadingBadFiles($filter){

        $this->file = UploadedFile::fake()->create($filter['fileName'], 1024);

        $response = $this->actingAs($this->user)->json('post', '/media', ['file' => $this->file]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJson([
            'errors' => [
                'file' => ['The file must be a file of type: jpeg, bmp, png.']
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
     * @param  string $fixture
     */
    public function testUploadingGoodFiles($filter){

        $this->file = UploadedFile::fake()->image($filter['fileName'], 600, 600);

        $response = $this->actingAs($this->user)->json('post', '/media', ['file' => $this->file]);

        $responseData = $response->json();

        $this->assertDatabaseHas('media', [
            'id' => $responseData['id'],
        ]);
    }
}