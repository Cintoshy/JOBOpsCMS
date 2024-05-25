
@extends('layouts.template')

@section('content')
<div class="form-group">
    <label for="ictram_job_type_id">Job Type</label>
    <select name="ictram_job_type_id" id="ictram_job_type_id" class="selectpicker form-control" data-style="btn-info" required>
        <option value="" disabled selected>Select Job Type</option>

        @foreach($ictrams as $ictram)
            <option value="{{ $ictram->id }}">{{ $ictram->jobType->jobType_name }}</option>
        @endforeach
    </select>
</div>


@endsection

