<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CourseImportControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint = '/api/admin/import';

    #[Test]
    public function it_imports_csv_successfully()
    {
        Storage::fake('private');

        // 正常CSV作成
        $csv = <<<CSV
owner_id,course_title,content,instructor,instructor_title,date_time,participation_fee,additional_fee,capacity,venue,venue_zip,venue_address,tel,email,map,status
1,講座A,内容A,田中,,2025-01-01 10:00,1000,,20,会場A,1000001,住所A,000-0000-0000,aaa@example.com,,1
2,講座B,内容B,佐藤,,2025-02-01 09:00,2000,,30,会場B,2000002,住所B,111-1111-1111,bbb@example.com,,1
CSV;

        $uploaded = UploadedFile::fake()->createWithContent('test.csv', $csv);

        $response = $this->postJson($this->endpoint, [
            'csv_file' => $uploaded
        ]);

        $response->assertStatus(200)
                 ->assertJson(['count' => 2]);

        // DBに2件登録されていること
        $this->assertDatabaseCount('courses', 2);
    }

    #[Test]
    public function it_fails_when_csv_contains_invalid_rows()
    {
        Storage::fake('private');

        // 2行目に owner_id が空、status が不正
        $csv = <<<CSV
owner_id,course_title,content,instructor,instructor_title,date_time,participation_fee,additional_fee,capacity,venue,venue_zip,venue_address,tel,email,map,status
1,正常講座,,田中,,2025-01-01 10:00,,,10,会場A,1000001,住所A,,,1
,エラー講座,,佐藤,,2025-02-01 09:00,,,20,会場B,2000002,住所B,,invalid-email,,2
CSV;

        $uploaded = UploadedFile::fake()->createWithContent('test_invalid.csv', $csv);

        $response = $this->postJson($this->endpoint, [
            'csv_file' => $uploaded
        ]);

        // バリデーションエラーは 422
        $response->assertStatus(422);

        // メッセージに2行目を含む
        $this->assertStringContainsString('2 行目', $response->json('message'));

        // DBに1件も入らないこと（全部ロールバック）
        $this->assertDatabaseCount('courses', 0);
    }
}

