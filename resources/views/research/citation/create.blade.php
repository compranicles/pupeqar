<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Research Citation') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('research.navigation-bar', ['research_code' => $research->research_code, 'research_status' => $research->status])
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('research.citation.store', $research['research_code']) }}" method="post">
                            @csrf
                            @include('research.form', ['formFields' => $researchFields, 'value' => $research])
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <button type="submit" id="submit" class="btn btn-success">Submit</button>
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
        $(function() {
            $('#link-to-register').show();
            $('#link-to-utilize').show();

            $('#link-to-complete').show();
            $("#link-to-publish").show();
            $("#link-to-present").show();
            $("#link-to-copyright").show();
            $("#link-to-cite").show();
        });

        if ( {{$research->status}} ==26 ){
            $('.research-tabs').remove();
        }

        else if ({{ $research->status }} == 27) {
            if ({{ $utilized }} == 0) {
                // $('#link-to-register').show();
                // $('#link-to-utilize').show();
                $('#link-to-utilize').remove();
            }
            else {
                $('.research-tabs').remove();
            }
        }

        else if ({{ $research->status }} == 28) {
            $("#link-to-cite").remove();

            if ({{ $published }} == 0) {
                $("#link-to-publish").remove();
            }

            if ({{ $presented }} == 0) {
                $("#link-to-present").remove();
            }

            if ({{ $copyrighted }} == 0) {
                $("#link-to-copyright").remove();
            }

            if ({{ $utilized }} == 0) {
                $("#link-to-utilize").remove();
            }
        }

        else if ({{ $research->status }} == 29) {
            $("#link-to-cite").remove();

            if ({{ $published }} == 0) {
                $("#link-to-publish").remove();
            }

            if ({{ $copyrighted }} == 0) {
                $("#link-to-copyright").remove();
            }

            if ({{ $utilized }} == 0) {
                $("#link-to-utilize").remove();
            }
        }

        else if ({{ $research->status }} == 30) {
            if ({{ $presented }} == 0) {
                $("#link-to-present").remove();
            }

            if ({{ $copyrighted }} == 0) {
                $("#link-to-copyright").remove();
            }

            if ({{ $cited }} == 0) {
                $("#link-to-cite").remove();
            }

            if ({{ $utilized }} == 0) {
                $("#link-to-utilize").remove();
            }
        }

        else if ({{$research->status}} == 31) {
            if ({{ $copyrighted }} == 0) {
                $("#link-to-copyright").remove();
            }

            if ({{ $cited }} == 0) {
                $("#link-to-cite").remove();
            }

            if ({{ $utilized }} == 0) {
                $("#link-to-utilize").remove();
            }
        }
    </script>
    @endpush
</x-app-layout>