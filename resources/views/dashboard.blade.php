<x-app-layout>
        <div class="row">
            <div class="d-flex col-md-12">
                <h5> {{ $user['first_name'].' '.($user['middle_name'] == '' ? '' : $user['middle_name']).' '.$user['last_name'] }}</h5> <p class="ml-3">{{$userRoleNames}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card mb-4">
                    <h5 class="card-header font-weight-bold">
                        <i class="bi bi-calendar3 mr-2" style="color: #820001;"></i>
                        Quarter {{ isset($currentQuarterYear->current_quarter) ? $currentQuarterYear->current_quarter : '' }} 
                        of {{ isset($currentQuarterYear->current_year) ? $currentQuarterYear->current_year : '' }} 
                        <?php
                            $deadline = strtotime( $currentQuarterYear->deadline );
                            $deadline = date( 'F d, Y', $deadline);
                        ?>
                        <small class="ml-2">Deadline: <strong>{{ $deadline }}</strong></small>
                    </h5>
                    <div class="card-body">
                        <div class="row px-4">
                            @if (in_array(1, $roles) || in_array(3, $roles))
                                @include('dashboard.faculty-admin')
                            @endif
                            @if (in_array(5, $roles))
                            <div class="db-col mb-2">
                                <div class="db-card">
                                    <h5 class="card-header text-center">Chair/Chief</h5>
                                    <div class="card-body d-flex justify-content-center">
                                        @foreach ($department[5] as $value)
                                            @include('dashboard.chairperson', ['countToReview' => $countToReview[5][$value->department_id], 'departmentID' => $value->department_id, 'departmentCode' => $value->code])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if (in_array(10, $roles))
                            <div class="db-col mb-2">
                                <div class="db-card">
                                    <h5 class="card-header text-center">Researcher</h5>
                                    <div class="card-body d-flex justify-content-center">
                                        @foreach ($department[10] as $value)
                                            @include('dashboard.researcher', ['countToReview' => $countToReview[10][$value->college_id], 'collegeCode' => $value->code ])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if (in_array(11, $roles))
                            <div class="db-col mb-2">
                                <div class="db-card">
                                    <h5 class="card-header text-center">Extensionist</h5>
                                    <div class="card-body d-flex justify-content-center">
                                        @foreach ($department[11] as $value)
                                            @include('dashboard.extensionist', ['countToReview' => $countToReview[11][$value->college_id], 'collegeCode' => $value->code ])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if (in_array(6, $roles) || in_array(12, $roles))
                            <div class="db-col mb-2">
                                <div class="db-card">
                                    @if (in_array(12, $roles))
                                    <h5 class="card-header text-center">Associate/Assistant <br> Dean/Director</h5>
                                    @else
                                    <h5 class="card-header text-center">Dean/Director</h5>
                                    @endif
                                    <div class="card-body d-flex justify-content-center">
                                        @foreach ($college[6] as $value)
                                            @include('dashboard.director', ['countToReview' => $countToReview[6][$value->college_id], 'collegeID' => $value->college_id, 'collegeCode' => $value->code])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if (in_array(7, $roles) || in_array(13, $roles))
                            <div class="db-col mb-2">
                                <div class="db-card">
                                    @if (in_array(13, $roles))
                                    <h5 class="card-header text-center">Assistant to VP</h5>
                                    @else
                                    <h5 class="card-header text-center">Sector Head</h5>
                                    @endif
                                    <div class="card-body d-flex justify-content-center">
                                        @foreach ($sector[7] as $value)
                                            @include('dashboard.sector-head', ['countToReview' => $countToReview[7][$value->sector_id], 'sectorCode' => $value->code])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if (in_array(8, $roles))
                                <div class="db-col mb-2">
                                    <div class="db-card">
                                        <h5 class="card-header text-center">IPO</h5>
                                        <div class="card-body d-flex justify-content-center">@include('dashboard.ipqmso', ['countToReview' => $countToReview[8], 
                                            'countExpectedTotal' => $countExpectedTotal[8], 'countReceived' => $countReceived[8]])
                                        </div>
                                    </div>
                                </div>       
                            @endif
                            @if (in_array(9, $roles))
                                @include('dashboard.superadmin', ['countRegisteredUsers' => $countRegisteredUsers[9]])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                        <h5 class="card-header"><i class="bi bi-megaphone-fill mr-2" style="color: #820001;"></i><strong>Announcements</strong></h5>
                            <div class="card-body">
                                <table style="background-color: white;">
                                    @php $i = 0; @endphp
                                    @forelse($announcements as $announcement)
                                    <thead>
                                        <tr>
                                            <th role="button" style="color: #373E45; {{ $i == 0 ? ' ' : 'border-top: 1px solid #dee2e6; padding-top: 8px;' }}"
                                                    data-bs-toggle="modal" data-bs-target="#announcementModal" data-bs-subject="{{ $announcement->subject }}"
                                                    data-bs-message="{{ $announcement->message }}" data-bs-date="{{ date( 'F j, Y', strtotime($announcement->updated_at)) }}">
                                                <small class="mb-2" style="color: var(--gray-dark)">{{ date( "F j, Y", strtotime($announcement->updated_at)) }}</small> &#8226;
                                                <small class="mb-2">{{ $diff = Carbon\Carbon::parse($announcement->updated_at)->diffForHumans() }}</small>
                                                <br>
                                                {{$announcement->subject}}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="home-report-list" style="padding-bottom: 10px;"><small>{{substr_replace($announcement->message, "...", 100)}}</small></td>
                                        </tr>
                                    </tbody>
                                    @php $i++; @endphp
                                    @empty
                                        <p class="align-middle text-center">No announcements to show.</p>
                                    @endforelse
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="announcementModalLabel">Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-md-11">

                            <h5 class="modal-subject font-weight-bold">Announcement</h5>
                            <h6 class="modal-date"></h6>
                            <br>
                            <p id="message"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-2" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>

    @push('scripts')
    <script>
        //Announcement to display in modal
        var announcementModal = document.getElementById('announcementModal')
        announcementModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var subject = button.getAttribute('data-bs-subject')
          var date = button.getAttribute('data-bs-date')
          var message = button.getAttribute('data-bs-message')
          var modalTitle = announcementModal.querySelector('.modal-subject')
          var modalSubTitle = announcementModal.querySelector('.modal-date')
          var modalBody = announcementModal.querySelector('#message')
          modalTitle.textContent = subject
          modalSubTitle.textContent = "Date Announced: " + date
          modalBody.textContent = message
        });
    </script>
    @endpush
</x-app-layout>
