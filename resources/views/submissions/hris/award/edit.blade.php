<x-app-layout>
    @section('title', 'Outstanding Awards/Achievements |')
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @endif
                    <p>
                        <a class="back_link" href="{{ route('submissions.award.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Outstanding Awards/Achievements</a>
                    </p>
                    {{-- Denied Details --}}
                    @if ($deniedDetails = Session::get('denied'))
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-exclamation-circle"></i> Remarks: {{ $deniedDetails->reason }}
                    </div>
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('submissions.award.update', $id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @if (!isset($collegeOfDepartment))
                                    @include('form', ['formFields' => $awardFields, 'value' => $values])
                                @else
                                    @include('form', ['formFields' => $awardFields, 'value' => $values, 'colleges' => $colleges, 'collegeOfDepartment' => $collegeOfDepartment])
                                @endif
                                <div class="form-group">
                                    <label class="font-weight-bold" >Document</label>
                                    <br>
                                    <img src="{{ url('fetch_image/'.$id.'/2') }}" alt="">
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-0">
                                            <div class="d-flex justify-content-end align-items-baseline">
                                                <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Cancel</a>
                                                <button type="submit" id="submit" class="btn btn-success">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @push('scripts')
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
                if(this.files[0].size > 102400){
                alert("File is too big!");
                this.value = "";
                };
            };
        </script>
        {{-- <script>
            var report_category_id = 27;
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            var api = '{{ url("/document-upload/description/27") }}';
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
        @endpush
    </x-app-layout>
