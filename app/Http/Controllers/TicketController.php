<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Notification;
use App\Notifications\TicketCreatedNotification;
use App\Notifications\TicketAssignedNotification;
use App\Models\ProblemTypeOrEquipment;
use App\Models\Ictram;
use App\Models\Nicmu;
use App\Models\Mis;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Unit;
use Carbon\Carbon;
use App\Models\IctramJobType;
use Illuminate\Support\Facades\Auth;


class TicketController extends Controller
{

        public function create()
        {
            // $ictrams = Ictram::with(['jobType', 'equipment', 'problem'])->get();
            // $nicmus = Nicmu::with(['jobType', 'equipment', 'problem'])->get();
            // $mises = Mis::all();
            return view('ticket.create');
        
        }

// public function getJobTypeDetails(Request $request)
// {
//     $jobType = $request->query('unit');
    
//     switch ($jobType) {
//         case 'ICTRAM':
//             $object = Ictram::with(['jobType', 'equipment', 'problem'])->get();
//             $details = ['ictram' => $object];
//             break;
//         case 'NICMUS':
//             $object = Nicmu::with(['jobType', 'equipment', 'problem'])->get();
//             $details = ['nicmu' => $object];
//             break;
//         case 'MIS':
//             $object = Mis::with(['requestTypeName', 'jobType', 'asName'])->get();
//             $details = ['mis' => $object];
//             break;
//         default:
//             $details = [];
//             break;
//         if ($request->has('priority_level')) {
//             $query->where('priority_level', $request->priority_level);
//         }

//         if ($request->has('status')) {
//             $query->where('status', $request->status);
//         }

//         $sortBy = $request->input('sort_by', 'id');
//         $sortDirection = $request->input('sort_order', 'asc');

//         $tickets = $query->orderBy($sortBy, $sortDirection)->get();

//         return view('ticket.index', compact('tickets','userIds'));
//     }
// }
    public function exportExcel(Request $request)
    {
        $tickets = $this->filteredTickets($request);

        // Create a new spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Merge cells for the header
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->mergeCells('A3:J3');
        $sheet->mergeCells('A4:J4');
        $sheet->mergeCells('A5:J5');
        $sheet->mergeCells('A6:J6');
        $sheet->mergeCells('A7:J7');

        // Set the header text
        $sheet->setCellValue('A1', "Republic of the Philippines");
        $sheet->setCellValue('A2', "CAMARINES SUR POLYTECHNIC COLLEGES");
        $sheet->setCellValue('A3', "Nabua, Camarines Sur");
        $sheet->setCellValue('A5', "MANAGEMENT INFORMATION AND COMMUNICATIONS TECHNOLOGY");
        $sheet->setCellValue('A6', "SUMMARY LIST OF JOB ORDER REQUEST FORM");
        $sheet->setCellValue('A7', "ICT REPAIR AND INSTALLATION\nfor the Month of January 2024");

        // Load the logo image
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path('dist/img/CSPC-Logo.jpg')); 
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(1000); // Adjust the offset to center the logo
        $drawing->setWorksheet($sheet);

        // Format the header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ];
        $sheet->getStyle('A1:J7')->applyFromArray($headerStyle);

        // Add column headers
        $sheet->setCellValue('A8', 'No.');
        $sheet->setCellValue('B8', 'REQUESITOR');
        $sheet->setCellValue('C8', 'Building Number');
        $sheet->setCellValue('D8', 'Office');
        $sheet->setCellValue('E8', 'Priority Level');
        $sheet->setCellValue('F8', 'Description');
        $sheet->setCellValue('G8', 'Status');
        $sheet->setCellValue('H8', 'Serial Number');
        $sheet->setCellValue('I8', 'Warranty Number');
        $sheet->setCellValue('J8', 'Date Requested');

        // Format column headers
        $columnHeaderStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A8:J8')->applyFromArray($columnHeaderStyle);

