<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Sector') }}
        </h2>
    </x-slot>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          @include('maintenances.navigation-bar')
        </div>

        <div class="col-md-12">
          @if ($message = Session::get('sync_success'))
            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
              <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
              </symbol>
            </svg>
            <div class="alert alert-success d-flex align-items-center" role="alert">
              <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
              <div class="ml-2">
                {{ $message }}
              </div>
            </div>            
          @endif 

          <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('sectors.maintenance.sync') }}" class="btn btn-success"><i class="fas fa-sync-alt"></i> Sync Sector, Office/College/Branch/Campus, and Department data</a>
                                <small class="text-sm ml-2"> Last Updated: {{ date("Y-m-d H:i:s", strtotime($sectors[0]->created_at)) }}</small>
                            </div>
                        </div>  
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-hover" id="sector_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Sector</th>
                                        <th>Code</th>
                                        {{-- <th>Actions</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sectors as $sector)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $sector->name }}</td>
                                        <td>{{ $sector->code }}</td>
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


    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
      <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);

        $(document).ready(function() {
          $('#sector_table').DataTable({
            'aoColumnDefs': [{
                  'bSortable': false,
                  'aTargets': [-1], /* 1st one, start by the right */
                  'searchable': false
              }]
          });
        } );

      </script>
    @endpush
  </x-app-layout>
