<x-app-layout>
    @section('title', 'Extension Program/Project/Activity |')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
              @if ($message = Session::get('cannot_access'))
                <div class="alert alert-danger alert-index">
                    {{ $message }}
                </div>
              @endif
              <div>
                <h3 class="font-weight-bold mr-2">Extension Program/Project/Activity</h3>
                <div class="d-flex align-items-center mb-2">
                    <a class="mr-auto back_link ml-2" href="{{ route('student-award.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Student Awards and Recognition</a>
                    @if ($extensionRole == '1')
                      <a href="{{ route('extension.invite.index', $extension_service->id) }}" class="btn btn-primary btn-sm mr-3 ml-3">
                        Add Co-Extensionists
                      </a>
                    @endif
                    <a href="{{ route('extension-service.edit', $extension_service->id) }}" class="action_buttons_show mr-3 ml-3"><i class="bi bi-pencil-square"></i> Edit</a>
                    <button type="button" value="{{ $extension_service->id }}" class="action-delete action_buttons_show" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="bi bi-trash"></i> Delete</button>
                </div>
              </div>
              @if ($extensionRole == '1')
              <div class="alert alert-info" role="alert">
                Add your co-extensionist/s in this extension to share them this info.
              </div>
              @endif
              <div class="row">
                <div class="col-md-12">
                  @include('extension-service-show', ['formFields' => $extensionServiceFields, 'value' => $values])
                </div>
              </div>
                <div class="row mt-3">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5 id="textHome" style="color:maroon">Supporting Documents</h5>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <h6 style="color:maroon"><i class="far fa-file-alt mr-2"></i>Documents</h6>
                              <div class="row">
                                @if (count($extensionServiceDocuments) > 0)
                                  @foreach ($extensionServiceDocuments as $document)
                                    @if(preg_match_all('/application\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                      <div class="col-md-12 mb-3" id="doc-{{ $document['id'] }}">
                                        <div class="card bg-light border border-maroon rounded-lg">
                                          <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <div class="embed-responsive embed-responsive-1by1">
                                                        <iframe  src="{{ route('document.view', $document['filename']) }}" width="100%" height="500px"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                  
                                          </div>
                                        </div>
                                      </div>
                                    @endif
                                  @endforeach
                                @else
                                <div class="col-md-4 offset-md-4">
                                  <h6 class="text-center">No Documents Attached</h6>
                                </div>
                                @endif
                              </div>
                          </div>
                          <div class="col-md-6">
                            <h6 style="color:maroon"><i class="far fa-image mr-2"></i>Images</h6>
                            <div class="row">
                              @if(count($extensionServiceDocuments) > 0)
                                @foreach ($extensionServiceDocuments as $document)
                                  @if(preg_match_all('/image\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                    <div class="col-md-6 mb-3" id="doc-{{ $document['id'] }}">
                                      <div class="card bg-light border border-maroon rounded-lg">
                                        <a href="{{ route('document.display', $document['filename']) }}" data-lightbox="gallery" data-title="{{ $document['filename'] }}" target="_blank">
                                            <img src="{{ route('document.display', $document['filename']) }}" class="card-img-top img-resize"/>
                                        </a>
                                      </div>
                                    </div>
                                  @endif
                                @endforeach
                              @else
                              <div class="col-md-4 offset-md-4">
                                  <h6 class="text-center">No Documents Attached</h6>
                              </div>
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    @include('delete')

    @push('scripts')
    <script>
       var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var eServiceTitle = '{{ ($values['title_of_extension_program'] != null ? $values['title_of_extension_program'] : ($values['title_of_extension_project'] != null ? $values['title_of_extension_project'] : ($values['title_of_extension_activity'] != null ? $values['title_of_extension_activity'] : ''))) }}'
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = eServiceTitle

          var url = '{{ route("extension-service.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;
          
        });
    </script>
    @endpush

</x-app-layout>