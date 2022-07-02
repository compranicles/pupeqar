<x-app-layout>
@section('title', 'Officerships & Memberships |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Officerships and/or Memberships</h3>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                @include('instructions')
                                <div class="table-responsive">
                                    <table class="table table-hover" id="officership_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Organization</th>
                                                <th>Position</th>
                                                <th>Inclusive Date</th>
                                                <th>Level</th>
                                                <th>Action</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($officershipFinal as $officership)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $officership->Organization }}</td>
                                                    <td>{{ $officership->Position }}</td>
                                                    <td>{{ $officership->IncDate }}</td>
                                                    <td>{{ $officership->Level }}</td>
                                                    <td>
                                                        @forelse ($officeReports as $officeReport)
                                                            @if ($officeReport->report_reference_id == $officership->EmployeeOfficershipMembershipID)
                                                                <a class="text-primary h4"><i class="fas fa-plus"></i> Add</a>
                                                                @break
                                                            @else
                                                                <a href="{{ route('submissions.officership.add', $officership->EmployeeOfficershipMembershipID) }}" class="text-primary h4"><i class="fas fa-plus"></i> Add</a>
                                                                @break
                                                            @endif
                                                        @empty
                                                            <a href="{{ route('submissions.officership.add', $officership->EmployeeOfficershipMembershipID) }}" class="text-primary h4"><i class="fas fa-plus"></i> Add</a>
                                                        @endforelse
                                                    </td>
                                                    <td>
                                                        @if ($officeReports != null)
                                                            @foreach ($officeReports as $officeReport)
                                                                @if ($officeReport->report_reference_id == $officership->EmployeeOfficershipMembershipID)
                                                                    <span class="badge bg-success">Submitted</span>
                                                                    <span class="badge bg-secondary">Quarter {{ $officeReport->report_quarter.' of '. $officeReport->report_year}}</span>
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
                $('#officership_table').DataTable({
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
