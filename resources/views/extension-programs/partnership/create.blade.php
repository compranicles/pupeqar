<x-app-layout>
    @section('title', 'Partnership, Linkages & Network |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mr-2">Add Partnership, Linkages & Network</h3>
                <div class="mb-3">
                    <a class="back_link" href="{{ route('partnership.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Partnership, Linkages & Network</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('partnership.store' ) }}" method="post" class="needs-validation" novalidate>
                            <div class="mt-2 mb-3">
                                <i class="bi bi-pencil-square mr-1"></i><strong>Instructions: </strong> Please fill in the required information with the symbol (<strong style="color: red;">*</strong>)
                            </div> 
                            <hr>
                            @csrf
                            @include('form', ['formFields' => $partnershipFields, 'colleges' => $colleges])
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
        <script src="{{ asset('js/spinner.js') }}"></script>
        <script>
            $('#start_date').on('change', function () {
                $('#end_date').datepicker('setStartDate', $('#start_date').val());
            });
        </script>
        <script>
            $("#collab_nature").selectize({
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
            $("#partnership_type").selectize({
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
            $("#deliverable").selectize({
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
            var report_category_id = 13;
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get("{{ url('document-upload/description/13') }}", function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        </script>
        <script>
            var dropdown_id = 30;
            setTimeout(function (){
                $('#collab_nature').empty().append('<option selected="selected" disabled="disabled" value=""></option>');
                $.get("{{ url('dropdowns/options/30') }}", function (data){
                    if (data != '') {
                        data.forEach(function (item){
                            $("#collab_nature")[0].selectize.addOption({value:item.name, text:item.name});
                        });
                    }
                });
            }, Math.floor(Math.random() * (2500 - 1) + 1));
        </script>
        <script>
            var dropdown_id = 31;
            setTimeout(function (){
                $('#partnership_type').empty().append('<option selected="selected" disabled="disabled" value=""></option>');
                $.get("{{ url('dropdowns/options/31') }}", function (data){
                    if (data != '') {
                        data.forEach(function (item){
                            $("#partnership_type")[0].selectize.addOption({value:item.name, text:item.name});
                        });
                    }
                });
            }, Math.floor(Math.random() * (2500 - 1) + 1));
        </script>
         <script>
            var dropdown_id = 32;
            setTimeout(function (){
                $('#deliverable').empty().append('<option selected="selected" disabled="disabled" value=""></option>');
                $.get("{{ url('dropdowns/options/32') }}", function (data){
                    if (data != '') {
                        data.forEach(function (item){
                            $("#deliverable")[0].selectize.addOption({value:item.name, text:item.name});
                        });
                    }
                });
            }, Math.floor(Math.random() * (2500 - 1) + 1));
        </script>
    @endpush
</x-app-layout>
