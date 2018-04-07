<?php

namespace App\Tests;

use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RonasIT\Support\Traits\FilesTestTrait;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class MediaTest extends TestCase
{
    use FilesTestTrait;

    protected $admin;
    protected $user;
    protected $file;

    public function setUp() {
        parent::setUp();

        $this->admin = User::find(1);
        $this->user = User::find(2);
        $this->file = UploadedFile::fake()->image('file.png', 600, 600);
    }

    public function testCreateMultipart()
    {
        $response = $this->actingAs($this->admin)->json('post', '/media', ['file' => $this->file]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCreate() {
        $response = $this->actingAs($this->admin)->json('post', '/media/stub.jpeg?name=Vasya', ['file' => $this->file]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCreateCheckUrls() {
        $this->actingAs($this->admin)->json('post', '/media/stub.jpeg?name=Vasya', ['file' => $this->file]);

        $this->assertEquals(1, Media::where('link', 'like', '/%')->count());
    }

    public function testCreateCheckResponse() {
        $response = $this->actingAs($this->admin)->json('post', '/media/stub.jpeg?name=Vasya', ['file' => $this->file]);

        $responseData = $response->json();

        $this->assertDatabaseHas('media', [
            'id' => $responseData['id'],
            'link' => $responseData['link']
        ]);

        Storage::disk('local')->assertExists('public/' . $this->getFilePathFromUrl($responseData['link']));

        $this->clearFolder();
    }

    public function testCreateNoAuth() {
        $response = $this->json('post', '/media/stub.jpeg?name=Vasya', ['file' => $this->file]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testCreateNoPermission() {
        $response = $this->actingAs($this->user)->json('post', '/media/stub.jpeg?name=Vasya', ['file' => $this->file]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testUpdate() {
        $response = $this->actingAs($this->admin)->json('put', '/media/1', ['name' => 'New Name']);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testUpdateNotExists() {
        $data = $this->getJsonFixture('media.json');

        $response = $this->actingAs($this->admin)->json('put', '/media/0', $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateNoAuth() {
        $data = $this->getJsonFixture('media.json');

        $response = $this->json('put', '/media/1', $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testDelete() {
        $response = $this->actingAs($this->admin)->json('delete', '/media/1');

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteNotExists() {
        $response = $this->actingAs($this->admin)->json('delete', '/media/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteNoAuth() {
        $response = $this->json('delete', '/media/1');

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testGet() {
        $response = $this->actingAs($this->admin)->json('get', '/media/1');

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testGetCheckResponse() {
        $response = $this->actingAs($this->admin)->json('get', '/media/1');

        $filteredResponse = array_except($response->json(), ['created_at', 'updated_at', 'deleted_at']);

        $this->assertEqualsFixture('get_media.json', $filteredResponse);
    }

    public function testGetNotExists() {
        $response = $this->actingAs($this->admin)->json('get', '/media/0');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function getSearchFilters() {
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

    /**
     * @dataProvider  getSearchFilters
     *
     * @param  array $filter
     * @param  string $fixture
     */
    public function testSearch($filter, $fixture) {
        $response = $this->actingAs($this->admin)->json('get', '/media', $filter);

        $response->assertStatus(Response::HTTP_OK);

        $this->assertEqualsFixture($fixture, $response->json());
    }
}