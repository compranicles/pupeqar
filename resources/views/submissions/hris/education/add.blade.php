<x-app-layout>
    @section('title', 'Ongoing Advanced/Professional Studies |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if(!isset($forview))
                <h2 class="font-weight-bold mb-2">Add Ongoing Advanced/Professional Study</h2>
                @else
                <h2 class="font-weight-bold mb-2">View Ongoing Advanced/Professional Study</h2>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @endif
                <p>
                    <a class="back_link" href="{{ route('submissions.educ.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Ongoing Advanced/Professional Studies</a>
                </p>
                {{-- Denied Details --}}
                @if ($deniedDetails = Session::get('denied'))
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-exclamation-circle"></i> Remarks: {{ $deniedDetails->reason }}
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('submissions.educ.store', $id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @if(!isset($forview))
                            <div class="mt-2 mb-3">
                                <i class="bi bi-pencil-square mr-1"></i><strong>Instructions: </strong> Please fill in the necessary details. No abbreviations. All inputs with the symbol (<strong style="color: red;">*</strong>) are required.
                            </div>
                            <hr>
                                @if (!isset($collegeOfDepartment))
                                    @include('form', ['formFields' => $educFields, 'value' => $values])
                                @else
                                    @include('form', ['formFields' => $educFields, 'value' => $values, 'colleges' => $colleges, 'collegeOfDepartment' => $collegeOfDepartment])
                                @endif
                            @else
                                @include('show', ['formFields' => $educFields, 'value' => $values])
                            @endif
                            <div class="form-group mt-3">
                                <label class="font-weight-bold" >Document</label>
                                <br>
                                <div class="img-container">
                                    <img src="{{ url('fetch_image/'.$id.'/1') }}" alt="">
                                </div>
                            </div>
                            @if(!isset($forview))
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Cancel</a>
                                            <button type="submit" id="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="{{ asset('js/spinner.js') }}"></script>
    @if(isset($forview))
    <script>
        $('#department_id').attr('disabled', 'disabled')
    </script>
    @endif
    <script>
        var uploadField = document.getElementById("document");

        uploadField.onchange = function() {
            if(this.files[0].size > 512000){
            alert("File is too big!");
            this.value = "";
            };
        };
    </script>
    <script>
        $('input[name="is_graduated"]').on('change', function () {
            if ($(this).val() == 'Yes') {
                $('#status').val(0);
                $('#is_enrolled2').attr("checked", "checked");
                $('#to').val('');
            }
            else {
                $('#status').val('');
                $('#is_enrolled1').attr("checked", "checked");
                $('#to').val('Present');
            }
        });

        $('#to').on('blur', function () {
            if ($('#is_enrolled2').prop('checked')) {
                if($('#to').val() < $('#from').val()) {
                    document.getElementById('to').value = "";
                    alert("For the inclusive end year, please select " + $('#from').val() + " onwards.");
                }
            }
        });
    </script>
    <script>
        $('#level').on('change', function () {
            if ($(this).val() == 1 || $(this).val() == 2 || $(this).val() == 8 ||
            $(this).val() == 9 || $(this).val() == 4) {
                $('#program_level').val(0);
                $('#education_discipline').val(0);
            } else {
                $('#program_level').val('');
                $('#education_discipline').val('');
            }
        });
    </script>
    @endpush
</x-app-layout>
