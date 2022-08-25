<x-app-layout>
@section('title', 'Officerships & Memberships |')


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if(!isset($forview))
                <h2 class="font-weight-bold mb-2">Add Officership/Membership</h2>
                @else
                <h2 class="font-weight-bold mb-2">View Officership/Membership</h2>
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
                    <a class="back_link" href="{{ route('submissions.officership.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Officerships/Memberships</a>
                </p>
                @if ($deniedDetails = Session::get('denied'))
                 <div class="alert alert-info" role="alert">
                     <i class="bi bi-exclamation-circle"></i> Remarks: {{ $deniedDetails->reason }}
                 </div>
                 @endif
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('submissions.officership.store', $id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @if(!isset($forview))
                                <div class="mt-2 mb-3">
                                    <i class="bi bi-pencil-square mr-1"></i><strong>Instructions: </strong> Please fill in the necessary details. No abbreviations. All inputs with the symbol (<strong style="color: red;">*</strong>) are required.
                                </div>
                                <hr>
                                @if (!isset($collegeOfDepartment))
                                    @include('form', ['formFields' => $officeFields, 'value' => $values])
                                @else
                                    @include('form', ['formFields' => $officeFields, 'value' => $values, 'colleges' => $colleges, 'collegeOfDepartment' => $collegeOfDepartment])
                                @endif
                            @else
                                @include('show', ['formFields' => $officeFields, 'value' => $values])
                            @endif
                            <div class="form-group mt-3">
                                <label class="font-weight-bold" >Document</label>
                                <br>
                                <div class="img-container">
                                    <img src="{{ url('fetch_image/'.$id.'/3') }}" alt="">
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
    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
        <script src="{{ asset('js/spinner.js') }}"></script>
        <script>
            $('#from').on('change', function () {
                $('#to').datepicker('setStartDate', $('#from').val());
            });
        </script>
        <script>
            var uploadField = document.getElementById("document");

            uploadField.onchange = function() {
                if(this.files[0].size > 512000){
                alert("File is too big!");
                this.value = "";
                };
            };
        </script>
    {{-- <script src="{{ asset('dist/selectize.min.js') }}"></script>
    <script>
        var report_category_id = 28;
        $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
        var api = '{{ url("/document-upload/description/28") }}';
		$.get(api, function (data){
            if (data != '') {
                data.forEach(function (item){
                    $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                });
            }
        });

        $(function(){
            $("input[name='document[]']").attr('required', true);
        });
    </script> --}}
    @if(isset($forview))
    <script>
        $('#department_id').attr('disabled', 'disabled')
    </script>
    @endif
    @endpush
</x-app-layout>
