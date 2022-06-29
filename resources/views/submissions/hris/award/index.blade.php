<x-app-layout>
    <x-slot name="header">
        @include('submissions.hris.navigation')
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Outstanding Awards and Achievements</h3>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="award_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Award</th>
                                                <th>Award Giving Body</th>
                                                <th>Date</th>
                                                <th>Level</th>
                                                <th>Action</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($awardFinal as $award)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $award->Achievement }}</td>
                                                    <td>{{ $award->AwardedBy }}</td>
                                                    <td>{{ $award->Date }}</td>
                                                    <td>{{ $award->Level }}</td>
                                                    <td>
                                                        @if ($awardReports != null)
                                                            @foreach ($awardReports as $awardReport)
                                                                @if ($awardReport->report_reference_id == $award->EmployeeOutstandingAchievementID)
                                                                    <a class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Add</a>
                                                                    @break
                                                                @else
                                                                    <a href="{{ route('submissions.award.add', $award->EmployeeOutstandingAchievementID) }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Add</a>
                                                                    @break
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($awardReports != null)
                                                            @foreach ($awardReports as $awardReport)
                                                                @if ($awardReport->report_reference_id == $award->EmployeeOutstandingAchievementID)
                                                                    <span class="badge bg-success">Submitted</span>
                                                                    <span class="badge bg-secondary">Quarter {{ $awardReport->report_quarter.' of '. $awardReport->report_year}}</span>
                                                                    @break
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
        <script>

            $(document).ready( function () {
                $('#award_table').DataTable({
                });
            } );
            // auto hide alert
            window.setTimeout(function() {
                $(".temp-alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);
        </script>
    @endpush

</x-app-layout>
