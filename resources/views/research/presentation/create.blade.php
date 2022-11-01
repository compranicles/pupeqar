<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @section('title', 'Research/Book Chapter Presentation |')
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="font-weight-bold mr-2">Add Research/Book Chapter Presentation</h3>
                        <div class="mb-3">
                            <a class="back_link" href="{{ route('research.index') }}"><i class="bi bi-chevron-double-left"></i>Return to Research Main Page</a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('research.presentation.store', $research->id) }}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
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
        // auto hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 4000);
    </script>
    <script>
        $(function() {
            $('#status').empty().append('<option selected="selected" value="{{ $researchStatus->id }}">{{ $researchStatus->name}}</option>');
            $('#status').attr('disabled', true);
            $('#date_presented').datepicker('setStartDate', "{{ date('m/d/Y', strtotime($value['completion_date'])) }}"); //Set min. date
        });
    </script>
    <script>
        $('#date_presented').on('click', function(){
            $('#date_presented').prop("min", "{{ $research->completion_date }}");
        });
    </script>
    <script>
         var report_category_id = 4;
		$('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
		var apinb = '{{ url("/document-upload/description/4") }}';
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
