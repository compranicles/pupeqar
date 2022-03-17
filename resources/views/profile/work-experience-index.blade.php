<x-app-layout>
    <x-slot name="header">
        @include('profile.navigation')
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Work Experiences</h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="work_experience_table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Position</th>
                                                <th>Company</th>
                                                <th>Inclusive Dates</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($workExperiences as $experience)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $experience->Position }}</td>
                                                    <td>{{ $experience->Company }}</td>
                                                    <td>{{ $experience->IncDate }}</td>
                                                    <td>
                                                        <a href="{{ route('profile.workExperience.view', $experience->EmployeePreviousWorkID) }}" class="text-primary">View</a>
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
                $('#work_experience_table').DataTable({
                });
            } );
        </script>
    @endpush
</x-app-layout>