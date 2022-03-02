<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Community Relations and Outreach Program') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('outreach-program.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Community Relations and Outreach Program</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('outreach-program.store' ) }}" method="post">
                            @csrf
                            @include('form', ['formFields' => $outreachFields])
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
        <script src="{{ asset('dist/selectize.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.datepicker').datepicker({
                    autoclose: true,
                    format: 'mm/dd/yyyy',
                    immediateUpdates: true,
                    todayBtn: "linked",
                    todayHighlight: true
                });
            });
        </script>   
        <script>
            function validateForm() {
                var isValid = true;
                $('.form-validation').each(function() {
                    if ( $(this).val() === '' )
                        isValid = false;
                });
                return isValid;
            }
        </script>
        <script>
            var report_category_id = 22;
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