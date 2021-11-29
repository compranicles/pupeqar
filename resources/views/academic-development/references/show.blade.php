<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('View '.$category[0]->name) }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
              <div class="d-flex mr-2">
                <p class="mr-auto">
                  <a class="back_link" href="{{ route('faculty.rtmmi.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Reference, Textbook, Module, Monographs, and Instructional Materials</a>
                </p>
                <p>
                  <a href="{{ route('faculty.rtmmi.edit', $rtmmi->id) }}" class="action_buttons_show mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                </p>
                <p>
                  <button type="button" value="{{ $rtmmi->id }}" class="action-delete action_buttons_show" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="bi bi-trash"></i> Delete</button>
                </p>
              </div>
                <div class="card">
                    <div class="card-body card_show">
                        <div class="table-responsive">
                          <table class="table table-borderless show_table">
                              <tr>
                                <th>{{ $referenceFields[0]->label }}</th>
                                <td>{{ $rtmmi[$referenceFields[0]->name] }}</td>

                              </tr>
                              <tr>
                                <th>Authors/Compilers</th>
                                <td>{{ $rtmmi->authors_compilers }}</td>

                              </tr>
                              <tr>
                                <th>Category</th>
                                <td>{{ $category[0]->name }}</td>
                              </tr>
                              <tr>
                                <th>Level</th>
                                <td>{{ $level[0]->name }}</td>
                              </tr>
                              <tr>
                                <th>Date Started</th>
                                <td>{{ $rtmmi->date_started }}</td>
                              </tr>
                              <tr>
                                <th>Date Completed</th>
                                <td>{{ $rtmmi->date_completed }}</td>

                              </tr>
                              <tr>
                                <th>Name of Editor/Referee</th>
                                <td>{{ $rtmmi->editor_name }}</td>

                              </tr>
                              <tr>
                                <th>Profession of Editor/Referee</th>
                                <td>{{ $rtmmi->editor_profession }}</td>

                              </tr>
                              <tr>
                                <th>Volume No.</th>
                                <td>{{ $rtmmi->volume_no }}</td>

                              </tr>
                              <tr>
                                <th>Issue No.</th>
                                <td>{{ $rtmmi->issue_no }}</td>

                              </tr>
                              <tr>
                                <th>Date of Publication</th>
                                <td>{{ $rtmmi->date_published }}</td>

                              </tr>
                              <tr>
                                <th>Copyright Registration No.</th>
                                <td>{{ $rtmmi->copyright_regi_no }}</td>

                              </tr>
                              <tr>
                                <th>Colleges/Campus/Branch/Office to commit the Accomplishment</th>
                                <td>{{ $collegeAndDepartment[0]->college_name }}</td>

                              </tr>
                              <tr>
                                <th>Department to commit the Accomplishment</th>
                                <td>{{ $collegeAndDepartment[0]->department_name }}</td>

                              </tr>
                              <tr>
                                <th>Description of Supporting Documents</th>
                                <td>{{ $rtmmi->description }}</td>
                              </tr>
                          </table>
                        </div>
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
                                @if (count($referenceDocuments) > 0)
                                  @foreach ($referenceDocuments as $document)
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
                              @if(count($referenceDocuments) > 0)
                                @foreach ($referenceDocuments as $document)
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
        $(function() {
            var categoryId = "{{ $rtmmi->category }}";
            $.get('/dropdowns/options/'+categoryId, function (data){

              console.log(data);
                
            });
        
        });
      </script>
    @endpush
  
</x-app-layout>