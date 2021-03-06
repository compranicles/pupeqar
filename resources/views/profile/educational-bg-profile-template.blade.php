<div class="row m-1">
    <div class="col-md-12">
        <div class="form-group input-group-sm">
            <label for="">School Name</label>
            <input type="text" readonly class="form-control" value="{{ $values->SchoolName }}">
        </div>
    </div>
    @if ($level == 1)
    <div class="col-md-6">
        <div class="form-group input-group-sm">
            <label for="">Program</label>
            <input type="text" readonly class="form-control" value="{{ $values->Degree }}">
        </div>
    </div>
    @php
        $educationDiscipline = '';
        foreach($disciplines as $discipline)
            if($values->EducationDisciplineID == $discipline->EducationDisciplineID)
                $educationDiscipline = $discipline->EducationDiscipline;
    @endphp
    <div class="col-md-6">
        <div class="form-group input-group-sm">
            <label for="">Program Discipline</label>
            <input type="text" readonly class="form-control" value="{{ $educationDiscipline }}">
        </div>
    </div>
    @endif
    <div class="col-md-2">
        <div class="form-group input-group-sm">
            <label for="">Graduated?</label>
            <input type="text" readonly class="form-control" value="{{ $values->IsGraduated }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group input-group-sm">
            <label for="">Currently Enrolled?</label>
            <input type="text" readonly class="form-control" value="{{ $values->IsCurrentlyEnrolled }}">
        </div>
    </div>
</div>
<div class="row m-1">
    <div class="col-md-3">
        <div class="form-group input-group-sm">
            <label for="">Inclusive Dates of Attendance</label>
            <input type="text" readonly class="form-control" value="{{ $values->IncYearFrom }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group input-group-sm">
            <label for="">-</label>
            <input type="text" readonly class="form-control" value="{{ $values->IncYearTo }}">
        </div>
    </div>
</div>
<div class="row m-1">
    <div class="col-md-3">
        <div class="form-group input-group-sm">
            <label for="">Highest Grade/Year Level/Units Earned</label>
            <input type="text" readonly class="form-control" value="{{ $values->UnitsEarned }}">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group input-group-sm">
            <label for="">Academic/Non-Academic Honors</label>
            <input type="text" readonly class="form-control" value="{{ $values->HonorsReceived }}">
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group input-group-sm">
            <label for="">Name of Scholarship Grant</label>
            <input type="text" readonly class="form-control" value="{{ $values->Scholarship }}">
        </div>
    </div>
</div>