<?php

namespace Tests\Unit;

use App\Http\Controllers\HolidayPlanController;
use App\Http\Requests\HolidayPlanRequest;
use App\Models\HolidayPlan;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class HolidayPlanControllerTest extends TestCase
{

    public function test_it_can_create_a_holiday_plan(): void
    {

        $data = [
            'title' => 'Vacation in Troia Island',
            'description' => 'A relaxing holiday to see old friends.',
            'date' => '2024-12-25',
            'location' => 'Setubal',
            'participants' => ['Caio Dias'],
        ];

        $createdHolidayPlan = (object) array_merge($data, [
            'id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $requestMock = Mockery::mock(HolidayPlanRequest::class);

        $requestMock->shouldReceive('validated')->andReturn($data);

        $holidayPlanMock = Mockery::mock(HolidayPlan::class);

        $holidayPlanMock->shouldReceive('create')->with($data)->andReturn($createdHolidayPlan);

        DB::shouldReceive('beginTransaction')->once();

        DB::shouldReceive('commit')->once();

        $controller = new HolidayPlanController($holidayPlanMock);

        $response = $controller->store($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);

        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_it_can_list_holiday_plans(): void
    {
        $holidayPlansData = collect([
            (object)[
                'id' => 1,
                'title' => 'Vacation in Troia Island',
                'description' => 'A relaxing holiday to see old friends.',
                'date' => '2024-12-25',
                'location' => 'Setubal',
                'participants' => ['Caio Dias'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);

        $holidayPlanMock = Mockery::mock(HolidayPlan::class);

        $holidayPlanMock->shouldReceive('all')->andReturn($holidayPlansData);

        $controller = new HolidayPlanController($holidayPlanMock);

        $response = $controller->index();

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJson($response->getContent());
    }

    public function test_it_can_show_a_holiday_plan(): void
    {
        $holidayPlanData = (object)[
            'id' => 1,
            'title' => 'Vacation in Troia Island',
            'description' => 'A relaxing holiday to see old friends.',
            'date' => '2024-12-25',
            'location' => 'Setubal',
            'participants' => ['Caio Dias'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $holidayPlanMock = Mockery::mock(HolidayPlan::class);

        $holidayPlanMock->shouldReceive('findOrFail')->with(1)->andReturn($holidayPlanData);

        $controller = new HolidayPlanController($holidayPlanMock);

        $response = $controller->show(1);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals($holidayPlanData->id, $responseData['id']);
    }

    public function test_it_can_update_a_holiday_plan(): void
    {
        $updateData = [
            'title' => 'Vacation In Lisbon',
            'description' => 'This is not a vacation, it’s a work trip to meet the Buzzvel team.',
            'date' => '2024-12-30',
            'location' => 'Lisbon',
            'participants' => ['Caio Dias'],
        ];

        $holidayPlanMock = Mockery::mock(HolidayPlan::class)->makePartial();

        $holidayPlanMock->shouldIgnoreMissing();

        $holidayPlanMock->shouldReceive('findOrFail')->with(1)->andReturn($holidayPlanMock);

        $holidayPlanMock->shouldReceive('update')->with($updateData)->andReturn(true);

        $holidayPlanMock->id = 1;
        $holidayPlanMock->title = $updateData['title'];
        $holidayPlanMock->description = $updateData['description'];
        $holidayPlanMock->date = $updateData['date'];
        $holidayPlanMock->location = $updateData['location'];
        $holidayPlanMock->participants = $updateData['participants'];
        $holidayPlanMock->created_at = Carbon::now();
        $holidayPlanMock->updated_at = Carbon::now();

        $requestMock = Mockery::mock(HolidayPlanRequest::class);

        $requestMock->shouldReceive('validated')->andReturn($updateData);

        DB::shouldReceive('beginTransaction')->once();

        DB::shouldReceive('commit')->once();

        $controller = new HolidayPlanController($holidayPlanMock);
        $response = $controller->update($requestMock, 1);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals('Holiday Plan successfully updated.', $responseData['message']);

        $this->assertEquals($updateData['title'], $responseData['data']['title']);
    }

    public function test_it_can_delete_a_holiday_plan(): void
    {
        $holidayPlanMock = Mockery::mock(HolidayPlan::class)->makePartial();

        $holidayPlanMock->shouldIgnoreMissing();

        $holidayPlanMock->shouldReceive('findOrFail')->with(1)->andReturn($holidayPlanMock);

        $holidayPlanMock->shouldReceive('delete')->andReturn(true);

        $controller = new HolidayPlanController($holidayPlanMock);

        $response = $controller->destroy(1);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertJson($response->getContent());

        $responseData = json_decode($response->getContent(), true);

        $this->assertEquals('Holiday Plan successfully deleted.', $responseData['message']);
    }

    public function test_it_can_generate_a_pdf_for_a_holiday_plan(): void
    {
        $holidayPlanData = (object)[
            'id' => 1,
            'title' => 'Vacation In Lisbon',
            'description' => 'This is not a vacation, it’s a work trip to meet the Buzzvel team.',
            'date' => '2024-12-30',
            'location' => 'Lisbon',
            'participants' => ['Caio Dias'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Mock do modelo HolidayPlan
        $holidayPlanMock = Mockery::mock(HolidayPlan::class)->makePartial();
        $holidayPlanMock->shouldReceive('findOrFail')->with(1)->andReturn($holidayPlanData);

        // Mock do PDF facade
        $pdfMock = Mockery::mock('alias:' . Pdf::class);
        $pdfMock->shouldReceive('loadView')->andReturnSelf();
        $pdfMock->shouldReceive('download')->andReturn(
            response()->make('PDF Content', 200, ['Content-Type' => 'application/pdf'])
        );

        // Controlador com o mock de HolidayPlan
        $controller = new HolidayPlanController($holidayPlanMock);
        $response = $controller->generatePdf(1);

        // Verificações
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));

        // Verificar se o conteúdo está presente e é binário (comum em PDFs)
        $this->assertNotEmpty($response->getContent());
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }



    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
