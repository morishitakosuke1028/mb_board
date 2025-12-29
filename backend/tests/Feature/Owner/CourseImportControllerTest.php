<?php

namespace Tests\Feature\Owner;

use App\Models\Owner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CourseImportControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint = '/api/owner/import';
    private Owner $owner;

    protected function setUp(): void
    {
        parent::setUp();
        Owner::factory()->create(['id' => 1]);
        Owner::factory()->create(['id' => 2]);
        $this->owner = Owner::factory()->create(['id' => 3]);
    }

    #[Test]
    public function it_imports_csv_successfully()
    {
        $csv = <<<CSV
owner_id,course_title,content,instructor,instructor_title,date_time,participation_fee,additional_fee,capacity,venue,venue_zip,venue_address,tel,email,map,status
1,講座A,内容A,田中,,2025-01-01 10:00:00,1000,,20,会場A,1000001,住所A,000-0000-0000,aaa@example.com,,1
2,講座B,内容B,佐藤,,2025-02-01 09:00:00,2000,,30,会場B,2000002,住所B,111-1111-1111,bbb@example.com,,1
CSV;

        // 実ストレージに正しい形式で作成
        $path = storage_path('app/private/imports/test.csv');
        @mkdir(dirname($path), 0777, true);
        file_put_contents($path, $csv);

        $uploaded = new UploadedFile(
            $path,
            'test.csv',
            'text/csv',
            null,
            true
        );

        $response = $this
            ->actingAs($this->owner, 'owner')
            ->postJson($this->endpoint, [
                'csv_file' => $uploaded,
            ]);

        // dd($response->json());

        $response->assertStatus(200)
                 ->assertJson(['count' => 2]);

        $this->assertDatabaseCount('courses', 2);
    }

    #[Test]
    public function it_fails_when_csv_contains_invalid_rows()
    {
        $csv = <<<CSV
owner_id,course_title,content,instructor,instructor_title,date_time,participation_fee,additional_fee,capacity,venue,venue_zip,venue_address,tel,email,map,status
1,正常講座,,田中,,2025-01-01 10:00:00,,,10,会場A,1000001,住所A,,,1
,エラー講座,,佐藤,,2025-02-01 09:00:00,,,20,会場B,2000002,住所B,,invalid-email,,2
CSV;

        // 実ストレージに正しい形式で作成
        $path = storage_path('app/private/imports/test.csv');
        @mkdir(dirname($path), 0777, true);
        file_put_contents($path, $csv);

        $uploaded = new UploadedFile(
            $path,
            'test.csv',
            'text/csv',
            null,
            true
        );

        $response = $this
            ->actingAs($this->owner, 'owner')
            ->postJson($this->endpoint, [
                'csv_file' => $uploaded,
            ]);

        $response->assertStatus(422);
        $this->assertStringContainsString('3行目', $response->json('message'));
        $this->assertDatabaseCount('courses', 0);
    }
}
