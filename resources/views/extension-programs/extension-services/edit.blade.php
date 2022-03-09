<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit Extension Service') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('extension-service.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Extension Services</a>
                </p>
                {{-- Denied Details --}}
                @if ($deniedDetails = Session::get('denied'))
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-exclamation-circle"></i> Remarks: {{ $deniedDetails->reason }}
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('extension-service.update', $value['id'] ) }}" method="post">
                            @csrf
                            @method('put')
                            @include('extension-programs.extension-services.form', ['formFields' => $extensionServiceFields, 'value' => $value, 'colleges' => $colleges, 'collegeOfDepartment' => $collegeOfDepartment])
                            @include('extension-programs.extension-services.no-of-beneficiaries', ['value' => $value])
                            @include('extension-programs.extension-services.form2', ['formFields' => $extensionServiceFields, 'value' => $value])
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
                        </form>
                    </div>
                </div>
                <div class="row mt-3" id="documentsSection">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <h5 id="textHome" style="color:maroon">Supporting Documents</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 style="color:maroon"><i class="far fa-file-alt mr-2"></i>Documents</h6>
                                        <div class="row">
                                            @if (count($extensionServiceDocuments) > 0)
                                                @foreach ($extensionServiceDocuments as $document)
                                                    @if(preg_match_all('/application\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                                        <div class="col-md-12 mb-3 documents-display" id="doc-{{ $document['id'] }}">
                                                            <div class="card bg-light border border-maroon rounded-lg">
                                                                <div class="card-body">
                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <div class="embed-responsive embed-responsive-1by1">
                                                                                <iframe  src="{{ route('document.view', $document['filename']) }}" width="100%" height="500px"></iframe>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <button class="btn btn-danger remove-doc" data-id="doc-{{ $document['id'] }}" data-link="{{ route('extension-service.removedoc', $document['filename']) }}" data-toggle="modal" data-target="#deleteModal">Delete</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                                <div class="col-md-4 offset-md-4 docEmptyMessage" style="display: none;">
                                                    <h6 class="text-center">No Documents Attached</h6>
                                                </div>
                                            @else
                                                <div class="col-md-4 offset-md-4">
                                                    <h6 class="text-center">No Documents Attached</h6>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 style="color:maroon"><i class="far fa-image mr-2"></i>Images</h6>
                                        <div class="row">
                                            @if(count($extensionServiceDocuments) > 0)
                                                @foreach ($extensionServiceDocuments as $document)
                                                    @if(preg_match_all('/image\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                                        <div class="col-md-6 mb-3 documents-display" id="doc-{{ $document['id'] }}">
                                                            <div class="card bg-light border border-maroon rounded-lg">
                                                                <a href="{{ route('document.display', $document['filename']) }}" data-lightbox="gallery" data-title="{{ $document['filename'] }}" target="_blank">
                                                                    <img src="{{ route('document.display', $document['filename']) }}" class="card-img-top img-resize"/>
                                                                </a>
                                                                <div class="card-body">
                                                                    <table class="table table-sm my-n3 text-center">
                                                                        <tr>
                                                                            <th>
                                                                                <button class="btn btn-danger remove-doc" data-id="doc-{{ $document['id'] }}" data-link="{{ route('extension-service.removedoc', $document['filename']) }}" data-toggle="modal" data-target="#deleteModal">Delete</button>
                                                                            </th>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                            @endforeach
                                        <div class="col-md-4 offset-md-4 docEmptyMessage" style="display: none;">
                                            <h6 class="text-center">No Documents Attached</h6>
                                        </div>
                                        @else
                                            <div class="col-md-4 offset-md-4">
                                                <h6 class="text-center">No Documents Attached</h6>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- Delete doc Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center">Are you sure you want to delete this document?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger mb-2 mr-2" id="deletedoc">Delete</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('dist/selectize.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>   

        <script>
            var other_classification = document.getElementById("other_classification");
            $('#classification').on('input', function(){
                var classification_name = $("#classification option:selected").text();
                if (classification_name == "Others") {
                    $('#other_classification').attr('required', true);
                    $('#other_classification').focus();
                }
                else {
                    $('#other_classification').removeAttr('required');
                    $('#other_classification').val('');

                }
            });

            var other_classification_of_trainees = document.getElementById("other_classification_of_trainees");
            $('#classification_of_trainees_or_beneficiaries').on('input', function(){
                var classification_trainees_name = $("#classification_of_trainees_or_beneficiaries option:selected").text();
                if (classification_trainees_name == "Others") {
                    $('#other_classification_of_trainees').attr('required', true);
                    $('#other_classification_of_trainees').focus();
                }
                else {
                    $('other_classification_of_trainees').removeAttr('required');
                    $('#other_classification_of_trainees').val('');

                }
            });
        </script>
        <script>
            var url = '';
            var docId = '';
            $('.remove-doc').on('click', function(){
                url = $(this).data('link');   
                docId = $(this).data('id');
            });
            $('#deletedoc').on('click', function(){
                $.get(url, function (data){
                    $('#deleteModal .close').click();
                    $('#'+docId).remove();

                    $('<div class="alert alert-success mt-3">Document removed successfully.</div>')
                        .insertBefore('#documentsSection')
                        .delay(3000)
                        .fadeOut(function (){
                            $(this).remove();
                        });

                    var docCount = $('.documents-display').length
                    if(docCount == 0){
                        $('.docEmptyMessage').show();
                    }
                });
            });
        </script>
        <script>
            $('#from').on('input', function(){
                var date = new Date($('#from').val());
                if (date.getDate() <= 9) {
                        var day = "0" + date.getDate();
                }
                else {
                    var day = date.getDate();
                }

                var month = date.getMonth() + 1;
                if (month <= 9) {
                    month = "0" + month;
                }
                else {
                    month = date.getMonth() + 1;
                }
                var year = date.getFullYear();
                // alert([day, month, year].join('-'));
                // document.getElementById("target_date").setAttribute("min", [day, month, year].join('-'));
                document.getElementById('to').setAttribute('min', [year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
                $('#to').val([year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
            });

            $('#type_of_funding').on('change', function (){
                var type = $(this).val();
                if(type == 123){
                    
                    $('#funding_agency').val('Polytechnic University of the Philippines');
                    $('#funding_agency').attr('disabled', true);
                    $('#funding_agency').addClass('form-validation');
                }
                else if(type == 124){
                    $('#funding_agency').attr('disabled', true);
                    $('#funding_agency').removeClass('form-validation');
                }
                else if(type == 125){
                    $('#funding_agency').removeAttr('readonly');
                    $('#funding_agency').removeAttr('disabled');
                    $('#funding_agency').val('');
                    $('#funding_agency').addClass('form-validation');
                }
            });

            $('#keywords').on('keyup', function(){
                // var value = $(this).val();
                var value = $(this).val().replace(/ /g,'');
                var words = value.split(",");
                words = words.filter(function(e){return e});
                // console.log(words);
                if(words.length < 5){
                    $("#validation-keywords").text('The number of keywords must be five (5)');
                }
                else if (words.length >= 5){
                    $("#validation-keywords").text('');
                }
                else if( words == null){
                    $("#validation-keywords").text('The number of keywords must be five (5)');
                }
            });
            

            // function validateForm() {
            //     var isValid = true;
            //     $('.form-validation').each(function() {
            //         if ( $(this).val() === '' )
            //             isValid = false;
            //     });
            //     return isValid;
            // }
        </script>
        <script>
            var report_category_id = 12;
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get('/document-upload/description/'+report_category_id, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>