<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\JobController;
// use App\Http\Controllers\ProblemController;
// use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\RequestTypeController;
use App\Services\ActivityLogger;

use App\Http\Controllers\NicmuController;
use App\Http\Controllers\MisController;
use App\Http\Controllers\ICTRAMController;

use App\Http\Controllers\ICTRAMRequestController;
use App\Http\Controllers\ICTRAM\JobTypeController;
use App\Http\Controllers\ICTRAM\EquipmentController;
use App\Http\Controllers\ICTRAM\ProblemController;
use App\Http\Controllers\ICTRAM\AssignController;


// use App\Http\Controllers\ICTRAMRequestController;
use App\Http\Controllers\UnitController;

// use App\Http\Controllers\UnitController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::prefix('director')->middleware(['auth', 'cache', 'approved', 'directors'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('director.dashboard');
    Route::get('/users/approve/{id}', [UserController::class, 'approve'])->name('users.approve');
    Route::get('/users/disapprove/{id}', [UserController::class, 'disapprove'])->name('users.disapprove');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::put('/edit-user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/delete-user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

});

Route::prefix('unit-head')->middleware(['auth', 'cache', 'approved', 'unit-heads'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('unit-head.dashboard');

});

Route::prefix('mict-staff')->middleware(['auth', 'cache', 'approved', 'mict-staffs'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('mict-staff.dashboard');

});


Route::prefix('staff')->middleware(['auth', 'cache', 'approved','staffs'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('staff.dashboard');

});

Route::prefix('student')->middleware(['auth', 'cache', 'approved','student'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');

});
Route::get('/testing', function () {
    return view('testing'); 
})->name('testing');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::get('/account-pending', function () {
    return view('account.pending'); 
})->name('account.pending');


Route::middleware(['auth'])->group(function () {
    Route::get('/setup-profile', [UserController::class, 'setupProfileForm'])->name('user.setupProfile');
    Route::post('/setup-profile', [UserController::class, 'saveProfile'])->name('user.saveProfile');
});


Route::get('/login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [LoginController::class, 'handleGoogleCallback']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/edit-user/{id}', [UserController::class, 'edit'])->name('user.edit');

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
Route::get('/mark-notification-as-read/{notification}', [NotificationController::class, 'markAsRead'])->name('mark-notification-as-read');
Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
Route::get('/mark-notification-as-read/{notification}', [NotificationController::class, 'markAsRead'])->name('mark-notification-as-read');

Route::get('/tickets', [TicketController::class, 'index'])->name('tickets');
Route::get('/create/ticket', [TicketController::class, 'create'])->name('create.ticket');
Route::get('/get-job-type-details', [TicketController::class, 'getJobTypeDetails'])->name('create.ticketssss');
Route::post('/store/ticket', [TicketController::class, 'store'])->name('store.ticket');
Route::get('/show/ticket/{id}', [TicketController::class, 'show'])->name('ticket.show');
Route::get('/edit-ticket/{id}', [TicketController::class, 'edit'])->name('edit.ticket');
Route::put('/edit-ticket/{id}', [TicketController::class, 'update'])->name('update.ticket');
Route::delete('/delete-ticket/{id}', [TicketController::class, 'destroy'])->name('destroy.ticket');
Route::post('tickets/{ticket}/update-users', [TicketController::class, 'updateUsers'])->name('tickets.updateUsers');
Route::get('/tickets/unassigned', [TicketController::class, 'unassigned'])->name('tickets.unassigned');
Route::get('/tickets/assigned', [TicketController::class, 'assignedToMe'])->name('tickets.assigned');

Route::get('/faqs', [FaqsController::class, 'index'])->name('faqs');
Route::get('/create/faq', [FaqsController::class, 'create'])->name('create.faq');
Route::post('/store/faq', [FaqsController::class, 'store'])->name('store.faq');
Route::get('/faqs/show/{id}', [FaqsController::class, 'show'])->name('faq.show');
Route::get('/edit-faq/{id}', [FaqsController::class, 'edit'])->name('edit.faq');
Route::put('/edit-faq/{id}', [FaqsController::class, 'update'])->name('update.faq');
Route::delete('/faqs/{id}', [FaqsController::class, 'destroy'])->name('destroy.faq');
Route::post('/upload-image', [FaqsController::class, 'uploadImage'])->name('upload.uploadImage'); 

