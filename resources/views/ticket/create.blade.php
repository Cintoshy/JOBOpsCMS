@extends('layouts.template')

@section('content')
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.4/axios.min.js"></script>
<div class="content-wrapper">
    <section class="content-header">
    </section>

    <section class="content">
        <div class="container-fluid mt-5">
            <div class="row align-content-center">
                <div class="col-md-12">
                    
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">Request Ticket</h3>
              </div>
              
              <form action="{{ route('store.ticket') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="container mt-5">
                        <div class="form-group">
                            <label for="service-location">Service Location</label>
                            <input type="text" name="building_number" class="form-control" placeholder="Enter Building Number or Name"><br>
                            <input type="text" name="office_name" class="form-control" placeholder="Enter Office Name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Enter Description">
                        </div>
                        <div class="form-group">

                            <label for="exampleInputFile">File input</label>
                            <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file_upload" name="file_upload">
                                <label class="custom-file-label" for="file_upload">Choose file</label>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text">Upload</span>
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="serial_number">Serial Number</label>
                            <input type="number" class="form-control" id="serial_number" name="serial_number" value="{{ old('serial_number') }}">
                        </div>
                        <div id="app">
                            <div class="mb-2">
                                <label for="unit">Select Unit:</label>
                                <select name="unit" class="form-select w-100 p-2 mb-2" v-model="selectedUnit" @change="fetchJobTypeDetails" required>
                                    <option value="" disabled>Select Unit</option>
                                    <option value="ICTRAM">ICTRAM</option>
                                    <option value="NICMUS">NICMUS</option>
                                    <option value="MIS">MIS</option>
                                </select>
                            </div>

                            <!-- ICTRAM Details -->
                            <div class=" w-50 mb-2" v-if="equipmentDetailsICTRAM.length > 0">
                                <div>
                                <label for="job_type">Select Job Type Details:</label>
                                <select class="form-select w-100 p-2 mb-2" name="ictram_job_type_id" v-model="selectedJobTypeICTRAM" @change="fetchEquipmentDetailsICTRAM(selectedJobTypeICTRAM)" required>
                                    <option value="" disabled>Select Job Type</option>
                                    <option v-for="detail in uniqueJobTypeDetailsICTRAM" :key="detail.job_type.id" :value="detail.job_type.id">
                                        ID: @{{ detail.job_type.id }} - @{{ detail.job_type.jobType_name }}
                                    </option>
                                </select>
                                </div>
                                <div>
                                <label for="equipment">Select Equipment Details:</label>
                                <select class="form-select w-100 p-2 mb-2" name="ictram_equipment_id" v-model="selectedEquipmentICTRAM" @change="fetchProblemDetailsICTRAM(selectedEquipmentICTRAM)" required>
                                    <option value="" disabled>Select Equipment</option>
                                    <option v-for="detail in uniqueEquipmentDetailsICTRAM" :key="detail.equipment.id" :value="detail.equipment.id">
                                        ID: @{{ detail.equipment.id }} - @{{ detail.equipment.equipment_name }}
                                    </option>
                                </select>
                                </div>
                                <div>
                                <label for="problem">Select Problem Details:</label>
                                <select class="form-select w-100 p-2 mb-2" name="ictram_problem_id" v-model="selectedProblemICTRAM" required>
                                    <option value="" disabled>Select Problem</option>
                                    <option v-for="detail in uniqueProblemDetailsICTRAM" :key="detail.problem.id" :value="detail.problem.id">
                                    ID: @{{ detail.problem.id }} - @{{ detail.problem.problem_description }}
                                    </option>
                                </select>
                                </div>
                            </div>

                            <!-- NICMUS Details -->
                            <div class="w-50 mb-2" v-if="equipmentDetailsNICMU.length > 0">
                                <div>
                                <label for="job_type">Select Job Type Details:</label>
                                <select class="form-select w-100 p-2 mb-2" name="nicmu_job_type_id" v-model="selectedJobTypeNICMU" @change="fetchEquipmentDetails(selectedJobTypeNICMU)" required>
                                    <option value="" disabled>Select Job Type</option>
                                    <option v-for="detail in uniqueJobTypeDetailsNICMU" :key="detail.job_type.id" :value="detail.job_type.id">
                                        ID: @{{ detail.job_type.id }} - @{{ detail.job_type.jobType_name }}
                                    </option>
                                </select>
                                </div>
                                <div>
                                <label for="equipment">Select Equipment Details:</label>
                                <select class="form-select w-100 p-2 mb-2" v-model="selectedEquipmentNICMU" name="nicmu_equipment_id" @change="fetchProblemDetails(selectedEquipmentNICMU)" required>
                                    <option value="" disabled>Select Equipment</option>
                                    <option v-for="detail in uniqueEquipmentDetailsNICMU" :key="detail.equipment.id" :value="detail.equipment.id">
                                    ID: @{{ detail.equipment.id }} - @{{ detail.equipment.equipment_name }}
                                    </option>
                                </select>
                                </div>
                                <div>
                                <label for="problem">Select Problem Details:</label>
                                <select class="form-select w-100 p-2 mb-2" v-model="selectedProblemNICMU" name="nicmu_problem_id" required>
                                    <option value="" disabled>Select Problem</option>
                                    <option v-for="detail in uniqueProblemDetailsNICMU" :key="detail.problem.id" :value="detail.problem.id">
                                    ID: @{{ detail.problem.id }} - @{{ detail.problem.problem_description }}
                                    </option>
                                </select>
                                </div>
                            </div>

                            <!-- MIS Details -->
                            <div class="w-50 mb-2" v-if="equipmentDetailsMIS.length > 0">
                                <label for="request_type_name">Request Type Name:</label>
                                <select class="form-select w-100 p-2 mb-2" v-model="selectedRequestType" name="mis_request_type_id" @change="fetchRequestTypeDetailsMIS(selectedRequestType)" required>
                                    <option value="" disabled>Select Request Type</option>
                                    <option v-for="detail in uniqueRequestTypeMIS" :key="detail.request_type_name.id" :value="detail.request_type_name.id">
                                    ID: @{{ detail.request_type_name.id }} - @{{ detail.request_type_name.requestType_name }}
                                    </option>
                                </select>
                                <label for="job_type">Select Job Type:</label>
                                <select class="form-select w-100 p-2 mb-2" v-model="selectedJobTypeMIS" name="mis_job_type_id" @change="fetchAccountNameMIS(selectedJobTypeMIS)" required>
                                    <option value="" disabled>Select Job Type</option>
                                    <option v-for="detail in uniqueJobTypeDetailsMIS" :key="detail.job_type.id" :value="detail.job_type.id">
                                    ID: @{{ detail.job_type.id }} - @{{ detail.job_type.jobType_name }}
                                    </option>
                                </select>
                                <label for="account_name">Select Account Name:</label>
                                <select class="form-select w-100 p-2 mb-2" v-model="selectedAccountname" name="mis_asname_id">
                                    <option value="" disabled>Select Account Name</option>
                                    <option v-for="detail in accountNameMISes" :key="detail.as_name.id" :value="detail.as_name.id" required>
                                    ID: @{{ detail.as_name.id }} - @{{ detail.as_name.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="covered_under_warranty" name="covered_under_warranty" value="1">
                            <label class="form-check-label" for="covered_under_warranty">Covered Under Warranty?</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
              </form>
            </div>
        </div>
    </section>
</div>
<script>
const { createApp, ref, computed } = Vue;

createApp({
    setup() {
        const selectedUnit = ref('');
        const selectedICTRAMDetail = ref('');
        const selectedEquipmentDetail = ref('');
        const accountNameDetails = ref([]);
        const equipmentDetailsNICMU = ref([]);
        const equipmentDetailsICTRAM = ref([]);
        const equipmentDetailsMIS = ref([]);
        const problemDetails = ref([]);
        const problemDetailsICTRAM = ref([]);
        const selectedProblemNICMU = ref('');
        const selectedJobTypeNICMU = ref('');
        const selectedEquipmentNICMU = ref('');
        const selectedJobTypeICTRAM = ref('');
        const selectedEquipmentICTRAM = ref('');
        const selectedProblemICTRAM = ref('');
        const selectedRequestType = ref('');
        const selectedJobTypeMIS = ref('');
        const selectedAccountname = ref('');
        const ICTRAM = ref([]);
        const NICMU = ref([]);
        const MIS = ref([]);
        const accountNameMISes = ref([]);

        const uniqueJobTypeDetailsNICMU = computed(() => {
            const uniqueDetails = [];
            const seenIds = new Set();
            equipmentDetailsNICMU.value.forEach(detail => {
                if (!seenIds.has(detail.job_type.id)) {
                    seenIds.add(detail.job_type.id);
                    uniqueDetails.push(detail);
                }
            });
            return uniqueDetails;
        });

        const uniqueJobTypeDetailsICTRAM = computed(() => {
            const uniqueDetails = [];
            const seenIds = new Set();
            equipmentDetailsICTRAM.value.forEach(detail => {
                if (!seenIds.has(detail.job_type.id)) {
                    seenIds.add(detail.job_type.id);
                    uniqueDetails.push(detail);
                }
            });
            return uniqueDetails;
        });

        const uniqueEquipmentDetailsICTRAM = computed(() => {
            const uniqueDetails = [];
            const seenIds = new Set();
            ICTRAM.value.forEach(detail => {
                if (!seenIds.has(detail.equipment.id)) {
                    seenIds.add(detail.equipment.id);
                    uniqueDetails.push(detail);
                }
            });
            return uniqueDetails;
        });

        const uniqueJobTypeDetailsMIS = computed(() => {
            const uniqueDetails = [];
            const seenIds = new Set();
            MIS.value.forEach(detail => {
                if (!seenIds.has(detail.job_type.id)) {
                    seenIds.add(detail.job_type.id);
                    uniqueDetails.push(detail);
                }
            });
            return uniqueDetails;
        });

        const uniqueEquipmentDetailsNICMU = computed(() => {
            const uniqueDetails = [];
            const seenIds = new Set();
            NICMU.value.forEach(detail => {
                if (!seenIds.has(detail.equipment.id)) {
                    seenIds.add(detail.equipment.id);
                    uniqueDetails.push(detail);
                }
            });
            return uniqueDetails;
        });

        const uniqueProblemDetailsNICMU = computed(() => {
            const uniqueDetails = [];
            const seenIds = new Set();
            problemDetails.value.forEach(detail => {
                if (!seenIds.has(detail.problem.id)) {
                    seenIds.add(detail.problem.id);
                    uniqueDetails.push(detail);
                }
            });
            return uniqueDetails;
        });

        const uniqueProblemDetailsICTRAM = computed(() => {
            const uniqueDetails = [];
            const seenIds = new Set();
            problemDetailsICTRAM.value.forEach(detail => {
                if (!seenIds.has(detail.problem.id)) {
                    seenIds.add(detail.problem.id);
                    uniqueDetails.push(detail);
                }
            });
            return uniqueDetails;
        });

        const uniqueRequestTypeMIS = computed(() => {
            const uniqueDetails = [];
            const seenIds = new Set();
            equipmentDetailsMIS.value.forEach(detail => {
                if (!seenIds.has(detail.mis_request_type_id)) {
                    seenIds.add(detail.mis_request_type_id);
                    uniqueDetails.push(detail);
                }
            });
            return uniqueDetails;
        });

        const uniqueAccountNameMIS = computed(() => {
            const uniqueDetails = [];
            const seenIds = new Set();
            accountNameMISes.value.forEach(detail => {
                if (!seenIds.has(detail.as_name.id)) {
                    seenIds.add(detail.as_name.id);
                    uniqueDetails.push(detail);
                }
            });
            return uniqueDetails;
        });

        const fetchJobTypeDetails = async () => {
            try {
                const response = await axios.get('/get-job-type-details', {
                    params: { unit: selectedUnit.value }
                });
                if (response.data.ictram) {
                    equipmentDetailsICTRAM.value = response.data.ictram;
                    equipmentDetailsNICMU.value = [];
                    equipmentDetailsMIS.value = [];
                } else if (response.data.nicmu) {
                    equipmentDetailsNICMU.value = response.data.nicmu;
                    equipmentDetailsICTRAM.value = [];
                    equipmentDetailsMIS.value = [];
                } else if (response.data.mis) {
                    equipmentDetailsMIS.value = response.data.mis;
                    equipmentDetailsICTRAM.value = [];
                    equipmentDetailsNICMU.value = [];
                }
            } catch (error) {
                console.error('Error fetching job type details:', error);
            }
        };

        const fetchEquipmentDetails = (id) => {
            NICMU.value = equipmentDetailsNICMU.value.filter(detail => detail.nicmu_job_type_id === id);
        };

        const fetchEquipmentDetailsICTRAM = (id) => {
            ICTRAM.value = equipmentDetailsICTRAM.value.filter(detail => detail.ictram_job_type_id === id);
        };

        const fetchRequestTypeDetailsMIS = (id) => {
            MIS.value = equipmentDetailsMIS.value.filter(detail => detail.mis_request_type_id === id);
        };

        const fetchProblemDetailsICTRAM = (id) => {
            problemDetailsICTRAM.value = ICTRAM.value.filter(detail => detail.ictram_equipment_id === id);
        };

        const fetchAccountNameMIS = (id) => {
            accountNameMISes.value = MIS.value.filter(detail => detail.mis_job_type_id === id);
        };

        const fetchProblemDetails = (id) => {
            problemDetails.value = NICMU.value.filter(detail => detail.nicmu_equipment_id === id);
        };

        return {
            selectedUnit,
            selectedICTRAMDetail,
            selectedEquipmentDetail,
            accountNameDetails,
            equipmentDetailsNICMU,
            equipmentDetailsICTRAM,
            equipmentDetailsMIS,
            problemDetails,
            problemDetailsICTRAM,
            selectedProblemNICMU,
            selectedJobTypeNICMU,
            selectedEquipmentNICMU,
            selectedJobTypeICTRAM,
            selectedEquipmentICTRAM,
            selectedProblemICTRAM,
            selectedRequestType,
            selectedJobTypeMIS,
            selectedAccountname,
            ICTRAM,
            NICMU,
            MIS,
            accountNameMISes,
            uniqueJobTypeDetailsNICMU,
            uniqueEquipmentDetailsNICMU,
            uniqueProblemDetailsNICMU,
            uniqueJobTypeDetailsICTRAM,
            uniqueEquipmentDetailsICTRAM,
            uniqueProblemDetailsICTRAM,
            uniqueJobTypeDetailsMIS,
            uniqueRequestTypeMIS,
            uniqueAccountNameMIS,
            fetchJobTypeDetails,
            fetchEquipmentDetails,
            fetchEquipmentDetailsICTRAM,
            fetchProblemDetailsICTRAM,
            fetchRequestTypeDetailsMIS,
            fetchAccountNameMIS,
            fetchProblemDetails,
        };
    },
    mounted() {
        this.fetchJobTypeDetails();
    }
}).mount('#app');
</script>

@endsection
