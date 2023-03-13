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
     * Test logged user can see this page
     * @return void
     */
    public function test_it_user_can_see_import_page()
    {
        $this->loginAsUser();

        $response = $this->get('/import');

        $response->assertStatus(200);
    }

    /**
     * Test upload csv file in importform 
     * @return [type] [description]
     */
    public function test_it_user_can_upload_csv_file_in_import_page()
    {
        $this->loginAsUser();

        Storage::fake('public');

        $file = UploadedFile::fake()->create('placescsv.csv', $sizeInKilobytes = 1000, 'text/csv');

        $response = $this->post('/import', [
            'file' => $file
        ]);

        Storage::disk('public')->assertExists('csv/placescsv.csv');
    } 

    /**
     * Test can user click import button
     * @return [type] [description]
     */
    public function test_it_user_can_import_uploaded_csv_file()
    {
        $this->loginAsUser();

        $import = Import::factory()->create();

        $response = $this->get('/import/store_palaces/'.$import->id)->assertStatus(500)->assertRedirect('/'); //302
    } 
}
