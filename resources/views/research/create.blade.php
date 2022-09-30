<x-app-layout>
    @section('title', 'Research & Book Chapter |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mr-2">Register Research/Book Chapter</h3>
                <div class="mb-3">
                    <a class="back_link" href="{{ route('research.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Research</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('research.store') }}" method="post" id="create_research" class="needs-validation" novalidate>
                            <div class="mt-2 mb-3">
                                <i class="bi bi-pencil-square mr-1"></i><strong>Instructions: </strong> Please fill in the necessary details. No abbreviations. All inputs with the symbol (<strong style="color: red;">*</strong>) are required. Tag your co-researchers to share them what you encoded.
                            </div>
                            <hr>
                            @csrf
                            <div class="form-group">
                                <label class="font-weight-bold" for="collaborators-tagging">Tag your co-researchers (Tagging PUP employees who use the eQAR system only).</label><br>
                                <span class="form-notes">If you are independent researcher, leave it blank.</span>
                                <select name="tagged_collaborators[]" id="tagged-collaborators" class="form-control custom-select">
                                    <option value="" selected>Choose...</option>
                                </select>
                            </div>
                            @include('form', ['formFields' => $researchFields, 'colleges' => $colleges])
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <a href="{{ route('research.index') }}" class="btn btn-secondary mr-2">Cancel</a>
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
        <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
        <script src="{{ asset('js/spinner.js') }}"></script>
        <script>
            $(function () {
                var middle = '';
                if ("{{auth()->user()->middle_name}}" != '') {
                    middle = "{{ substr(auth()->user()->middle_name,0,1).'.' }}";
                }
                var fullname = "{{ auth()->user()->last_name.', '.auth()->user()->first_name.' ' }}" + middle;
                $("#researchers")[0].selectize.addOption({value:fullname, text:fullname});
                $("#researchers")[0].selectize.addItem(fullname);
                $('#tagged-collaborators')[0].selectize.removeOption("{{auth()->id()}}");
            });
        </script>
        <script>
            $('#start_date').on('change', function () {
                $('#target_date').datepicker('setStartDate', $('#start_date').val());
            });
        </script>
         <script>
            $("#tagged-collaborators").selectize({
              maxItems: null,
              valueField: 'id',
              labelField: 'fullname',
              sortField: "fullname",
              searchField: "fullname",
              options: @json($allUsers),
            //   onChange: function(value) {
            //     var selected = $("#tagged-collaborators option:selected").text();
            //     const arraySelected = selected.replaceAll(".", "./");
            //     if($("#tagged-collaborators option:selected").length > 1) {
            //         $('#researchers').val(arraySelected);
            //     } else {
            //         $('#researchers').val(selected);
            //     }
            //   }
          });

        
        </script>
        <script>
            // $('#nature_of_involvement').on('load', function (){
                $('#nature_of_involvement option[value=12]').attr('disabled','disabled');
                $('#nature_of_involvement option[value=13]').attr('disabled','disabled');
                $('#nature_of_involvement').removeClass('form-validation');
            // });

            $('#nature_of_involvement').on('change', function (){
                if ($('#nature_of_involvement').val() == 224)  {
                    // if independent researcher
                    $('#tagged-collaborators')[0].selectize.lock(); //input for tagging will be disabled
                    $('#tagged-collaborators')[0].selectize.clear(); // clears the value if any
                }
                else
                    $('#tagged-collaborators')[0].selectize.unlock();
            });
        </script>
        <script>
            $('#funding_type').on('change', function (){
                if ($(this).val() == 23) {
                    //Univ. Funded
                    $('#funding_agency').val("Polytechnic University of the Philippines");
                    $('#funding_agency').removeAttr('disabled');
                    $('#funding_agency').attr('required', true);
                }
                else if ($(this).val() == 24) {
                    //Self Funded
                    $('#funding_agency').val("");
                    $('#funding_agency').attr('disabled', true);
                    $('#funding_agency').removeAttr('required');
                }
                else if ($(this).val() == 25) { // External Funded
                    $('#funding_agency').val("");
                    $('#funding_agency').removeAttr('disabled');
                    $('#funding_agency').attr('required', true);
                }
            });
        </script>
        <script>
            $('#status option[value=28]').attr('disabled','disabled');
            $('#status option[value=29]').attr('disabled','disabled');
            $('#status option[value=30]').attr('disabled','disabled');
            $('#status option[value=31]').attr('disabled','disabled');
            $('#status option[value=32]').attr('disabled','disabled'); //Deferred
            $('#status').on('change', function(){

                if ($(this).val() == 26) { //New Commitment
                    $('#start_date').val("");
                    $('#start_date').attr('disabled', true);
                    $('#start_date').removeAttr('required');
                    $('#target_date').val("");
                    $('#target_date').attr('disabled', true);
                    $('#target_date').removeAttr('required');
                }
                else if ($(this).val() == 27) { // Ongoing
                    $('#start_date').removeAttr('disabled');
                    $('#start_date').attr('required', true);
                    $('#target_date').removeAttr('disabled');
                    $('#target_date').attr('required', true);
                    $('#start_date').focus();
                }
            });
        </script>
        <script>
             var report_category_id = 1;
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            var apinb = '{{ url("/document-upload/description/1") }}';
            setTimeout(function (){
            $.get(apinb, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            }); }, 2000);
        </script>
    @endpush
</x-app-layout>
