<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Partnership, Linkages & Network') }}
        </h2>
    </x-slot>
    @php
    $currentMonth = date('m');

    $year_or_quarter = 0;
    if ($currentMonth <= 3 && $currentMonth >= 1) 
        $quarter = 1;
    if ($currentMonth <= 6 && $currentMonth >= 4)
        $quarter = 2;
    if ($currentMonth <= 9 && $currentMonth >= 7)
        $quarter = 3;
    if ($currentMonth <= 12 && $currentMonth >= 10) 
        $quarter = 4;
    @endphp
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                @if ($message = Session::get('partnership_success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i>  {{ $message }}
                </div>          
                @endif
                @if ($message = Session::get('cannot_access'))
                <div class="alert alert-danger alert-index">
                    {{ $message }}
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('partnership.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Partnership, Linkages & Network</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="row">
                                <div class="col-md-4">
                                    <label for="collabFilter" class="mr-2">Collaboration: </label>
                                    <select id="collabFilter" class="custom-select">
                                        <option value="">Show All</option>
                                        @foreach ($collaborations as $collaboration)
                                        <option value="{{ $collaboration->name }}">{{ $collaboration->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="quarterFilter" class="mr-2">Quarter Period: </label>
                                    <div class="d-flex">
                                        <select id="quarterFilter" class="custom-select" name="quarter">
                                            <option value="1" {{$quarter== 1 ? 'selected' : ''}} class="quarter">1</option>
                                            <option value="2" {{$quarter== 2 ? 'selected' : ''}} class="quarter">2</option>
                                            <option value="3" {{$quarter== 3 ? 'selected' : ''}} class="quarter">3</option>
                                            <option value="4" {{$quarter== 4 ? 'selected' : ''}} class="quarter">4</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="yearFilter" class="mr-2">Year Added:</label>
                                    <div class="d-flex">
                                        <select id="yearFilter" class="custom-select" name="yearFilter">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="collegeFilter" class="mr-2">College/Branch/Campus/Office where committed: </label>
                                    <select id="collegeFilter" class="custom-select">
                                        <option value="">Show All</option>
                                        @foreach($partnership_in_colleges as $college)
                                        <option value="{{ $college->name }}">{{ $college->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table" id="partnership_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>MOA/MOU Code</th>
                                        <th>Title</th>
                                        <th>Organization/Partner</th>
                                        <th>Collaboration</th>
                                        <th>College/Branch/Campus/Office</th>
                                        <th>Date Added</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($partnerships as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' ">{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' ">{{ $row->moa_code }}</td>
                                        <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' ">{{ $row->title_of_partnership }}</td>
                                        <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' ">{{ $row->name_of_partner }}</td>
                                        <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' ">{{ $row->collab }}</td>
                                        <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' ">{{ $row->college_name }}</td>
                                        <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' ">
                                            <?php $created_at = strtotime( $row->created_at );
                                                $created_at = date( 'M d, Y h:i A', $created_at ); ?>  
                                            {{ $created_at }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' ">
                                            <?php $updated_at = strtotime( $row->updated_at );
                                                $updated_at = date( 'M d, Y h:i A', $updated_at ); ?>  
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('partnership.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i></a>
                                                <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-partnership="{{ $row->title_of_partnership }}"><i class="bi bi-trash" style="font-size: 1.25em;"></i></button>
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
    @include('delete')

    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
     <script>
         window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);

         $(document).ready( function () {
             $('#partnership_table').DataTable();
         } );

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var partnershipTitle = button.getAttribute('data-bs-partnership')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = partnershipTitle

          var url = '{{ route("partnership.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;
          
        });
     </script>
     <script>
         var table =  $("#partnership_table").DataTable();

          var collabIndex = 0;
            $("#partnership_table th").each(function (i) {
                if ($($(this)).html() == "Collaboration") {
                    collabIndex = i; return false;

                }
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var selectedItem = $('#collabFilter').val()
                    var collaboration = data[collabIndex];
                    if (selectedItem === "" || collaboration.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );
            var quarterIndex = 0;
            $("#partnership_table th").each(function (i) {
                if ($($(this)).html() == "Date Modified") {
                    quarterIndex = i; return false;

                }
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var selectedItem = $('#quarterFilter').val()
                    var quarter = data[quarterIndex].substring(0, 4);
                    switch (quarter) {
                        case "Jan ":
                        case "Feb ":
                        case "Mar ":
                            quarter = "1";
                            break;
                        case "Apr ":
                        case "May ":
                        case "Jun ":
                            quarter = "2";
                            break;
                        case "Jul ":
                        case "Aug ":
                        case "Sep ":
                            quarter = "3";
                            break;
                        case "Oct ":
                        case "Nov ":
                        case "Dec ":
                            quarter = "4";
                            break;
                        default:
                        quarter = "";
                    }

                    if (selectedItem === "" || quarter.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );

            var yearIndex = 0;
            $("#partnership_table th").each(function (i) {
                if ($($(this)).html() == "Date Added") {
                    yearIndex = i; return false;

                }
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    let selectedItem = $('#yearFilter').val()
                    var year = data[yearIndex].substring(8, 12);
                    console.log(year);
                    if (selectedItem === "" || year.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );

            var collegeIndex = 0;
            $("#partnership_table th").each(function (i) {
                if ($($(this)).html() == "College/Branch/Campus/Office") {
                    collegeIndex = i; return false;

                }
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var selectedItem = $('#collegeFilter').val()
                    var college = data[collegeIndex];
                    if (selectedItem === "" || college.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );

            $("#collabFilter").change(function (e) {
                table.draw();
            });
            $("#quarterFilter").change(function (e) {
                table.draw();
            });
            $("#yearFilter").change(function (e) {
                table.draw();
            });
            $("#collegeFilter").change(function (e) {
                table.draw();
            });

            table.draw();
     </script>
     <script>
        var max = new Date().getFullYear();
        var min = 0;
        var diff = max-2022;
        min = max-diff;
        select = document.getElementById('yearFilter');
        for (var i = max; i >= min; i--) {
            select.append(new Option(i, i));
            if (i == "{{ date('Y') }}") {
                document.getElementById("yearFilter").value = i;
                table.draw();
            }
        }
    </script>
     @endpush
</x-app-layout>