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
    @section('title', $nameOfPage.' | ')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                @if ($message = Session::get('cannot_access'))
                <div class="alert alert-danger alert-index">
                    {{ $message }}
                </div>
                @endif
                <div>
                    <h3 class="font-weight-bold mr-2">{{ $nameOfPage }}</h3>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <a class="mr-auto back_link ml-2" href="{{ route('special-tasks.index') }}"><i class="bi bi-chevron-double-left"></i>{{ __('Back to All '.$nameOfPages) }}</a>
                    <a href="{{ route('special-tasks.edit', $special_task->id) }}" class="action_buttons_show mr-3 ml-3"><i class="bi bi-pencil-square"></i> Edit</a>
                    <button type="button" value="{{ $special_task->id }}"  data-bs-request="{{ $special_task->final_output }}" class="action-delete action_buttons_show" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="bi bi-trash"></i> Delete</button>
                </div>
                @include('show', ['formFields' => $specialTaskFields, 'value' => $values])
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
                                    @if (count($documents) > 0)
                                        @foreach ($documents as $document)
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
                                    @if(count($documents) > 0)
                                        @foreach ($documents as $document)
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
                                    </div>
                                    @endif
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
        //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var requestTitle = button.getAttribute('data-bs-request')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = requestTitle

          var url = '{{ route("special-tasks.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;
          
        });
    </script>
    @endpush
</x-app-layout>