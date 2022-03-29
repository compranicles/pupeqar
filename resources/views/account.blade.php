<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('User Account') }}
        </h2>
    </x-slot>

    <div class="container">
        {{-- Success Message --}}
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-index action-alert">
            <i class="bi bi-check-circle"></i> {{ $message }}
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Employee Code</label>
                                    <input type="text" readonly class="form-control" value="{{ $accountDetail[0]->EmpCode }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Plantilla Position</label>
                                    <input type="text" readonly class="form-control" value="{{ $accountDetail[0]->PlantillaPosition }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                        <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="">Surname</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail[0]->LName }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="">First Name</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail[0]->FName }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="">Middle Name</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail[0]->MName }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group input-group-sm">
                                    <label for="">Suffix</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail[0]->EName }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Role</label>
                                    <input type="text" id="role" readonly class="form-control" value="{{ $roles[0] }}">
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('account.signature.save') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Signature </label>
                                        <input type="file" 
                                        class="{{ $errors->has('document') ? 'is-invalid' : '' }} filepond mb-n1"
                                        name="document[]"
                                        id="document"
                                        data-max-file-size="50MB"
                                        data-max-files="50"
                                        />
                                        <p class="mt-1"><small>Accepts JPEG, and PNG file formats.</small></p>
                                        <button type="submit" id="submit" class="btn btn-success float-right">Save</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if (!$user->signature == '')
                                        @if(preg_match_all('/image\/\w+/', \Storage::mimeType('documents/'.$user->signature)))
                                        
                                        <div class="card bg-light border border-maroon rounded-lg">
                                            <a href="{{ route('document.display', $user->signature) }}" data-lightbox="gallery" data-title="{{ $user->signature }}" target="_blank">
                                                <img src="{{ route('document.display', $user->signature) }}" class="card-img-top img-resize"/>
                                            </a>
                                        </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-12">
                <h2 class="h4 font-weight-bold">
                    Sector, College/Branch/Campus/Office, and Department
                </h2>
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-inline mr-2">
                                <a href="{{ route('offices.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add</a>
                            </div>
                        </div>  
                        <hr>
                        @forelse($employeeSectorsCbcoDepartment as $row)
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Sector</label>
                                    <input type="text" readonly class="form-control" value="{{ $row->sectorName }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="">College</label>
                                    <input type="text" readonly class="form-control" value="{{ $row->collegeName }}">
                                </div>
                            </div>
                            <div class="col-md-2" style="padding-top: 35px;" role="group">
                                <div class="form-group input-group-sm">
                                    <a href="{{ route('offices.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i></a>
                                    <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-sector="{{ $row->sectorName }}" data-bs-cbco="{{ $row->collegeName }}" data-bs-dept="{{ $row->departmentName }}"><i class="bi bi-trash" style="font-size: 1.25em;"></i></button>
                                </div>
                            </div>
                        </div>
                        @empty
                            <div class="col-md-12">
                                <div class="alert alert-success text-center p-5" role="alert">
                                    <h5>
                                        Add College/Branch/Campus/Office Where You Are Reporting.
                                    </h5> 
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    <h5 class="text-center">Are you sure you want to remove this in your account?</h5>
                    <p id="itemToDelete1" class="text-center font-weight-bold"></p>
                    <p id="itemToDelete2" class="text-center font-weight-bold"></p>
                    <form action="" id="delete_item" method="POST">
                        @csrf
                        @method('delete')
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger mb-2 mr-2">Delete</button>
            </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('dist/selectize.min.js') }}"></script>
    <script>
        $("#role").selectize({
        delimiter: ",",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
        });
        $("#role")[0].selectize.lock();
    </script>
    <script>
        window.setTimeout(function() {
            $(".action-alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
    <script>
        //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var sector = button.getAttribute('data-bs-sector')
          var cbco = button.getAttribute('data-bs-cbco')
          var itemToDelete1 = deleteModal.querySelector('#itemToDelete1')
          var itemToDelete2 = deleteModal.querySelector('#itemToDelete2')
          itemToDelete1.textContent = "Sector: " + sector
          itemToDelete2.textContent = "College/Branch/Campus/Office: " + cbco

          var url = '{{ route("offices.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;
        });
    </script>
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
            acceptedFileTypes: ['image/jpeg', 'image/png'],
            
            server: {
                process: {
                    url: "/upload",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                },
            }
        });
    </script>
    @endpush
</x-app-layout>