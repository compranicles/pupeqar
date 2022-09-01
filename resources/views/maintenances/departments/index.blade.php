<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Departments') }}
        </h2>
    </x-slot>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          @include('maintenances.navigation-bar')
        </div>
        <div class="col-md-12">
            <h2 class="font-weight-bold mb-2">Departments/Sections</h2>
        </div>
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                  <table class="table table-hover" id="department_table">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Department</th>
                              <th>Code</th>
                              <th>Sector</th>
                              <th>Office/College/Branch/Campus</th>
                              {{-- <th>Actions</th> --}}
                          </tr>
                      </thead>
                      <tbody>
                        @foreach ($departments as $department)
                        <tr>
                            <th>{{ $loop->iteration }}</th>
                            <td>{{ $department->name }}</td>
                            <td>{{ $department->code }}</td>
                            <td>{{ $department->sector_name }}</td>
                            <td>{{ $department->college_name }}</td>
                            {{-- <td>
                              <div role="group">
                                  <a href="{{ route('departments.edit', $department->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i></a>
                                  <button type="button" value="{{ $department->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-department="{{ $department->name }}"><i class="bi bi-trash" style="font-size: 1.25em;"></i></button>
                              </div>
                            </td> --}}
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

    {{-- Delete Modal --}}
      @include('maintenances.departments.delete')

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
          $('#department_table').DataTable({
            'aoColumnDefs': [{
                  'bSortable': false,
                  'aTargets': [-1], /* 1st one, start by the right */
                  'searchable': false
              }]
          });
        } );

        $('#department_table thead th.filter').each(function() {
          var title = $(this).text();
            if (title == 'Action') {
              return NULL;     
            }
        });
        
        //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var departmentName = button.getAttribute('data-bs-department')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = departmentName

          var url = '{{ route("departments.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('department_delete').action = url;
          
        });

      </script>
    @endpush
  </x-app-layout>
