@php
    $nameOfPage = '';
    $nameOfPages = '';
    if(in_array(1, $roles)){
        $nameOfPage = 'Special Task';
        $nameOfPages = 'Special Tasks';
    }
    elseif (in_array(3, $roles)) {
        $nameOfPage = 'Accomplishment Based on OPCR';
        $nameOfPages = 'Accomplishments Based on OPCR';
    }
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add '.$nameOfPage) }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('special-tasks.index') }}"><i class="bi bi-chevron-double-left"></i>{{ __('Back to All '.$nameOfPages) }}</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('special-tasks.store') }}" method="post">
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
        var report_category_id = 29;
        $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
        
        setTimeout(() => {
            var urlre = "{{ url('document-upload/description/29') }}";
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