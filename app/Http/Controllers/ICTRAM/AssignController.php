<?php
namespace App\Http\Controllers\ICTRAM;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\IctramJobType;
use App\Models\IctramEquipment;
use App\Models\IctramProblem;
use App\Models\IctramRequest;
use App\Models\Ictram;

class AssignController extends Controller
{
    public function index()
    {
        $ictrams = Ictram::with(['jobType', 'equipment', 'problem'])->get();
        $jobTypes = IctramJobType::all();
        $equipments = IctramEquipment::all();
        $problems = IctramProblem::all();
        $sortedIctrams = $ictrams->sortBy('jobType.jobType_name');
        
        return view('units.ictram.index', compact('jobTypes', 'equipments', 'problems', 'sortedIctrams'));
    }

    public function storeAndRelate(Request $request)
{
    // Check if the request has 'other' input
    if ($request->has('jobType_other')) {

        $jobType = IctramJobType::create([
            'title' => $request->input('title'),
            'author' => $request->input('author'),
            'isbn' => $request->input('isbn')
        ]);
    } else {
        $ictram_job_type_id = $request->input('ictram_job_type_id');
        
        $jobType = IctramJobType::findOrFail($ictram_job_type_id);
    }

    // Store the relationship
    $relationship = Relationship::create([
        'ictram_job_type_id' => $jobType->id,
        'ictram_equipment_id' => $equipmentId,
        'ictram_problem_id' => $problemId,
    ]);

    // Return success response or whatever you need
    return response()->json(['message' => 'Book stored and related successfully', 'book' => $book]);
}
    
    public function storeWithRelationShip(Request $request)
    {   
        
    //Job type Other
    if ($request->has('jobType_other')) {

        $jobType = IctramJobType::create([
            'jobType_name' => $request->input('jobType_other'),
        ]);
    } else {
        $ictram_job_type_id = $request->input('ictram_job_type_id');
        
        $jobType = IctramJobType::findOrFail($ictram_job_type_id);
    }
    //Equipment Other
    if ($request->has('jobType_other')) {

        $equipment = IctramEquipment::create([
            'equipment_name' => $request->input('jobType_other'),
        ]);
    } else {
        $ictram_equipment_id = $request->input('ictram_equipment_id');
        
        $equipment = IctramEquipment::findOrFail($ictram_equipment_id);

    }

        //Problem Other
    if ($request->has('problem_other')) {

        $problemIds[] = IctramProblem::create([
            'problem_description' => $request->input('problem_other'),
        ]);


    } else {
        $ictram_problem_ids = $request->input('ictram_problem_ids');
        
        $problemIds = IctramProblem::findOrFail($ictram_problem_ids);
    }
    
        foreach ($problemIds as $problemId) {
            Ictram::create([
                'ictram_job_type_id' => $jobType->id,
                'ictram_equipment_id' => $equipment->id,
                'ictram_problem_id' => $problemId->id,
            ]);
        }

        return redirect()->route('ictrams.index')->with('success', 'ICTRAM Saved successfully.');
    }
    public function destroy($id)
    {
            $jobType = Ictram::findOrFail($id);
            $jobType->delete();
        return redirect()->route('ictrams.index')->with('success', 'Deleted successfully');
    }
}

