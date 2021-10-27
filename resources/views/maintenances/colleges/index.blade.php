<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Colleges') }}
        </h2>
    </x-slot>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          @include('maintenances.navigation-bar')
        </div>

        <div class="col-md-12">
        <!--<div class="col-md-10 float-none m-0 m-auto"> -->
          <h2 class="mb-2">Colleges</h2>

          <p class="mb-3">
            <a href="{{ route('admin.colleges.create') }}" class="btn btn-md btn-primary"><i class="bi bi-plus mr-1"></i>Add College</a>
          </p>
          
          @if ($message = Session::get('edit_college_success'))
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
              <div class="row">
                <div class="col-md-12">
                  <table id="college_table" class="table table-hover">
                      <thead>
                          <tr>
                              <th>Name</th>
                              <th>Actions</th>
                          </tr>
                      </thead>
                      <tbody>
                        @foreach ($colleges as $college)
                          <tr>
                            <td>{{ $college->name }}</td>
                            <td>
                              <div role="group">
                                  <a href="{{ route('admin.colleges.edit', $college->id) }}"  class="btn btn-outline-success btn-sm mr-3"><i class="bi bi-pencil-square mr-2"></i>Edit</a>
                                  <button type="button" value="{{ $college->id }}" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-college="{{ $college->name }}"><i class="bi bi-trash mr-2"></i>Delete</button>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                          <th>Name</th>
                          <th>Actions</th>
                      </tr>
                      </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Delete Modal --}}
      @include('maintenances.colleges.delete')

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
          $('#college_table').DataTable({
            'aoColumnDefs': [{
                  'bSortable': false,
                  'aTargets': [-1], /* 1st one, start by the right */
                  'searchable': false
              }]
          });
        } );

        $('#college_table thead th.filter').each(function() {
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
          var collegeName = button.getAttribute('data-bs-college')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = collegeName

          var url = '{{ route("admin.colleges.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('college_delete').action = url;
          
        });

      </script>
    @endpush
  </x-app-layout>
