<div class="row">
    <div class="col-md-12 mt-3 mb-3">
        <p>No. of Trainees/Beneficiaries Who Rated the <em>Quality</em> of Extension Service</p>
        <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="qpoor">Poor</label>
                <input value="{{ old('quality_poor', ((isset($value['quality_poor'])) ?  $value['quality_poor'] : '' ) ) }}" type="number" class="form-control form-validation" id="qpoor" name="quality_poor">
                @error('quality_poor')
                    <span class='invalid-feedback' role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="qfair">Fair</label>
                <input type="number" value="{{ old('quality_fair', ((isset($value['quality_fair'])) ?  $value['quality_fair'] : '' ) ) }}"  class="form-control form-validation" id="qfair" name="quality_fair">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="qsatisfactory">Satisfactory</label>
                <input type="number" value="{{ old('quality_satisfactory', ((isset($value['quality_satisfactory'])) ?  $value['quality_satisfactory'] : '' ) ) }}"  class="form-control form-validation" id="qsatisfactory" name="quality_satisfactory">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="qverysatisfactory">Very Satisfactory</label>
                <input type="number" value="{{ old('quality_very_satisfactory', ((isset($value['quality_very_satisfactory'])) ?  $value['quality_very_satisfactory'] : '' ) ) }}" class="form-control form-validation" id="qverysatisfactory" name="quality_very_satisfactory">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="qoutstanding">Outstanding</label>
                <input type="number" value="{{ old('quality_outstanding', ((isset($value['quality_outstanding'])) ?  $value['quality_outstanding'] : '' ) ) }}" class="form-control form-validation" id="qoutstanding" name="quality_outstanding">
            </div>
        </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mb-3">
        <p>No. of Trainees/Beneficiaries Who Rated the <em>Timeliness</em> of Extension Service</p>
        <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label for="tpoor">Poor</label>
                <input type="number" value="{{ old('timeliness_poor', ((isset($value['timeliness_poor'])) ?  $value['timeliness_poor'] : '' ) ) }}" class="form-control form-validation" id="tpoor" name="timeliness_poor">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="tfair">Fair</label>
                <input type="number" value="{{ old('timeliness_fair', ((isset($value['timeliness_fair'])) ?  $value['timeliness_fair'] : '' ) ) }}" class="form-control form-validation" id="tfair" name="timeliness_fair">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="tsatisfactory">Satisfactory</label>
                <input type="number" value="{{ old('timeliness_satisfactory', ((isset($value['timeliness_satisfactory'])) ?  $value['timeliness_satisfactory'] : '' ) ) }}" class="form-control form-validation" id="tsatisfactory" name="timeliness_satisfactory">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="tverysatisfactory">Very Satisfactory</label>
                <input type="number" value="{{ old('timeliness_very_satisfactory', ((isset($value['timeliness_very_satisfactory'])) ?  $value['timeliness_very_satisfactory'] : '' ) ) }}" class="form-control form-validation" id="tverysatisfactory" name="timeliness_very_satisfactory">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="toutstanding">Outstanding</label>
                <input type="number" value="{{ old('timeliness_outstanding', ((isset($value['timeliness_outstanding'])) ?  $value['timeliness_outstanding'] : '' ) ) }}" class="form-control form-validation" id="toutstanding" name="timeliness_outstanding">
            </div>
        </div>
        </div>
    </div>
</div>