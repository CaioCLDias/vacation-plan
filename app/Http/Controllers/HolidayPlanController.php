<?php

namespace App\Http\Controllers;

use App\Http\Requests\HolidayPlanRequest;
use App\Http\Resources\HolidayPlanResource;
use App\Models\HolidayPlan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class HolidayPlanController extends Controller
{
    protected HolidayPlan $holidayPlan;

    public function __construct(HolidayPlan $holidayPlan) {
        $this->holidayPlan = $holidayPlan;
    }

    /**
     * Get all holiday plans.
     *
     * Retrieves a list of all holiday plans available.
     *
     * @group Holiday Plans
     * @authenticated
     * @response 200 [
     * {
     * "title": "Summer Break",
     * "description": A relaxing holiday to see old friends.
     * "date": "2024-07-20",
     * "location": "Algarve",
     * "participants": ["João", "Maria"]
     * }
     * ]
     */
    public function index(): JsonResponse
    {
        try{

            $holidayPlans = $this->holidayPlan->all();

            return $holidayPlans->isEmpty()
                ? response()->json(['message' => 'No record'], 204)
                : response()->json(HolidayPlanResource::collection($holidayPlans), 200);

        }catch (\Exception $e){
            return response()->json([
                'message' => 'An error occurred while retrieving the Holiday Plans',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Create a new holiday plan.
     *
     * Adds a new holiday plan to the system with the specified details.
     *
     * @group Holiday Plans
     * @authenticated
     * @bodyParam title string required The title of the holiday plan. Example: Summer Break
     * @bodyParam description string required The description of the holiday plan. Example: A relaxing holiday
     * @bodyParam date required The date of the holiday. Example: 2024-07-20
     * @bodyParam location string required The location of the holiday. Example: Algarve
     * @bodyParam participants array List of participants. Example: ["João", "Maria"]
     * @response 201 {
     *   "id": 2,
     *   "title": "Summer Break",
     *   "description": A relaxing holiday.
     *   "date": "2024-07-20",
     *   "location": "Algarve",
     *   "participants": ["João", "Maria"]
     * }
     */
    public function store(HolidayPlanRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try{

            $holidayPlan = $this->holidayPlan->create($request->validated());

            DB::commit();

            return response()->json([
                'message' => 'Holiday Plan successfully created.',
                'data' => new HolidayPlanResource($holidayPlan)
            ], 201);

        }catch (\Exception $e){
            DB::rollback();
            Log::error('Erro no store do HolidayPlanController: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create a Holiday Plan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Show holiday plan details.
     *
     * Displays details of a specific holiday plan by its ID.
     *
     * @group Holiday Plans
     * @authenticated
     * @urlParam id integer required The ID of the holiday plan. Example: 1
     * @response 200 {
     *   "title": "Summer Break",
     *   "description": A relaxing holiday to see old friends.
     *   "date": "2024-07-20",
     *   "location": "Algarve",
     *   "participants": ["João", "Maria"]
     *}
     */
    public function show($id): JsonResponse
    {
        try{

            $holidayPlan = $this->holidayPlan->findOrFail($id);

            $holidayPlanResource = new HolidayPlanResource($holidayPlan);

            return response()->json($holidayPlanResource, 200);

        }catch (ModelNotFoundException $e){
            return response()->json([
                'message' => 'Holiday Plan not found.',
            ], 404);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'An error occurred while retrieving the Holiday Plan.',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Update holiday plan.
     *
     * Modifies the details of an existing holiday plan.
     *
     * @group Holiday Plans
     * @authenticated
     * @urlParam id integer required The ID of the holiday plan to update. Example: 1
     * @bodyParam title string The new title of the holiday plan. Example: Winter Escape
     * @bodyParam description string required The description of the holiday plan. Example: A relaxing holiday
     * @bodyParam date The new date of the holiday. Example: 2024-12-15
     * @bodyParam location string The new location of the holiday. Example: Serra da Estrela
     * @bodyParam participants array Updated list of participants. Example: ["David", "Mafalda"]
     * @response 200 {
     *   "id": 1,
     *   "name": "Winter Escape",
     *   "date": "2024-12-15",
     *   "location": "Serra da Estrela",
     *   "participants": ["David", "Mafalda"]
     * }
     */
    public function update(HolidayPlanRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();

        try{

            $holidayPlan = $this->holidayPlan->findOrFail($id);

            $holidayPlan->update($request->validated());

            DB::commit();

            return response()->json([
                'message' => 'Holiday Plan successfully updated.',
                'data' => new HolidayPlanResource($holidayPlan)
            ],200);


        }catch (ModelNotFoundException $e){
            DB::rollback();

            return response()->json([
                'message' => 'Holiday Plan not found.',
            ],404);
        }catch (\Exception $e){
            DB::rollback();

            return response()->json([
                'message' => 'An error occurred while updating the Holiday Plan.',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Delete holiday plan.
     *
     * Removes a specific holiday plan by its ID.
     *
     * @group Holiday Plans
     * @authenticated
     * @urlParam id integer required The ID of the holiday plan to delete. Example: 1
     * @response 204 {
     *   "message": "Holiday plan deleted successfully."
     * }
     */
    public function destroy($id): JsonResponse
    {
        try{

            $holidayPlan = $this->holidayPlan->findOrFail($id);

            $holidayPlan->delete();

            return response()->json([
                'message' => 'Holiday Plan successfully deleted.',
            ],200);

        }catch (ModelNotFoundException $e){
            return response()->json([
                'message' => 'Holiday Plan not found.',
            ],404);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'An error occurred while deleting the Holiday Plan.',
                'error' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Generate PDF for holiday plan.
     *
     * Generates a PDF file for a specific holiday plan, detailing the information.
     *
     * @group Holiday Plans
     * @authenticated
     * @urlParam id integer required The ID of the holiday plan to generate the PDF for. Example: 1
     * @response 200 application/pdf
     */
    public function generatePdf($id): Response|JsonResponse
    {
        try{

            $holidayPlan = $this->holidayPlan->findOrFail($id);

            $pdf = Pdf::loadView('pdf.holiday-plan', compact('holidayPlan'));

            return $pdf->download('holiday-plan-' . $holidayPlan->id . '.pdf');

        }catch (ModelNotFoundException $e){
            return response()->json([
                'message' => 'Holiday Plan not found.',
            ],404);
        }catch (\Exception $e){
            return response()->json([
                'message' => 'Failed to generate PDF.',
                'error' => $e->getMessage()
            ],500);
        }
    }

}
