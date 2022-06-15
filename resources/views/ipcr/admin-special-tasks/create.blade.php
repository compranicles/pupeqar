<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Special Task') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('admin-special-tasks.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Special Tasks</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin-special-tasks.store' ) }}" method="post">
                            @csrf
                            @include('form', ['formFields' => $specialTaskFields])
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
    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <script>
        $('#from').on('change', function () {
            $('#to').datepicker('setDate', $('#from').val());
            $('#to').datepicker('setStartDate', $('#from').val());
        });
    </script>
    <script>
        var report_category_id = 28;
        $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
        
        setTimeout(() => {
            var urlre = "{{ url('document-upload/description/28') }}";
            $.get(urlre, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        }, 2000);
        
    </script>
    @endpush
</x-app-layout>