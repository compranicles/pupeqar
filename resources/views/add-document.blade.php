<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Adding Documents') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">BACK</a>
                                <hr>
                            </div>
                        </div>
                        <form action="{{ route('submissions.faculty.savedoc', [$id, $report_category_id]) }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Documents</label><span style='color: red'></span>

                                        <input type="file"
                                            class="filepond mb-n1"
                                            name="document[]"
                                            id="document"
                                            multiple
                                            data-max-file-size="5MB"
                                            data-max-files="10">
                                        <p class="mt-1"><small>Maximum size per file: 5MB. Maximum number of files: 10.</small></p>
                                        <p class="mt-n4"><small>Accepts PDF, JPEG, and PNG file formats.</small></p>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
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
    <script>
        FilePond.registerPlugin(

            // encodes the file as base64 data
            FilePondPluginFileEncode,

            // validates the size of the file
            FilePondPluginFileValidateSize,

            // corrects mobile image orientation
            FilePondPluginImageExifOrientation,

            // previews dropped images
            FilePondPluginImagePreview,
            FilePondPluginFileValidateType,

        );
        // Create a FilePond instance
        const pondDocument = FilePond.create(document.querySelector('input[name="document[]"]'));
        pondDocument.setOptions({
            acceptedFileTypes: ['application/pdf', 'image/jpeg', 'image/png'],

            server: {
                process: {
                    url: "{{ url('upload') }}",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                },
            }
        });
    </script>
    @endpush
</x-app-layout>
