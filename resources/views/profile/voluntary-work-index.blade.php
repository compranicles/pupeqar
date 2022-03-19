<x-app-layout>
    <x-slot name="header">
        @include('profile.navigation')
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Voluntary Works</h3>
                        <hr>
                        <!-- FOREACH (If the user has more than 1 work) -->
                       <div class="row">
                           <div class="col-md-12">
                               <div class="table-responsive">
                                   <table class="table table-hover" id="voluntary_work_table">
                                       <thead>
                                           <tr>
                                               <th></th>
                                               <th>Position</th>
                                               <th>Organization</th>
                                               <th>Organization Address</th>
                                               <th>Inclusive Dates</th>
                                               <th>No. of Hours</th>
                                           </tr>
                                       </thead>
                                       <tbody>
                                           @foreach ($voluntaryWorks as $works)
                                           <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $works->Position }}</td>
                                                <td>{{ $works->Organization }}</td>
                                                <td>{{ $works->Address }}</td>
                                                <td>{{ date('m/d/Y', strtotime($works->IncDateFrom)).' - '.date('m/d/Y', strtotime($works->IncDateTo)) }}</td>
                                                <td>{{ $works->NumberOfHours }}</td>
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
                $('#voluntary_work_table').DataTable({
                });
            } );
        </script>
    @endpush
</x-app-layout>