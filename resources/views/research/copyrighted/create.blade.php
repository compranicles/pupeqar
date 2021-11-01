<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Copyrighted Research ') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('research.navigation-bar', ['research_code' => $research->research_code])
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('research.copyrighted.store', $research->research_code) }}" method="post">
                            @csrf
                            @include('research.form', ['formFields' => $researchFields, 'value' => $research])
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
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
    <script>
        $(function() {
            $('textarea').val('');
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
        if ({{ $research->status }} == 28) {

            $("#link-to-publish").remove();
            $("#link-to-present").remove();
            $("#link-to-copyright").remove();
            $("#link-to-cite").remove();
        }
        else if ({{ $research->status }} == 29) {
            $("#link-to-publish").remove();
            $("#link-to-copyright").remove();
        }
        else if ({{ $research->status }} == 30) {
            $("#link-to-present").remove();
            $("#link-to-copyright").remove();
        }
        else if ({{ $research->status }} == 31) {
            $("#link-to-copyright").remove();
        } 
    </script>
    <script>
        function hide_dates() {
            $('.start_date').hide();
            $('.target_date').hide();
        }

        $(function() {
            hide_dates();
        });

    </script>
    <script>
        
        var statusId = $('#status').val();
        if (statusId == 26) {
            hide_dates();

            $('#start_date').prop("required", false);
            $('#target_date').prop("required", false);
        }
    </script>
@endpush
</x-app-layout>