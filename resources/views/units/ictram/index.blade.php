@extends('layouts.template')

@section('content')


<div class="content-wrapper">
    <section class="content-header">
        
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">ICTRAMs Assignation</h1>
          </div>
        </div>
      </div>

    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card px-3 pt-3 pb-1">
                        <form id="jobForm" action="{{ route('ictrams.add-relation') }}" method="POST" onsubmit="return validateForm()">
                                @csrf
                                <div class="d-md-flex flex-md-row flex-column justify-content-between gap-3">
                                <div class="mx-2 w-100">
                                    <div class="d-flex flex-row justify-content-between align-items-center mb-1 mt-2">
                                        <label for="jobType">Job Type</label>
                                        <div>
                                        <button type="button" class="btn btn-outline-primary float-right" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#ictramCreateJobTypeModal222">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <input class="form-control" type="text" id="displayFieldJobType" name="jobType_name" placeholder="Select JobType" onclick="toggleDropdown('dropdownMenuJobType')" readonly required>
                                        <span id="jobTypeValidationMessage" class="text-danger" style="display: none;">This field must have a value.</span>
                                        <input type="hidden" id="hiddenInputJobType" name="ictram_job_type_id" required>
                                        <div class="dropdown-menu scrollable-menu" id="dropdownMenuJobType" aria-labelledby="dropdownMenuButtonJobType">
                                            <div class="px-2 w-100 sticky-top bg-light">
                                                <input type="text" class="form-control search-input px-3" placeholder="Search..." oninput="filterDropdown('dropdownMenuJobType')">
                                            </div>
                                                @foreach($jobTypes as $jobType)
                                                    <a class="dropdown-item" href="#" onclick="selectItemJobType('{{ $jobType->id }}', '{{ $jobType->jobType_name }}', 'displayFieldJobType', 'hiddenInputJobType')">{{ $jobType->jobType_name }}</a>
                                                @endforeach
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mx-2 w-100">
                                    <div class="d-flex flex-row justify-content-between align-items-center mb-1 mt-2">
                                        <label for="equipment">Equipment</label>
                                        <button type="button" class="btn btn-outline-primary float-right" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#ictramCreateJobTypeModal222">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="dropdown">
                                        <input class="form-control" type="text" id="displayFieldEquipment" name="equipment_name" placeholder="Select Equipment" onclick="toggleDropdown('dropdownMenuEquipment')" readonly>
                                        <span id="EquipmentValidationMessage" class="text-danger" style="display: none;">This field must have a value.</span>
                                        <input type="hidden" id="hiddenInputEquipmentId" name="ictram_equipment_id" required>
                                        <div class="dropdown-menu scrollable-menu" id="dropdownMenuEquipment" aria-labelledby="dropdownMenuButtonEquipment">
                                            <div class="px-2 w-100 sticky-top">
                                                <input type="text" class="form-control search-input px-3" placeholder="Search..." oninput="filterDropdown('dropdownMenuEquipment')">
                                            </div>
                                            @foreach($equipments as $equipment)
                                                <a class="dropdown-item" href="#" onclick="selectItemEquipment('{{ $equipment->id }}', '{{ $equipment->equipment_name }}', 'displayFieldEquipment', 'hiddenInputEquipmentId')">{{ $equipment->equipment_name }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>


                                
                                <div class="mx-2 w-100">
                                    <div class="d-flex flex-row justify-content-between align-items-center mb-1 mt-2">
                                        <label for="jobType">Problem</label>
                                        <div>
                                        <button type="button" class="btn btn-outline-primary float-right" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#ictramCreateJobTypeModal222">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <input class="form-control" type="text" id="displayFieldProblem" name="problem_description" placeholder="Select Problem" onclick="toggleDropdown('dropdownMenuProblem')" readonly required>
                                        <span id="ProblemValidationMessage" class="text-danger" style="display: none;">This field must have a value.</span>
                                        <input type="hidden" id="hiddenInputProblem" name="ictram_problem_id" required>
                                        <div class="dropdown-menu scrollable-menu" id="dropdownMenuProblem" aria-labelledby="dropdownMenuButtonProblem">
                                            <div class="px-2 w-100 sticky-top">
                                                <input type="text" class="form-control search-input px-3" placeholder="Search..." oninput="filterDropdown('dropdownMenuProblem')">
                                            </div>
                                                @foreach($problems as $problem)
                                                    <a class="dropdown-item" href="#" onclick="selectItemProblem('{{ $problem->id }}', '{{ $problem->problem_description }}', 'displayFieldProblem', 'hiddenInputProblem')">{{ $problem->problem_description }}</a>
                                                @endforeach
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="d-flex flex-row justify-content-end mx-2 mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check"></i> Save
                                </button>
                                </div>
                            </form>
                            <!-- @include('units.ictram.create-equipment')
                            

                            @include('units.ictram.create-problem') -->
                    </div>
            <table id="datatable-responsive" class="table table-bordered table-hover text-center table-sm">
                <!-- <thead>
                    <tr>
                        <th>#</th>
                        <th>Job-Types</th>
                        <th>Equipemnts</th>
                        <th>Problems</th>
                    </tr>
                </thead> -->
<tbody>
    @foreach ($sortedIctrams->groupBy('jobType.jobType_name') as $jobTypeName => $ictrams)
        <tr>
            <th class="bg-info py-2" colspan="4">{{ $jobTypeName }}</th>
        </tr>
        <tr>
            <th>Equipment</th>
            <th>Problem</th>
        </tr>
        @foreach ($ictrams as $index => $ictram)
            <tr>
                <td>{{ $ictram->equipment->equipment_name }}</td>
                <td>{{ $ictram->problem->problem_description }}</td>
            </tr>
        @endforeach
    @endforeach
</tbody>


            </table>
                </div>
            </div>
        </div>
    </div>
</section>  
</div> 
@endsection

<style>
    .scrollable-menu {
        max-height: 250px;
        overflow-y: auto;
    }
</style>

<script>
function toggleDropdown(menuId) {
    var dropdownMenus = document.querySelectorAll('.dropdown-menu');
    dropdownMenus.forEach(function(menu) {
        if(menu.id !== menuId){
        menu.classList.remove("show");
        }
    });
    var dropdownMenu = document.getElementById(menuId);
    dropdownMenu.classList.toggle("show");
}
function selectItemJobType(item1, item, displayId, hiddenId, menuId) {
    document.getElementById(displayId).value = item;
    document.getElementById(hiddenId).value = item1;
    toggleDropdown(displayId.replace("displayFieldJobType", "dropdownMenuJobType"));
}
function selectItemEquipment(item1, item, displayId, hiddenId, menuId) {
    console.log("selectItemEquipment", item1);
    document.getElementById(displayId).value = item;
    document.getElementById(hiddenId).value = item1;
    toggleDropdown(displayId.replace("displayFieldEquipment", "dropdownMenuEquipment"));
}
function selectItemProblem(item1, item, displayId, hiddenId, menuId) {
    document.getElementById(displayId).value = item;
    document.getElementById(hiddenId).value = item1;

    toggleDropdown(displayId.replace("displayFieldProblem", "dropdownMenuProblem"));
}

function filterDropdown(menuId) {
    var input, filter, dropdownItems, items, i;
    input = document.querySelector("#" + menuId + " .search-input");
    filter = input.value.toUpperCase();
    dropdownItems = document.querySelectorAll("#" + menuId + " .dropdown-item");
    for (i = 0; i < dropdownItems.length; i++) {
        items = dropdownItems[i].innerText;
        if (items.toUpperCase().indexOf(filter) > -1) {
            dropdownItems[i].style.display = "";
        } else {
            dropdownItems[i].style.display = "none";
        }
    }
}
function validateForm() {
    var jobTypeField = document.getElementById("displayFieldJobType").value.trim();
    var EquipmentField = document.getElementById("displayFieldEquipment").value.trim();
    var ProblemField = document.getElementById("displayFieldProblem").value.trim();
    if (jobTypeField === "") {
        document.getElementById("jobTypeValidationMessage").style.display = "block";
        return false;
    } else if (EquipmentField === ""){
        document.getElementById("EquipmentValidationMessage").style.display = "block";
        document.getElementById("jobTypeValidationMessage").style.display = "none";
        return false;
    } else if (ProblemField === ""){
        document.getElementById("ProblemValidationMessage").style.display = "block";
        document.getElementById("EquipmentValidationMessage").style.display = "none";
        return false;
    }
    // Add validation for other fields similarly
    
    return true;
}
</script>