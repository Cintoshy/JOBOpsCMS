<?php

namespace App\Http\Controllers;
use App\Models\Ticket;
use App\Models\User;
use App\Models\FAQs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\PDF;
use App\Models\Report; 

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function generateReport(Request $request)
    {
        // Retrieve search parameters from the request
        $date = $request->input('date');
        $name = $request->input('name');
        $unit = $request->input('unit');
        $requestType = $request->input('request_type');

        // Query reports table
        $query = Report::query();

        // Apply filters based on search parameters
        if ($date) {
            $query->where('date', $date);
        }

        if ($name) {
            $query->where('name', $name);
        }

        if ($unit) {
            $query->where('unit', $unit);
        }

        if ($requestType) {
            $query->where('request', $requestType);
        }

        // Sort the results, you can change 'date' to any field you want to sort by
        $query->orderBy('date', 'asc');

        // Retrieve the sorted and filtered results
        $reports = $query->get();

        // You can return the results to your view or do further processing here
        return view('reports.index', compact('reports'));
    }

    public function unassignedReport(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        

        $unassignedTickets = Ticket::doesntHave('users')->whereBetween('created_at', [$start_date, $end_date])->get();

        $pdf = PDF::loadView('reports.unassigned-report', compact('unassignedTickets'));

        // Validate the date range
        $formattedStartDate = Carbon::parse($start_date)->startOfDay();
        $formattedEndDate = Carbon::parse($end_date)->endOfDay();
        
        return $pdf->stream('unassigned-report-' . $formattedStartDate . '-' . $formattedEndDate . '.pdf');
    }

    public function closedReport(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
        // Adjust database query to filter records based on the date range
        $tickets = Ticket::where('status', 'Closed')->whereBetween('created_at', [$start_date, $end_date])->get();

        $pdf = PDF::loadView('reports.closed-report', compact('tickets'));

        // Validate the date range
        $formattedStartDate = Carbon::parse($start_date)->startOfDay();
        $formattedEndDate = Carbon::parse($end_date)->endOfDay();
        
        return $pdf->stream('closed-report-' . $formattedStartDate . '-' . $formattedEndDate . '.pdf');
    }

    public function inProgressReport(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
        // Adjust database query to filter records based on the date range
        $tickets = Ticket::where('status', 'In Progress')->whereBetween('created_at', [$start_date, $end_date])->get();

        $pdf = PDF::loadView('reports.in-progress-report', compact('tickets'));

        // Validate the date range
        $formattedStartDate = Carbon::parse($start_date)->startOfDay();
        $formattedEndDate = Carbon::parse($end_date)->endOfDay();
        
        return $pdf->stream('in-progress-report-' . $formattedStartDate . '-' . $formattedEndDate . '.pdf');
    }

    public function openReport(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
        // Adjust database query to filter records based on the date range
        $tickets = Ticket::where('status', 'Open')->whereBetween('created_at', [$start_date, $end_date])->get();

        $pdf = PDF::loadView('reports.open-report', compact('tickets'));

        // Validate the date range
        $formattedStartDate = Carbon::parse($start_date)->startOfDay();
        $formattedEndDate = Carbon::parse($end_date)->endOfDay();
        
        return $pdf->stream('open-report-' . $formattedStartDate . '-' . $formattedEndDate . '.pdf');
    }

    public function ticketsReport(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
        // Adjust database query to filter records based on the date range
        $tickets = Ticket::whereBetween('created_at', [$start_date, $end_date])->get();

        $pdf = PDF::loadView('reports.tickets-reports', compact('tickets'));

        // Validate the date range
        $formattedStartDate = Carbon::parse($start_date)->startOfDay();
        $formattedEndDate = Carbon::parse($end_date)->endOfDay();
        
        return $pdf->stream('tickets-reports-' . $formattedStartDate . '-' . $formattedEndDate . '.pdf');
        
    }

    public function usersReport(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
        // Adjust database query to filter records based on the date range
        $users = User::whereBetween('created_at', [$start_date, $end_date])->get();

        $pdf = PDF::loadView('reports.users-report', compact('users'));

        // Validate the date range
        $formattedStartDate = Carbon::parse($start_date)->startOfDay();
        $formattedEndDate = Carbon::parse($end_date)->endOfDay();
        
        return $pdf->stream('users-report-' . $formattedStartDate . '-' . $formattedEndDate . '.pdf');
        
    }

    public function faqsReport(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
        // Adjust database query to filter records based on the date range
        $faqs = FAQs::whereBetween('created_at', [$start_date, $end_date])->get();

        $pdf = PDF::loadView('reports.faqs-report', compact('faqs'));

        // Validate the date range
        $formattedStartDate = Carbon::parse($start_date)->startOfDay();
        $formattedEndDate = Carbon::parse($end_date)->endOfDay();
        
        return $pdf->stream('faqs-report-' . $formattedStartDate . '-' . $formattedEndDate . '.pdf');
        
    }

    public function highReport(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
        // Adjust database query to filter records based on the date range
        $tickets = Ticket::where('priority_level', 'High')->whereBetween('created_at', [$start_date, $end_date])->get();

        $pdf = PDF::loadView('reports.high-report', compact('tickets'));

        // Validate the date range
        $formattedStartDate = Carbon::parse($start_date)->startOfDay();
        $formattedEndDate = Carbon::parse($end_date)->endOfDay();
        
        return $pdf->stream('high-report-' . $formattedStartDate . '-' . $formattedEndDate . '.pdf');
    }

    public function midReport(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
        // Adjust database query to filter records based on the date range
        $tickets = Ticket::where('priority_level', 'Mid')->whereBetween('created_at', [$start_date, $end_date])->get();

        $pdf = PDF::loadView('reports.mid-report', compact('tickets'));

        // Validate the date range
        $formattedStartDate = Carbon::parse($start_date)->startOfDay();
        $formattedEndDate = Carbon::parse($end_date)->endOfDay();
        
        return $pdf->stream('mid-report-' . $formattedStartDate . '-' . $formattedEndDate . '.pdf');
    }

    public function lowReport(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        
        // Adjust database query to filter records based on the date range
        $tickets = Ticket::where('priority_level', 'Low')->whereBetween('created_at', [$start_date, $end_date])->get();

        $pdf = PDF::loadView('reports.low-report', compact('tickets'));

        // Validate the date range
        $formattedStartDate = Carbon::parse($start_date)->startOfDay();
        $formattedEndDate = Carbon::parse($end_date)->endOfDay();
        
        return $pdf->stream('low-report-' . $formattedStartDate . '-' . $formattedEndDate . '.pdf');
    }
}
