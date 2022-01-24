<?php

namespace Tests\Feature;

use App\Models\Import;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_user_can_see_import_page()
    {
        $response = $this->loginAsUser();

        $response = $this->get('/import');

        $response->assertStatus(200);
    }

    public function test_it_user_can_upload_csv_file_in_import_page()
    {
        $response = $this->loginAsUser();

        Storage::fake('public');

        $file = UploadedFile::fake()->create('placescsv.csv', $sizeInKilobytes = 1000, 'text/csv');

        $response = $this->post('/import', [
            'file' => $file
        ]);

        Storage::disk('public')->assertExists('csv/placescsv.csv');
    }
}
