<?php

namespace App\Http\Controllers;

use App\Http\Requests\HolidayPlanRequest;
use App\Http\Resources\HolidayPlanResource;
use App\Models\HolidayPlan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class HolidayPlanController extends Controller
{
    protected $holidayPlan;

    public function __construct(HolidayPlan $holidayPlan) {
        $this->holidayPlan = $holidayPlan;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
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
     * Store a newly created resource in storage.
     */
    public function store(HolidayPlanRequest $request)
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
     * Display the specified resource.
     */
    public function show($id)
    {
        try{

            $holidayPlan = $this->holidayPlan->findOrFail($id);

            return new HolidayPlanResource($holidayPlan);


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
     * Update the specified resource in storage.
     */
    public function update(HolidayPlanRequest $request, $id)
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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
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
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function generatePdf($id)
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
