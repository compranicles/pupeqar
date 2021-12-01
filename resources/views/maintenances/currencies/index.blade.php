<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Currencies') }}
        </h2>
    </x-slot>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          @include('maintenances.navigation-bar')
        </div>

        <div class="col-md-12">
          @if ($message = Session::get('edit_currency_success'))
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
                                <a href="{{ route('currencies.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Currency</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="table-responsive">
                            <table class="table" id="currency_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Symbol</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($currencies as $currency)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $currency->code }}</td>
                                        <td>{{ $currency->name }}</td>
                                        <td>{{ $currency->symbol }}</td>
                                        <td>
                                          <div role="group">
                                              <a href="{{ route('currencies.edit', $currency->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                              <button type="button" value="{{ $currency->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-currency="{{ $currency->name }}"><i class="bi bi-trash"></i> Delete</button>
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
      @include('maintenances.currencies.delete')

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
          $('#currency_table').DataTable({
            'aoColumnDefs': [{
                  'bSortable': false,
                  'aTargets': [-1], /* 1st one, start by the right */
                  'searchable': false
              }]
          });
        } );

        $('#currency_table thead th.filter').each(function() {
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
          var currencyName = button.getAttribute('data-bs-currency')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = currencyName

          var url = '{{ route("currencies.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('currency_delete').action = url;
          
        });

      </script>
    @endpush
  </x-app-layout>
