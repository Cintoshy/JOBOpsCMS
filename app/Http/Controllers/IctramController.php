<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ictram;
use App\Models\IctramJobType;
use App\Models\IctramEquipment;
use App\Models\IctramProblem;

class ICTRAMController extends Controller
{
    public function create()
    {
        $jobTypes = IctramJobType::all();
        $equipments = IctramEquipment::all();
    
        return view('units.ictram.create', compact('jobTypes', 'equipments'));
    }
 public function store(Request $request)
{
    // Validate the request
    $request->validate([
        'jobType_id' => 'required_without:jobType_name|nullable|exists:ictram_job_types,id',
        'jobType_name' => 'required_without:jobType_id|nullable|string|max:255',
        'equipment_id' => 'required_without:equipment_name|nullable|exists:ictram_equipments,id',
        'equipment_name' => 'required_without:equipment_id|nullable|string|max:255',
        'problem_description' => 'required|string|max:255',
    ]);

    // Create or find Job Type
    if ($request->filled('jobType_name')) {
        $jobType = IctramJobType::firstOrCreate(['jobType_name' => $request->input('jobType_name')]);
    } else {
        $jobType = IctramJobType::find($request->input('jobType_id'));
    }

    // Check if the equipment with the given name and job type exists
    if ($request->filled('equipment_name')) {
        $existingEquipment = IctramEquipment::where('equipment_name', $request->input('equipment_name'))
            ->where('ictram_job_type_id', $jobType->id)
            ->first();

        if (!$existingEquipment) {
            // Create new Equipment if it does not exist
            $equipment = IctramEquipment::create([
                'equipment_name' => $request->input('equipment_name'),
                'ictram_job_type_id' => $jobType->id,
            ]);
        } else {
            $equipment = $existingEquipment;
        }
    } else {
        $equipment = IctramEquipment::find($request->input('equipment_id'));
    }

    // Create Problem associated with Equipment
    IctramProblem::create([
        'problem_description' => $request->input('problem_description'),
        'ictram_equipment_id' => $equipment->id,
    ]);

    return redirect()->back()->with('success', 'ICTRAM records created successfully.');
}

    
    // public function store(Request $request)
    // {
    //     // Validate the request
    //     $request->validate([
    //         'jobType_name' => 'required|string|max:255',
    //         'equipment_name' => 'required|string|max:255',
    //         'problem_description' => 'required|string|max:255',
    //     ]);
    
    //     // Create Job Type
    //     $jobType = IctramJobType::create(['jobType_name' => $request->input('jobType_name')]);
    
    //     // Create Equipment associated with Job Type
    //     $equipment = IctramEquipment::create([
    //         'equipment_name' => $request->input('equipment_name'),
    //         'ictram_job_type_id' => $jobType->id,
    //     ]);
    
    //     // Create Problem associated with Equipment
    //     IctramProblem::create([
    //         'problem_description' => $request->input('problem_description'),
    //         'ictram_equipment_id' => $equipment->id,
    //     ]);
    
    //     return redirect()->back()->with('success', 'ICTRAM records created successfully.');
    // }
    




    
    public function index()
    {
        $ictrams = Ictram::all();
        $jobTypes = IctramJobType::all();
        $equipments = IctramEquipment::all();
        $problems = IctramProblem::all();

        
        $ictrams = Ictram::with('jobTypes.equipments.problems')->get();
        
        // return view('units.ictram.index', compact('ictrams'));
        
        return view('units.ictram.index', compact('ictrams', 'jobTypes', 'equipments', 'problems'));
    }

    // Display the form
    // public function create()
    // {
    //     $jobTypes = IctramJobType::all();
    //     return view('units.ictram.index', compact('jobTypes'));
    // }

    // Handle form submission
    public function storeJobType(Request $request)
    {
        // Validate the request if needed
        $validatedData = $request->validate([
            'jobType_name' => 'required|string|max:255',
            // Add more validation rules as needed
        ]);

        // Create a new IctramJobType instance
        $jobType = new IctramJobType();
        $jobType->jobType_name = $request->input('jobType_name');
        // You can assign other properties here if needed
        $jobType->save();

        // You can return a response if needed, for example:
        return response()->json(['success' => true, 'message' => 'ICTRAM Job Type created successfully.']);
    }

        public function storeWithRelationShip(Request $request)
    {   
        IctramEquipment::create([
            'equipment_name' => $request->input('equipment_name'),
            'ictram_job_type_id' => $request->input('ictram_job_type_id'),
        ]);
        
        IctramProblem::create([
            'ictram_equipment_id' => $request->input('ictram_equipment_id'),
            'problem_description' => $request->input('problem_description'),
        ]);

        return redirect()->route('ictrams.index')->with('success', 'ICTRAM Job Type created successfully.');
    }

    
    // Handle form submission
    public function storeEquipment(Request $request)
    {
        $ictram = IctramEquipment::create($request->all());

        return redirect()->route('ictrams.index')->with('success', 'ICTRAM Request created successfully.');
    }
    
    // Handle form submission
    public function storeProblem(Request $request)
    {

        $equipments = IctramEquipment::all();
        $problem = IctramProblem::create([
            'ictram_equipment_id' => $request->input('ictram_equipment_id'),
            'problem_description' => $request->input('problem_description'),
        ]);

        return redirect()->route('ictrams.index', compact('equipments'));
    }


    public function edit($id)
    {
        $ictram = Ictram::findOrFail($id);
        return view('ictrams.edit', compact('ictram'));
    }

    public function show($id)
    {
        $ictram = Ictram::findOrFail($id);
        return view('ictrams.index', compact('ictram'));
    }

    public function destroy($id)
    {
        $ictram = Ictram::findOrFail($id);
        $ictram->delete();
        return redirect()->route('ictrams.index')->with('success', 'ICTRAM deleted successfully');
    }

    
    public function getEquipmentsByJobType($jobType)
    {
        // Fetch equipment options based on the selected job type
        $equipments = IctramEquipment::where('job_type_id', $jobType)->get();

        // Construct HTML options for the equipment dropdown
        $options = '';
        foreach ($equipments as $equipment) {
            $options .= '<option value="' . $equipment->id . '">' . $equipment->equipment_name . '</option>';
        }

        // Return the HTML options
        return $options;
    }
}
