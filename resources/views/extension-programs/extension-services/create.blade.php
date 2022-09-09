<x-app-layout>
    @section('title', 'Extension Programs/Projects/Activities |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mr-2">Add Extension Program/ Project/Activity</h3>
                <div class="mb-3">
                    <a class="back_link" href="{{ route('extension-service.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Extension Programs/Projects/Activities</a>
                </div>
                <div class="alert alert-info" role="alert">
                    You can still tag other extension partner/s in this extension after you save this extension.
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('extension-service.store' ) }}" method="post" class="needs-validation" novalidate>
                            <div class="mt-2 mb-3">
                                <i class="bi bi-pencil-square mr-1"></i><strong>Instructions: </strong> Please fill in the necessary details. No abbreviations. All inputs with the symbol (<strong style="color: red;">*</strong>) are required. Tag your extension partners to share them what you encoded.
                            </div>    
                            <hr>
                            @csrf
                            <div class="form-group">
                                <label class="font-weight-bold" for="collaborators-tagging">Tag your extension partners/persons participated in the extension (Tagging PUP employees who use the eQAR system only).</label><br>
                                <span class="form-notes">If none, leave it blank.</span>
                                <select name="tagged_collaborators[]" id="tagged-collaborators" class="form-control custom-select">
                                    <option value="" selected>Choose...</option>
                                </select>
                            </div>
                            @include('extension-programs.extension-services.form', ['formFields' => $extensionServiceFields, 'colleges' => $colleges])
                            @include('extension-programs.extension-services.no-of-beneficiaries', ['value' => ''])
                            @include('extension-programs.extension-services.form2', ['formFields' => $extensionServiceFields])
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
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('dist/selectize.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>z
        <script src="{{ asset('js/spinner.js') }}"></script>
        <script>
            $(function() {
                $('#status').val(105);
                $('#tagged-collaborators')[0].selectize.removeOption("{{auth()->id()}}");
            });
        </script>
        <script>
            $('#type_of_funding').on('change', function (){
                if ($(this).val() == 123) {
                    //Univ. Funded
                    $('#funding_agency').val("Polytechnic University of the Philippines");
                    $('#funding_agency').removeAttr('disabled');
                    $('#funding_agency').attr('required', true);
                }
                else if ($(this).val() == 124) {
                    //Self Funded
                    $('#funding_agency').val("");
                    $('#funding_agency').attr('disabled', true);
                    $('#funding_agency').removeAttr('required');
                }
                else if ($(this).val() == 125) { // External Funded
                    $('#funding_agency').val("");
                    $('#funding_agency').removeAttr('disabled');
                    $('#funding_agency').attr('required', true);
                }
            });
        </script>

        <script>
            var report_category_id = 12;
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get("{{ url('document-upload/description/12') }}", function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        </script>
         <script>
            $("#tagged-collaborators").selectize({
              maxItems: null,
              valueField: 'id',
              labelField: 'fullname',
              sortField: "fullname",
              options: @json($allUsers),
          });
        </script>
    @endpush
</x-app-layout>
