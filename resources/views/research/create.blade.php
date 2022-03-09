<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Research Registration') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('research.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Research</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('research.store') }}" method="post" id="create_research">
                            @csrf
                            @include('form', ['formFields' => $researchFields, 'colleges' => $colleges])
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <a href="{{ route('research.index') }}" class="btn btn-secondary mr-2">Cancel</a>
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
        <script src="{{ asset('dist/selectize.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>   
        <script src="{{ asset('js/views/research.js') }}"></script>   
        <script>
            $(function () {
                $('#start_date').attr('disabled', true);
                $('#target_date').attr('disabled', true);
                $('#funding_agency').attr('disabled', true);
                $('#funding_agency').removeClass('form-validation');
                if ("{{ auth()->user()->middle_name }}" == '')
                {
                    $('#researchers').val("{{ auth()->user()->last_name.' '.auth()->user()->first_name.' '.substr(auth()->user()->middle_name,0,1).'.' }}");
                }
                else
                {
                    $('#researchers').val("{{ auth()->user()->last_name.' '.auth()->user()->first_name.' '.substr(auth()->user()->middle_name,0,1).'.' }}");
                }
                $('#researchers').attr('readonly', true);
            });
        </script>
        <script>
            $('#nature_of_involvement').on('change', function (){
                $('#nature_of_involvement option[value=12]').attr('disabled',true);
                $('#nature_of_involvement option[value=13]').attr('disabled',true);
                $('#nature_of_involvement').removeClass('form-validation'); 
            });
        </script>
        <script>
            var report_category_id = 1;
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