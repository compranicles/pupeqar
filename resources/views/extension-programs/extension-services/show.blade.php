<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('View Extension Service') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
            <p>
              <a class="back_link" href="{{ route('faculty.extension-service.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Extension Services</a>
            </p>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                          <table class="table table-borderless show_table">
                              <tr>
                                <th>Title of Extension Program</th>
                                <td>{{ $extension_service->title_of_extension_program }}</td>
                              </tr>
                              <tr>
                                <th>Title of Extension Project</th>
                                <td>{{ $extension_service->title_of_extension_project }}</td>
                              </tr>
                              <tr>
                                <th>Title of Extension Activity</th>
                                <td>{{ $extension_service->title_of_extension_activity }}</td>
                              </tr>
                              <tr>
                                <th>Classification of Extension Activity</th>
                                <td>{{ $classification[0]->name }}</td>
                              </tr>
                              <tr>
                                <th>Type of Extension Activity</th>
                                <td>{{ $type[0]->name }}</td>
                              </tr>
                              <tr>
                                <th>Partnership Level</th>
                                <td>{{ $level[0]->name }}</td>
                              </tr>
                              <tr>
                                <th>Status</th>
                                <td>{{ $status[0]->name }}</td>
                              </tr>
                              <tr>
                                <th>Nature of Involvement</th>
                                <td>{{ $nature_of_involvement[0]->name }}</td>
                              </tr>
                              @if ($type_of_funding[0]->name != 'Self Funded')
                              <tr>
                                <th>Funding Agency</th>
                                <td>{{ $extension_service->funding_agency }}</td>
                              </tr>
                              @endif
                              <tr>
                                <th>Type of Funding</th>
                                <td>{{ $type_of_funding[0]->name }}</td>
                              </tr>
                              <tr>
                                <th>Amount of Funding</th>
                                <td>{{ $extension_service->currency }} {{ $extension_service->amount_of_funding }}</td>
                              </tr>
                              <tr>
                                <th>Project Duration</th>
                              </tr>
                              <tr>
                                <td class="padding_left_show">From</th>
                                <td>{{ $extension_service->from }}</td>
                              </tr>
                              <tr>
                                <td class="padding_left_show">To</td>
                                <td>{{ $extension_service->to }}</td>
                              </tr>
                              <tr>
                                <th>No. of Trainees or Beneficiaries</th>
                                <td>{{ $extension_service->no_of_trainees_or_beneficiaries }}</td>
                              </tr>
                              <tr>
                                <th>Classification of Trainees or Beneficiaries</th>
                                <td>{{ $extension_service->classification_of_trainees_or_beneficiaries }}</td>
                              </tr>
                              <tr>
                                <th>Keywords</th>
                                <td>{{ $extension_service->keywords }}</td>
                              </tr>
                              <tr>
                                <th>Total No. of Hours</th>
                                <td>{{ $extension_service->total_no_of_hours }}</td>
                              </tr>
                              <tr>
                                  <th>No. of Trainees or Beneficiaries who Rated the <em>Quality</em> of the Extension Service</th>
                              </tr>
                              <tr>
                                <td class="padding_left_show">Poor</td>
                                <td>{{ $extension_service->quality_poor }}</td>
                              </tr>
                              <tr>
                                <td class="padding_left_show">Fair</td>
                                <td>{{ $extension_service->quality_fair }}</td>
                              </tr>
                              <tr>
                                <td class="padding_left_show">Satisfactory</td>
                                <td>{{ $extension_service->quality_satisfactory }}</td>
                              </tr>
                              <tr>
                                <td class="padding_left_show">Very Satisfactory</td>
                                <td>{{ $extension_service->quality_very_satisfactory }}</td>
                              </tr>
                              <tr>
                                <td class="padding_left_show">Outstanding</td>
                                <td>{{ $extension_service->quality_outstanding }}</td>
                              </tr>
                              <tr>
                                <th>No. of Trainees or Beneficiaries who Rated the <em>Timeliness</em> of the Extension Service</th>
                              </tr>
                              <tr>
                                <td class="padding_left_show">Poor</td>
                                <td>{{ $extension_service->timeliness_poor }}</td>
                              </tr>
                              <tr>
                                <td class="padding_left_show">Fair</td>
                                <td>{{ $extension_service->timeliness_fair }}</td>
                              </tr>
                              <tr>
                                <td class="padding_left_show">Satisfactory</td>
                                <td>{{ $extension_service->timeliness_satisfactory }}</td>
                              </tr>
                              <tr>
                                <td class="padding_left_show">Very Satisfactory</td>
                                <td>{{ $extension_service->timeliness_very_satisfactory }}</td>
                              </tr>
                              <tr>
                                <td class="padding_left_show">Outstanding</td>
                                <td>{{ $extension_service->timeliness_outstanding }}</td>
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
                                <td>{{ $extension_service->description }}</td>
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