Route::post('/generate-tickets-report', [ReportController::class, 'ticketsReport'])->name('generate.tickets.report');
Route::post('/generate-users-report', [ReportController::class, 'usersReport'])->name('generate.users.report');
Route::post('/generate-faqs-report', [ReportController::class, 'faqsReport'])->name('generate.faqs.report');
Route::post('/generate-faqs-report', [ReportController::class, 'faqsReport'])->name('generate.faqs.report');
Route::post('/open-report', [ReportController::class, 'openReport'])->name('open-status.report');
Route::post('/in-progress-report', [ReportController::class, 'inProgressReport'])->name('in-progress-status.report');
Route::post('/closed-report', [ReportController::class, 'closedReport'])->name('closed-status.report');
Route::post('/high-report', [ReportController::class, 'highReport'])->name('high-priority.report');
Route::post('/mid-report', [ReportController::class, 'midReport'])->name('mid-priority.report');
Route::post('/low-report', [ReportController::class, 'lowReport'])->name('low-priority.report');
Route::post('/unassigned/tickets', [ReportController::class, 'unassignedReport'])->name('unassigned.report');

Route::get('/status/open', [StatusController::class, 'open'])->name('status.open');
Route::get('/status/in-progress', [StatusController::class, 'inProgress'])->name('status.in-progress');
Route::get('/status/closed', [StatusController::class, 'closed'])->name('status.closed');
Route::get('/status/completed', [StatusController::class, 'completed'])->name('status.completed');

Route::get('/priority-level/high', [PriorityController::class, 'high'])->name('priority-level.high');
Route::get('/priority-level/mid', [PriorityController::class, 'mid'])->name('priority-level.mid');
Route::get('/priority-level/low', [PriorityController::class, 'low'])->name('priority-level.low');

// Route for Admin and MICT Staff only
Route::patch('tickets/{id}/status', [TicketController::class, 'updateStatus'])->name('tickets.updateStatus');
Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log');

Route::get('/jobs/create', [JobController::class, 'create'])->name('jobs.create');
Route::post('/jobs/store', [JobController::class, 'store'])->name('jobs.store');

// Adding API routes for dynamic dropdowns
Route::get('/api/job-types/{unitId}', [JobController::class, 'getJobTypesByUnit']);

Route::get('/ICTRAM-unit/create', [ICTRAMController::class, 'create'])->name('ictram-unit.create');
Route::post('/ICTRAM-unit/store', [ICTRAMController::class, 'store'])->name('ictram-unit.store');

// API routes for dynamically fetching data based on selection
Route::get('/request-types/{id}/job-types', [RequestController::class, 'getJobTypes']);
Route::get('/job-types/{id}/equipments', [RequestController::class, 'getEquipments']);
Route::get('/equipments/{id}/problems', [RequestController::class, 'getProblems']);

// Routes for NICMU
Route::get('/nicmus', [NicmuController::class, 'index'])->name('nicmus.index');
Route::post('/nicmus/add-relation', [NicmuController::class, 'storeWithRelationShip'])->name('nicmu.add-relation');
Route::post('/nicmus/storeJobType', [NicmuController::class, 'storeJobType'])->name('nicmus.storeJobType');
Route::post('/nicmus/storeEquipment', [NicmuController::class, 'storeEquipment'])->name('nicmus.storeEquipment');
Route::post('/nicmus/storeProblem', [NicmuController::class, 'storeProblem'])->name('nicmus.storeProblem');
Route::get('/nicmus/create', [NicmuController::class, 'create'])->name('nicmus.create');
Route::post('/nicmus', [NicmuController::class, 'store'])->name('nicmus.store');
// Route::get('/nicmus/{id}', [NicmuController::class, 'show'])->name('nicmus.show');
Route::get('/nicmus/{id}/edit', [NicmuController::class, 'edit'])->name('nicmus.edit');
Route::put('/nicmus/{id}', [NicmuController::class, 'update'])->name('nicmus.update');
Route::delete('/nicmus/{id}', [NicmuController::class, 'destroy'])->name('nicmus.destroy');