        // Populate the spreadsheet with data
        $row = 9;
        $index = 1;
        foreach ($tickets as $ticket) {
            $sheet->setCellValue('A' . $row, $index);
            $sheet->setCellValue('B' . $row, $ticket->user_id);
            $sheet->setCellValue('C' . $row, $ticket->building_number);
            $sheet->setCellValue('D' . $row, $ticket->office_name);
            $sheet->setCellValue('E' . $row, $ticket->priority_level);
            $sheet->setCellValue('F' . $row, $ticket->description);
            $sheet->setCellValue('G' . $row, $ticket->status);
            $sheet->setCellValue('H' . $row, $ticket->serial_number);
            $sheet->setCellValue('I' . $row, $ticket->covered_under_warranty ? 'Yes' : 'No');
            $sheet->setCellValue('J' . $row, $ticket->created_at);
            $row++;
            $index++;
        }

        // Adjust column widths
        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Generate and save the spreadsheet to a file
        $writer = new Xlsx($spreadsheet);
        $filePath = storage_path('app/public/tickets.xlsx');
        $writer->save($filePath);

        // Return the file as a response to the user
        return response()->download($filePath)->deleteFileAfterSend(true);
}

// public function getAllDetails(Request $request)
// {
//     $jobType = $request->query('unit');
    
//     switch ($jobType) {
//         case 'ICTRAM':
//             $object = IctramJobType::with(['jobType', 'equipment', 'problem'])->get();
//             $details = ['ictram' => $object];
//             break;
//         case 'NICMUS':
//             $object = Nicmu::with(['jobType', 'equipment', 'problem'])->get();
//             $details = ['nicmu' => $object];
//             break;
//         case 'MIS':
//             $object = Mis::with(['requestTypeName', 'jobType', 'asName'])->get();
//             $details = ['mis' => $object];
//             break;
//         default:
//             $details = [];
//             break;
//     }

//     return response()->json($details);
// }


public function getJobTypeDetails(Request $request)
{
    $jobType = $request->query('unit');
    
    switch ($jobType) {
        case 'ICTRAM':
            $object = Ictram::with(['jobType', 'equipment', 'problem'])->get();
            $details = ['ictram' => $object];
            break;
        case 'NICMUS':
            $object = Nicmu::with(['jobType', 'equipment', 'problem'])->get();
            $details = ['nicmu' => $object];
            break;
        case 'MIS':
            $object = Mis::with(['requestTypeName', 'jobType', 'asName'])->get();
            $details = ['mis' => $object];
            break;
        default:
            $details = [];
            break;
    }

    return response()->json($details);
}

