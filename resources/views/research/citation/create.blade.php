<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __($research['research_code'].' > Add Research Citation') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('research.navigation-bar', ['research_code' => $research['id'], 'research_status' => $research['status']])
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form id="create_form" action="{{ route('research.citation.store', $research['id']) }}" method="post" class="needs-validation" novalidate>
                            @csrf
                            @include('form', ['formFields' => $researchFields, 'value' => $research])
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <a href="{{ route('research.citation.index', $research['id']) }}" class="btn btn-secondary mr-2">Cancel</a>
                                        <button type="submit" id="submit" class="btn btn-success mr-2">Save</button>
                                        <button type="submit" id="submit_save" class="btn btn-primary">Save and Submit</button>
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
            $('#year').datepicker({
                format: " yyyy", // Notice the Extra space at the beginning
                viewMode: "years",
                minViewMode: "years"
            });
        </script>
        <script>
            var report_category_id = 5;
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            var apijk = '{{ url("/document-upload/description/5") }}';
			setTimeout(function (){
			$.get(apijk, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            }); }, 2000);
        </script>
        <script>
            $('#submit_save').on('click', function () {
                $('#create_form').attr('action', "{{ route('research.citation.store', $research['id']) }}"+"?o=submit");
                $('#create_form').submit();
            });
        </script>
    @endpush
</x-app-layout>