Route::delete('/nicmus/delete-job-type/{id}', [NicmuController::class, 'destroyJobType'])->name('nicmus.destroyJobType');
Route::delete('/nicmus/delete-equipment/{id}', [NicmuController::class, 'destroyEquipment'])->name('nicmus.destroyEquipment');
Route::delete('/nicmus/delete-problem/{id}', [NicmuController::class, 'destroyProblem'])->name('nicmus.destroyProblem');
Route::delete('/nicmus/delete-relation/{id}', [NicmuController::class, 'destroy'])->name('nicmus.destroyNicmu');

Route::put('/nicmus/{id}/edit-job-types', [NICMUController::class, 'jobTypeEdit'])->name('nicmus.editJobType');
Route::put('/nicmus/{id}/edit-equipment', [NICMUController::class, 'equipmentEdit'])->name('nicmus.editEquipment');
Route::put('/nicmus/{id}/edit-problem', [NICMUController::class, 'problemEdit'])->name('nicmus.editProblem');

Route::get('/nicmus/job-types', [NicmuController::class, 'jobType_index'])->name('nicmus.JobTypes');
Route::get('/nicmus/equipments', [NicmuController::class, 'equipment_index'])->name('nicmus.Equipments');
Route::get('/nicmus/problems', [NicmuController::class, 'problem_index'])->name('nicmus.Problems');

// Routes for MIS
Route::get('/mises', [MisController::class, 'index'])->name('mises.index');
Route::post('/mises/add-relation', [MisController::class, 'storeWithRelationShip'])->name('mises.add-relation');
Route::get('/mises/create', [MisController::class, 'create'])->name('mises.create');
Route::post('/mises', [MisController::class, 'store'])->name('mises.store');
// Route::get('/mises/{id}', [MisController::class, 'show'])->name('mises.show');
Route::get('/mises/{id}/edit', [MisController::class, 'edit'])->name('mises.edit');
Route::put('/mises/{id}', [MisController::class, 'update'])->name('mises.update');

Route::get('/mises/job-types', [MisController::class, 'jobType_index'])->name('mises.JobTypes');
Route::get('/mises/request-types', [MisController::class, 'requestType_index'])->name('mises.RequestTypes');
Route::get('/mises/asnames', [MisController::class, 'asName_index'])->name('mises.AsNames');

Route::post('/mises/storeJobType', [MisController::class, 'storeJobType'])->name('mises.storeJobType');
Route::post('/mises/storeRequestType', [MisController::class, 'storeRequestType'])->name('mises.storeRequestType');
Route::post('/mises/storeAsName', [MisController::class, 'storeAsName'])->name('mises.storeAsName');

Route::put('/mises/{id}/edit-job-type', [MisController::class, 'jobTypeEdit'])->name('mises.editJobType');
Route::put('/mises/{id}/edit-equipment', [MisController::class, 'requestTypeEdit'])->name('mises.editRequestType');
Route::put('/mises/{id}/edit-problem', [MisController::class, 'asNameEdit'])->name('mises.editAsName');


Route::delete('/mises/{id}', [MisController::class, 'destroy'])->name('mises.destroyMis');
Route::delete('/mises/delete-job-type/{id}', [MisController::class, 'destroyJobType'])->name('mises.destroyJobType');
Route::delete('/mises/delete-equipment/{id}', [MisController::class, 'destroyRequestType'])->name('mises.destroyRequestType');
Route::delete('/mises/delete-problem/{id}', [MisController::class, 'destroyAsName'])->name('mises.destroyAsName');

// Routes for ICTRAM Director Side
Route::get('/ictram/create', [ICTRAMController::class, 'create'])->name('ictram.create');
Route::post('/ictram/store', [ICTRAMController::class, 'store'])->name('ictram.store');

Route::get('/ictrams', [AssignController::class, 'index'])->name('ictrams.index');
Route::post('/ictrams/add-relation', [AssignController::class, 'storeWithRelationShip'])->name('ictrams.add-relation');

