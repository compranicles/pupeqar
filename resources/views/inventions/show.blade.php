<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('View Invention, Innovation, or Creative Works') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
            <div class="d-flex mr-2">
                <p class="mr-auto">
                  <a class="back_link" href="{{ route('faculty.invention-innovation-creative.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Inventions, Innovation and Creative Works</a>
                </p>
                <p>
                  <a href="{{ route('faculty.invention-innovation-creative.edit', $invention_innovation_creative->id) }}" class="action_buttons_show mr-3">Edit</a>
                </p>
                <p>
                  <button type="button" value="{{ $invention_innovation_creative->id }}" class="action-delete action_buttons_show" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                </p>
              </div>
                <div class="card">
                    <div class="card-body card_show">
                        <div class="table-responsive">
                          <table class="table table-borderless show_table">
                              <tr>
                                  <th>Classification</th><td>{{ $classification->name }}</td>
                              </tr>
                              <tr>
                                <th>Nature (IT Products, Equipments, Machinery, etc.)</th>
                                <td>{{ $invention_innovation_creative->nature }}</td>
                              </tr>
                              <tr>
                                <th>Title</th>
                                <td>{{ $invention_innovation_creative->title }}</td>
                              </tr>
                              <tr>
                                <th>Name of Collaborators</th>
                                <td>{{ $invention_innovation_creative->collaborator }}</td>
                              </tr>
                              <tr>
                                <th>Type of Funding</th>
                                <td>{{ $funding_type->name }}</td>

                              </tr>
                              <tr>
                                <th>Amount of Funding</th>
                                <td>{{ $invention_innovation_creative->funding_amount }}</td>

                              </tr>
                              <tr>
                                <th>Funding Agency</th>
                                <td>{{ $invention_innovation_creative->funding_agency }}</td>

                              </tr>
                              <tr>
                                <th>Status</th>
                                <td>{{ $status->name }}</td>

                              </tr>
                              <tr>
                                <th>Project Date Started</th>
                                <td>{{ $invention_innovation_creative->start_date }}</td>

                              </tr>
                              <tr>
                                <th>Project Date Ended</th>
                                <td>{{ $invention_innovation_creative->end_date }}</td>
                              </tr>
                              <tr>
                                <th>Utilization of Invention</th>
                                <td>{{ $invention_innovation_creative->utilization }}</td>

                              </tr>
                              <tr>
                                <th>Copyright/Patent No.</th>
                                <td>{{ $invention_innovation_creative->copyright_number }}</td>

                              </tr>
                              <tr>
                                <th>Date of Issue</th>
                                <td>{{ $invention_innovation_creative->issue_date }}</td>

                              </tr>
                              <tr>
                                <th>Description of Supporting Documents</th>
                                <td>{{ $invention_innovation_creative->description }}</td>

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
                                @if (count($inventionDocuments) > 0)
                                  @foreach ($inventionDocuments as $document)
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
                              @if(count($inventionDocuments) > 0)
                                @foreach ($inventionDocuments as $document)
                                  @if(preg_match_all('/image\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                    <div class="col-md-6 mb-3" id="doc-{{ $document['id'] }}">
                                      <div class="card bg-light border border-maroon rounded-lg">
                                        <a href="{{ route('document.display', $document['filename']) }}" data-lightbox="gallery" data-title="{{ $document['filename'] }}">
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