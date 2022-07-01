<x-app-layout>
    @section('title', 'Inter-Country Mobility |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mr-2">Add Inter-Country Mobility</h3>
                </div>
                <div class="mb-3">
                    <a class="back_link" href="{{ route('mobility.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Inter-Country Mobility</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('mobility.store') }}" method="post" class="needs-validation" novalidate>
                            <div class="mt-2 mb-3">
                                <i class="bi bi-pencil-square mr-1"></i><strong>Instructions: </strong> Please fill in the required information with the symbol (<strong style="color: red;">*</strong>)
                            </div> 
                            <hr>
                            @csrf
                            @include('form', ['formFields' => $mobilityFields, 'colleges' => $colleges,  'colaccomp' => 1])
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
            $('#classification_of_person').on('change', function() {
                if ({!! json_encode($is_dean) !!} == null || {!! json_encode($is_chair) !!} == null) {
                    $('#classification_of_person option[value=298]').attr('disabled', 'disabled');
                }
            });
        </script>
        <script>
            $('#start_date').on('change', function () {
                $('#end_date').datepicker('setStartDate', $('#start_date').val());
            });
        </script>
        <script>
            $("#classification_of_mobility").selectize({
                maxItems: 5,
                delimiter: ",",
                persist: true,
                create: function (input) {
                    return {
                    value: input,
                    text: input,
                    };
                },
            });
        </script>
        <script>
            $("#nature_of_engagement").selectize({
                maxItems: 5,
                delimiter: ",",
                persist: true,
                create: function (input) {
                    return {
                    value: input,
                    text: input,
                    };
                },
            });
        </script>
        <script>
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get("{{ url('document-upload/description/14') }}", function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        </script>
        <script>
            setTimeout(function (){
                $('#classification_of_mobility').empty().append('<option selected="selected" disabled="disabled" value=""></option>');
                $.get("{{ url('dropdowns/options/35') }}", function (data){
                    if (data != '') {
                        data.forEach(function (item){
                            $("#classification_of_mobility")[0].selectize.addOption({value:item.name, text:item.name});
                        });
                    }
                });
            }, Math.floor(Math.random() * (2500 - 1) + 1));
        </script>
        <script>
            setTimeout(function (){
                $('#nature_of_engagement').empty().append('<option selected="selected" disabled="disabled" value=""></option>');
                $.get("{{ url('dropdowns/options/34') }}", function (data){
                    if (data != '') {
                        data.forEach(function (item){
                            $("#nature_of_engagement")[0].selectize.addOption({value:item.name, text:item.name});
                        });
                    }
                });
            }, Math.floor(Math.random() * (2500 - 1) + 1));
        </script>
    @endpush
</x-app-layout>