Route::post('/ictrams/storeProblem', [ICTRAMController::class, 'storeProblem'])->name('ictrams.storeProblem');
Route::get('/ictrams/offices', [ICTRAMController::class, 'offices'])->name('ictrams.offices');
Route::get('/ictrams/create', [ICTRAMController::class, 'create'])->name('ictrams.create');
Route::get('/ictrams/job-types', [JobTypeController::class, 'index'])->name('ictrams.JobTypes');
Route::get('/ictrams/equipments', [EquipmentController::class, 'index'])->name('ictrams.Equipments');
Route::get('/ictrams/problems', [ProblemController::class, 'index'])->name('ictrams.Problems');
Route::post('/ictrams/storeJobType', [ICTRAMController::class, 'storeJobType'])->name('ictrams.storeJobType');
Route::post('/ictrams/storeEquipment', [ICTRAMController::class, 'storeEquipment'])->name('ictrams.storeEquipment');
Route::post('/ictrams/storeProblem', [ICTRAMController::class, 'storeProblem'])->name('ictrams.storeProblem');
Route::get('/ictrams/{id}', [ICTRAMController::class, 'show'])->name('ictrams.show');
Route::put('/ictrams/{id}/edit-job-types', [ICTRAMController::class, 'jobTypeEdit'])->name('ictrams.editJobType');
Route::put('/ictrams/{id}/edit-equipment', [ICTRAMController::class, 'equipmentEdit'])->name('ictrams.editEquipment');
Route::put('/ictrams/{id}/edit-problem', [ICTRAMController::class, 'problemEdit'])->name('ictrams.editProblem');

Route::put('/ictrams/{id}', [ICTRAMController::class, 'update'])->name('ictrams.update');
Route::delete('/ictrams/delete-job-type/{id}', [ICTRAMController::class, 'destroyJobType'])->name('ictrams.destroyJobType');
Route::delete('/ictrams/delete-equipment/{id}', [ICTRAMController::class, 'destroyEquipment'])->name('ictrams.destroyEquipment');
Route::delete('/ictrams/delete-problem/{id}', [ICTRAMController::class, 'destroyProblem'])->name('ictrams.destroyProblem');
Route::delete('/ictrams/delete-relation/{id}', [AssignController::class, 'destroy'])->name('ictrams.destroyIctram');

Route::get('/api/equipments/{jobType}', [ICTRAMController::class, 'getEquipmentsByJobType'])->name('equipments.byJobType');

// Routes for ICTRAM for Client side
Route::get('/ictram/requests', [ICTRAMRequestController::class, 'index'])->name('ictram.request.index');
Route::get('/ictram/requests/create', [ICTRAMRequestController::class, 'create'])->name('ictram.request.create');
Route::post('/ictram/requests', [ICTRAMRequestController::class, 'store'])->name('ictram.request.store');
Route::get('/ictram/requests/{id}', [ICTRAMRequestController::class, 'show'])->name('ictram.request.show');
Route::get('/ictram/requests/{id}/edit', [ICTRAMRequestController::class, 'edit'])->name('ictram.request.edit');
Route::put('/ictram/requests/{id}', [ICTRAMRequestController::class, 'update'])->name('ictram.request.update');
Route::delete('/ictram/requests/{id}', [ICTRAMRequestController::class, 'destroy'])->name('ictram.request.destroy');

// Tickets Per Unit
Route::get('/ictram-tickets', [UnitController::class, 'ictramIndex'])->name('ictram-tickets');
Route::get('/nicmu-tickets', [UnitController::class, 'nicmuIndex'])->name('nicmu-tickets');
Route::get('/mis-tickets', [UnitController::class, 'misIndex'])->name('mis-tickets');
Route::get('/unit/purchased', [UnitController::class, 'purchased'])->name('unit.purchased');


Route::get('/search', [ReportController::class, 'index'])->name('search');
Route::get('/result', [TicketController::class, 'filter'])->name('tickets.filter');
Route::get('/tickets/export/excel', [ReportController::class, 'exportExcel'])->name('tickets.export.excel');
Route::get('/tickets/export/pdf', [ReportController::class, 'exportPdf'])->name('tickets.export.pdf');


Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});