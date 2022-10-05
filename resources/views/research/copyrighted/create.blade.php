<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __($research->research_code.' > Mark Research as Copyrighted') }}
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
                        <form action="{{ route('research.copyrighted.store', $research->id) }}" method="post" class="needs-validation" novalidate>
                            @csrf
                            @include('quarter-field')
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
    <script src="{{ asset('js/spinner.js') }}"></script>
    <script>
        $(function() {
            $('#copyright_year').datepicker('setStartDate', "{{ date('Y', strtotime($value['completion_date'])) }}"); //Set min. date
        });
    </script>
    <script>
        $('#copyright_year').datepicker({
            format: " yyyy", // Notice the Extra space at the beginning
            viewMode: "years",
            minViewMode: "years"
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
         var report_category_id = 7;
		$('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
		var apinb = '{{ url("/document-upload/description/7") }}';
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
