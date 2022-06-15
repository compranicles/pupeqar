<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __($research->research_code.' > Mark Research as Completed') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('research.navigation-bar', ['research_code' => $research->id, 'research_status' => $research->status])
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('research.completed.store', $research->id) }}" method="post">
                            @csrf
                            @include('form', ['formFields' => $researchFields, 'value' => $value])
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <a href="{{ route('research.show', $research->id) }}" class="btn btn-secondary mr-2">Cancel</a>
                                        <button type="submit" id="submit" class="btn btn-success">Save</button>
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
    <script>
        $(function() {
            $('#status').empty().append('<option selected="selected" value="{{ $researchStatus->id }}">{{ $researchStatus->name}}</option>');
            $('#status').attr('disabled', true);
            $('#completion_date').datepicker('setStartDate', "{{ date('m/d/Y', strtotime($value['start_date'])) }}");
        });
    </script>
    <script>
        // auto hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
    <script>
        var report_category_id = 2;
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