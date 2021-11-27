<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('View Expert Service Rendered in Academics') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
            <div class="d-flex mr-2">
                <p class="mr-auto">
                  <a class="back_link" href="{{ route('faculty.expert-service-in-academic.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Expert Services in Academic Journals, Books, Publication, Newsletter, Creative Works</a>
                </p>
                <p>
                  <a href="{{ route('faculty.expert-service-in-academic.edit', $expert_service_in_academic->id) }}" class="action_buttons_show mr-3">Edit</a>
                </p>
                <p>
                  <button type="button" value="{{ $expert_service_in_academic->id }}" class="action-delete action_buttons_show" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                </p>
              </div>
                <div class="card">
                    <div class="card-body card_show">
                        <div class="table-responsive">
                          <table class="table table-borderless show_table">
                              <tr>
                                <th>Classification of Expert Service Rendered </th>
                                <td>{{ $classification[0]->name }}</td>
                              </tr>
                              <tr>
                                <th>Nature of Service Rendered</th>
                                <td>{{ $nature[0]->name }}</td>
                              </tr>
                              <tr>
                                <th>Publication/Audio Visual Production</th>
                                <td>{{ $expert_service_in_academic->publication_or_audio_visual }}</td>
                              </tr>
                              <tr>
                                <th>Indexing</th>
                                @if(empty($indexing[0]->name))
                                  <td>None</td>
                                @else
                                  <td>{{ $indexing[0]->name }}</td>
                                @endif
                              </tr>
                              <tr>
                                <th>Inclusive Date</th>
                              </tr>
                              <tr>
                                <td class="padding_left_show">From</td>
                                <td>{{ $expert_service_in_academic->from }}</td>
                              </tr>
                              <tr>
                                <td class="padding_left_show">To</td>
                                <td>{{ $expert_service_in_academic->to }}</td>
                              </tr>
                              <tr>
                                <th>Level</th>
                                <td>{{ $level[0]->name }}</td>
                              </tr>
                              <tr>
                                <th>College/Campus/Branch/Office where you Committed the Accomplishment</th>
                                <td>{{ $collegeAndDepartment[0]->college_name }}</td>
                              </tr>
                              <tr>
                                <th>Department where you Committed the Accomplishment</th>
                                <td>{{ $collegeAndDepartment[0]->department_name }}</td>
                              </tr>
                              <tr>
                                <th>Description of Supporting Documents</th>
                                <td>{{ $expert_service_in_academic->description }}</td>
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
                                @if (count($expertServiceAcademicDocuments) > 0)
                                  @foreach ($expertServiceAcademicDocuments as $document)
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
                              @if(count($expertServiceAcademicDocuments) > 0)
                                @foreach ($expertServiceAcademicDocuments as $document)
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

  
</x-app-layout>