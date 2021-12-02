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
          @if ($message = Session::get('edit_department_success'))
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
                                <a href="{{ route('departments.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Department</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="table-responsive">
                            <table class="table" id="department_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departments as $department)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $department->name }}</td>
                                        <td>
                                          <div role="group">
                                              <a href="{{ route('departments.edit', $department->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                              <button type="button" value="{{ $department->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-department="{{ $department->name }}"><i class="bi bi-trash"></i> Delete</button>
                                          </div>
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
