<x-app-layout> 
    <div class="container">
        <div class="row">
            <div class="d-flex col-md-12">
                <h5> {{ $user['first_name'].' '.($user['middle_name'] == '' ? '' : $user['middle_name']).' '.$user['last_name'] }}</h5> <p class="ml-3">{{$userRoleNames}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 8px;">
                    <div class="d-flex" style="padding: 2.40em 2em 2.40em 2em">
                        <div class="db-icon">
                            <i class="bi bi-calendar3 home-icons"></i>
                        </div>
                        <div class="ml-auto">
                            <h5 class="text-right">Quarter {{ isset($currentQuarterYear->current_quarter) ? $currentQuarterYear->current_quarter : '' }} of {{ isset($currentQuarterYear->current_year) ? $currentQuarterYear->current_year : '' }}</h5>
                            @if (in_array(5, $roles))
                                @foreach ($department[5] as $values)
                                    <small class="text-right">{{ $values->code }}</small>
                                @endforeach
                            @elseif (in_array(6, $roles))
                                @foreach ($college[6] as $values)
                                    <small class="text-right">{{ $values->code }}</small>
                                @endforeach
                            @elseif (in_array(7, $roles))
                                <small class="text-right">{{ $sector[7]->code }}</small>
                            @else
                                <small class="text-right">Reporting Period</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if (in_array(1, $roles) || in_array(3, $roles))
                @include('dashboard.faculty-admin')
            @endif
            @if (in_array(10, $roles))
                @foreach ($department[10] as $value)
                    @include('dashboard.researcher', ['countReviewed1' => $countReviewed1[10][$value->department_id], 'countReviewed2' => $countReviewed2[10][$value->department_id]])
                @endforeach
            @endif
            @if (in_array(11, $roles))
                @foreach ($department[11] as $value)
                    @include('dashboard.extensionist', ['countReviewed1' => $countReviewed1[11][$value->department_id]])
                @endforeach
            @endif
            @if (in_array(5, $roles))
                @foreach ($department[5] as $value)
                    @include('dashboard.chairperson', ['countReviewed1' => $countReviewed1[5][$value->department_id], 'department_id' => $value->department_id])
                @endforeach
            @endif
            @if (in_array(6, $roles))
                @foreach ($college[6] as $value)
                    @include('dashboard.director', ['countReviewed1' => $countReviewed1[6][$value->college_id], 'college_id' => $value->college_id])
                @endforeach
            @endif
            @if (in_array(7, $roles))
                @include('dashboard.sector-head')
            @endif
            @if (in_array(8, $roles) || (in_array(8, $roles) && in_array(9, $roles)))
                @include('dashboard.ipqmso')
            @endif
            @if (in_array(9, $roles) && !in_array(8, $roles))
                @include('dashboard.superadmin')
            @endif
        </div>

        <div class="row">
            
            @if (in_array(8, $roles) || in_array(9, $roles))
            <div class="col-md-8 mb-4">
                <div class="card">
                <h5 class="card-header">Activity Log <small class="ml-2"><a href="{{ route('logs.all') }}" class="home-card-links" style="color: #5b0616;">View all.</a></small></h5>   
                    <div class="card-body">
                        <table class="table table-sm table-borderless fixed_header" id="log_activity_table" style="height: 15rem;">
                            <tbody>
                            </tbody>
                            <p class="align-middle text-center no-data-message">No recent logs to show.</p>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-8">
                <div class="card">
                <h5 class="card-header">Recent Activity <small class="ml-2"><a href="{{ route('logs.user') }}" class="home-card-links" style="color: #5b0616;">View all.</a></small></h5>   
                    <div class="card-body">
                        <table class="table table-sm table-borderless fixed_header" id="log_activity_individual_table" style="height: 15rem;">
                            <tbody>
                            </tbody>
                            <p class="align-middle text-center no-data-message">No recent activities to show.</p>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                        <h5 class="card-header">Announcements</h5>
                            <div class="card-body">
                                <table style="background-color: white;">
                                    @php $i = 0; @endphp
                                    @forelse($announcements as $announcement)
                                    <thead>
                                        <tr>
                                            <th role="button" class="font-weight-bold home-titles" style="{{ $i == 0 ? ' ' : 'border-top: 1px solid #dee2e6; padding-top: 10px;' }}"
                                                    data-bs-toggle="modal" data-bs-target="#announcementModal" data-bs-subject="{{ $announcement->subject }}"
                                                    data-bs-message="{{ $announcement->message }}" data-bs-date="{{ date( 'F j, Y', strtotime($announcement->updated_at)) }}">
                                                <small style="color: var(--gray-dark)">{{ date( "F j, Y", strtotime($announcement->updated_at)) }}</small>
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
    <script>
        $(function(){
            getLog();
            getLogInd();

            setInterval(getLog, 60000);
            setInterval(getLogInd, 60000);
        });

        function getLog(){
            $('.activity-log-content').remove();

            $.get('/get-dashboard-list', function (data){
                var countColumns = 0;

                data.forEach(function(item){
                    $('.no-data-message').remove();
                    $('#log_activity_table').append('<tr id="activity-log-'+countColumns+'" class="activity-log-content"></tr>');
                    $('#activity-log-'+countColumns)
                        .append('<td class="activity-log-content text-small border-bottom"><i class="bi bi-square-fill mr-2" style="color: #00a7d1;"></i>'+
                                item.subject
                            +'<div class="text-muted ml-4"><small>'+item.name+' &#183; '+item.created_at+'</small></div></td>'
                        );
                    countColumns++;
                });
            });
        }
        function getLogInd(){
            $('.activity-log-indi-content').remove();

            $.get('/get-dashboard-list-indi', function (data){
                var countColumns = 0;

                data.forEach(function(item){
                    $('.no-data-message').remove();
                    $('#log_activity_individual_table').append('<tr id="activity-log-indi-'+countColumns+'" class=" activity-log-indi-content"></tr>');
                    $('#activity-log-indi-'+countColumns)
                        .append('<td class="activity-log-indi-content text-small border-bottom"><i class="bi bi-square-fill mr-2" style="color: #00a7d1;"></i>'+
                                item.subject
                            +'<div class="text-muted ml-4"><small>'+item.created_at+'</small></div></td>'
                        );
                    countColumns++;
                });
            });
        }
    </script>
    @endpush
</x-app-layout>
