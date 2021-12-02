<div class="row">
    <div class="col-md-12 mt-3 mb-3">
        <p>No. of Trainees/Benefiaries Who Rated the <em>Quality</em> of Extension Service</p>
        <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="qpoor">Poor</label>
                <input @if ($value!='') value="{{ old('quality_poor', $value['quality_poor']) }}" @endif type="number" class="form-control form-validation" id="qpoor" name="quality_poor">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="qfair">Fair</label>
                <input type="number" @if ($value!='') value="{{ old('quality_fair', $value['quality_fair']) }}" @endif class="form-control form-validation" id="qfair" name="quality_fair">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="qsatisfactory">Satisfactory</label>
                <input type="number" @if ($value!='') value="{{ old('quality_satisfactory', $value['quality_satisfactory']) }}" @endif class="form-control form-validation" id="qsatisfactory" name="quality_satisfactory">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="qverysatisfactory">Very Satisfactory</label>
                <input type="number" @if ($value!='') value="{{ old('quality_very_satisfactory', $value['quality_very_satisfactory']) }}" @endif class="form-control form-validation" id="qverysatisfactory" name="quality_very_satisfactory">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="qoutstanding">Outstanding</label>
                <input type="number" @if ($value!='') value="{{ old('quality_outstanding', $value['quality_outstanding']) }}" @endif class="form-control form-validation" id="qoutstanding" name="quality_outstanding">
            </div>
        </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-3">
        <p>No. of Trainees/Benefiaries Who Rated the <em>Timeliness</em> of Extension Service</p>
        <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="tpoor">Poor</label>
                <input type="number" @if ($value!='') value="{{ $value['timeliness_poor'] }}" @endif class="form-control form-validation" id="tpoor" name="timeliness_poor">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="tfair">Fair</label>
                <input type="number" @if ($value!='') value="{{ $value['timeliness_fair'] }}" @endif class="form-control form-validation" id="tfair" name="timeliness_fair">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="tsatisfactory">Satisfactory</label>
                <input type="number" @if ($value!='') value="{{ $value['timeliness_satisfactory'] }}" @endif class="form-control form-validation" id="tsatisfactory" name="timeliness_satisfactory">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="tverysatisfactory">Very Satisfactory</label>
                <input type="number" @if ($value!='') value="{{ $value['timeliness_very_satisfactory'] }}" @endif class="form-control form-validation" id="tverysatisfactory" name="timeliness_very_satisfactory">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="toutstanding">Outstanding</label>
                <input type="number" @if ($value!='') value="{{ $value['timeliness_outstanding'] }}" @endif class="form-control form-validation" id="toutstanding" name="timeliness_outstanding">
            </div>
        </div>
        </div>
    </div>
</div>