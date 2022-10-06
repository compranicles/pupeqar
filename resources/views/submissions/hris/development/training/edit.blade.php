<x-app-layout>
    @section('title', 'Trainings |')
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="font-weight-bold mb-2">Edit Training</h2>
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
                        <a class="back_link" href="{{ route('submissions.development.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Trainings and Seminars</a>
                    </p>
                     {{-- Denied Details --}}
                     @if ($deniedDetails = Session::get('denied'))
                     <div class="alert alert-info" role="alert">
                         <i class="bi bi-exclamation-circle"></i> Remarks: {{ $deniedDetails->reason }}
                     </div>
                     @endif
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('submissions.development.training.update', $id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                @if (!isset($collegeOfDepartment))
                                    @include('form', ['formFields' => $trainingFields, 'value' => $values])
                                @else
                                    @include('form', ['formFields' => $trainingFields, 'value' => $values, 'colleges' => $colleges, 'collegeOfDepartment' => $collegeOfDepartment])

                                @endif
                                <hr>
                                <div class="form-group">
                                    <label class="font-weight-bold" >Document</label>
                                    <br>
                                    <h6>Special Order (S.O)</h6>
                                    @if ($values['mimeTypeSO'] == null && $values['documentSO'] == null)
                                        <h6 class="ml-3 mt-3">None </h6>
                                    @endif
                                    @if($values['mimeTypeSO'] == 'application/pdf')
                                        <iframe  src="{{ url('fetch_images/'.$values['id'].'/5/1') }}" width="100%" height="500px"></iframe>
                                    @else
                                        <div class="img-container">
                                            <img src="{{ url('fetch_images/'.$values['id'].'/5/1') }}" alt="">
                                        </div>
                                    @endif
                                    <br>
                                    <h6>Certificate of Participation/Attendance/Completion</h6>
                                    @if ($values['mimeTypeCert'] == null && $values['documentCert'] == null)
                                        <h6 class="ml-3 mt-3">None</h6>
                                    @endif
                                    @if($values['mimeTypeCert'] == 'application/pdf')
                                        <iframe  src="{{ url('fetch_images/'.$values['id'].'/5/2') }}" width="100%" height="500px"></iframe>
                                    @else
                                        <div class="img-container">
                                            <img src="{{ url('fetch_images/'.$values['id'].'/5/2') }}" alt="">
                                        </div>
                                    @endif
                                    <br>
                                    <h6>Compiled Photos in 1 PDF File</h6>
                                    @if ($values['mimeTypePic'] == null && $values['documentPic'] == null)
                                        <h6 class="ml-3 mt-3">None</h6>
                                    @endif
                                    @if($values['mimeTypePic'] == 'application/pdf')
                                        <iframe  src="{{ url('fetch_images/'.$values['id'].'/5/3') }}" width="100%" height="500px"></iframe>
                                    @else
                                        <div class="img-container">
                                            <img src="{{ url('fetch_images/'.$values['id'].'/5/3') }}" alt="">
                                        </div>
                                    @endif
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
            var uploadFieldSO = document.getElementById("documentSO");
            var uploadFieldCert = document.getElementById("documentCert");
            var uploadFieldPic = document.getElementById("documentPic");

            uploadFieldSO.onchange = function() {
                if(this.files[0].size > 512000){
                alert("File is too big! File must not exceed to 500KB.");
                this.value = "";
                };
            };

            uploadFieldCert.onchange = function() {
                if(this.files[0].size > 512000){
                alert("File is too big! File must not exceed to 500KB.");
                this.value = "";
                };
            };

            uploadFieldPic.onchange = function() {
                if(this.files[0].size > 512000){
                alert("File is too big! File must not exceed to 500KB.");
                this.value = "";
                };
            };
        </script>
        @if(isset($forview))
        <script>
            $('#department_id').attr('disabled', 'disabled')
        </script>
        @endif
        @endpush
    </x-app-layout>