public function getAllDetails(Request $request)
{
    $jobType = $request->query('unit');
    
    switch ($jobType) {
        case 'ICTRAM':
            $object = IctramJobType::with(['jobType', 'equipment', 'problem'])->get();
            $details = ['ictram' => $object];
            break;
        case 'NICMUS':
            $object = Nicmu::with(['jobType', 'equipment', 'problem'])->get();
            $details = ['nicmu' => $object];
            break;
        case 'MIS':
            $object = Mis::with(['requestTypeName', 'jobType', 'asName'])->get();
            $details = ['mis' => $object];
            break;
        default:
            $details = [];
            break;
    }

    return response()->json($details);
}


    public function getJobTypesByUnit($unitId)
    {
        $unit = Unit::find($unitId);
        if (!$unit) {
            return response()->json(['message' => 'Unit not found'], 404);
        }

        $jobTypes = $unit->jobTypes; // Assuming there is a 'jobTypes' relationship defined in Unit model
        return response()->json($jobTypes);
    }
    

    public function assignedToMe()
    {
        $userId = auth()->id();
    
        // Fetch tickets where the user is assigned
        $assignedTickets = Ticket::whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })
        ->with('user', 'users') // Load relationships
        ->orderBy('created_at', 'desc')
        ->get();
    
    
        return view('ticket.assigned', compact('assignedTickets'));
    }
    

    public function unassigned()
    {
        // Fetch tickets that have no users assigned
        $unassignedTickets = Ticket::doesntHave('users')->get();
        // $userIds = User::where('role', 2)->where('is_approved', true)->get(); 
        $userIds = User::whereIn('role', [1, 2])
               ->where('is_approved', true)
               ->get();


        // Return the view with the unassigned tickets data
        return view('ticket.unassigned', compact('unassignedTickets','userIds'));
    }
    
    // public function index()
    // {
    //     try {
    //         // Retrieve all tickets with their associated user (who created the ticket) and the assigned users.
    //         // Including 'user' in the with clause assumes you have a separate relationship defined in Ticket model to fetch the creator of the ticket
    //         $tickets = Ticket::with(['user', 'users'])->orderBy('created_at', 'desc')->get();
    //         $userIds = User::where('role', 2)->where('is_approved', true)->get();  // Specific user with conditions

    //         // Calculate age for each ticket
    //         $tickets->each(function ($ticket) {
    //             $ticket->age = Carbon::parse($ticket->created_at)->diffInDays(Carbon::now());
    //         });

            
    //         return view('ticket.index', compact('tickets','userIds'));
    //     } catch (Exception $e) {
    //         return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    //     }
    // }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'building_number' => 'nullable',
            'office_name' => 'nullable',
            'description' => 'nullable',
            'serial_number' => 'nullable',
            'file_upload' => 'nullable|file|max:2048',
            'ictram_job_type_id' => 'nullable',
            'ictram_equipment_id' => 'nullable',
            'ictram_problem_id' => 'nullable',
            'nicmu_job_type_id' => 'nullable',
            'nicmu_equipment_id' => 'nullable',
            'nicmu_problem_id' => 'nullable',
            'mis_request_type_id' => 'nullable',
            'mis_job_type_id' => 'nullable',
            'mis_asname_id' => 'nullable',
        ]);
        $userId = Auth::id();

        if ($request->hasFile('file_upload')) {
            $file = $request->file('file_upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $filename, 'public');
        }
        // Create a new ticket instance
        $ticket = new Ticket();
        $ticket->building_number = $request->building_number;
        $ticket->office_name = $request->office_name;
        $ticket->description = $request->description;
        $ticket->serial_number = $request->serial_number;

        $ticket->ictram_job_type_id = $request->ictram_job_type_id;
        $ticket->ictram_equipment_id = $request->ictram_equipment_id;
        $ticket->ictram_problem_id = $request->ictram_problem_id;

        $ticket->nicmu_job_type_id = $request->nicmu_job_type_id;
        $ticket->nicmu_equipment_id = $request->nicmu_equipment_id;
        $ticket->nicmu_problem_id = $request->nicmu_problem_id;

        $ticket->mis_request_type_id = $request->mis_request_type_id;
        $ticket->mis_job_type_id = $request->mis_job_type_id;
        $ticket->mis_asname_id = $request->mis_asname_id;

        $ticket->user_id = $userId;
        $ticket->file_path = $filePath;

        // Save the ticket
        $ticket->save();

        // Redirect with success message
        return redirect()->back()->with('success', 'Ticket Successfully Created!');
    }


    public function show($id)
    {
        try {
            // Retrieve and show the specific item using the provided ID
            $ticket = Ticket::findOrFail($id);
            
            $user = Ticket::with('users')->findOrFail($id);

            $userIds = User::whereIn('role', [1, 2])
                   ->where('is_approved', true)
                   ->get();

            return view('ticket.show', compact('ticket','user','userIds'));
        } catch (Exception $e) {
            // Handle the exception, you can log it for debugging or display an error message to the user.
            return back()->with('error', 'An error occurred while fetching the ticket: ' . $e->getMessage());
        }
    }
    
    public function edit($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $priority_level = $ticket->priority_level; 
            $status = $ticket->status; 
            $userIds = User::where('role', 2)->where('is_approved', true)->get();  
            
            return view('ticket.edit', compact('ticket','priority_level','status','userIds'))->with('error', 'An error occurred');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $ticket->update($request->all());
            

            // Log activity
            ActivityLogger::log('Updated', $ticket, 'Ticket updated');
            
            return redirect()->route('tickets')->with('success', 'Ticket Successfully Updated!');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $ticket->delete(); 
            
            // Log activity
            ActivityLogger::log('Deleted', $ticket, 'Ticket Deleted');
            
            return redirect()->back()->with('success', 'Task deleted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }

    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Open,In Progress,Closed'
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->status = $request->status;
        $ticket->save();

        return redirect()->route('tickets')->with('success', 'Ticket status updated successfully.');
    }
    
    public function updateUsers(Request $request, $ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId); // Ensure the ticket exists
        $userIds = $request->input('assigned_user_id'); // Corrected from user_ids to assigned_user_id
    
        // Sync the users to the ticket
        $ticket->users()->sync($userIds);
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Users assigned successfully!');

    }
    

}


