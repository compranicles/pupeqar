<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Expert Services Rendered') }}
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
            <div class="col-md-12">
            @include('extension-programs.navigation-bar')
            </div>

            <div class="col-lg-12">
                @if ($message = Session::get('edit_esconference_success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>         
                @endif
                @if ($message = Session::get('cannot_access'))
                <div class="alert alert-danger alert-index">
                    {{ $message }}
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-inline mr-2">
                                <a href="{{ route('expert-service-in-conference.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Expert Service in Conference/Workshop/Training Course</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="row">
                                <div class="col-md-3">
                                    <label for="natureFilter" class="mr-2">Nature: </label>
                                    <select id="natureFilter" class="custom-select">
                                        <option value="">Show All</option>
                                        @foreach ($conferenceNature as $nature)
                                        <option value="{{ $nature->name }}">{{ $nature->name }}</option>
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
                                <div class="col-md-5">
                                    <label for="collegeFilter" class="mr-2">College/Branch/Campus/Office where committed: </label>
                                    <select id="collegeFilter" class="custom-select">
                                        <option value="">Show All</option>
                                        @foreach($conference_in_colleges as $college)
                                        <option value="{{ $college->name }}">{{ $college->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table" id="esconference_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Nature</th>
                                        <th>College/Branch/Campus/Office</th>
                                        <th>Date Added</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expertServicesConference as $expertServiceConference)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' ">{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' ">{{ $expertServiceConference->title }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' ">{{ $expertServiceConference->nature }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' ">{{ $expertServiceConference->college_name }}</td>

                                        <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' ">
                                            <?php $created_at = strtotime( $expertServiceConference->created_at );
                                                $created_at = date( 'M d, Y h:i A', $created_at ); ?>        
                                            {{ $created_at }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' ">
                                            <?php $updated_at = strtotime( $expertServiceConference->updated_at );
                                                $updated_at = date( 'M d, Y h:i A', $updated_at ); ?>        
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('expert-service-in-conference.edit', $expertServiceConference) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i></a>
                                                <button type="button" value="{{ $expertServiceConference->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-esconference="{{ $expertServiceConference->title }}"><i class="bi bi-trash" style="font-size: 1.25em;"></i></button>
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
             $('#esconference_table').DataTable();
         } );

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var esConferenceTitle = button.getAttribute('data-bs-esconference')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = esConferenceTitle

          var url = '{{ route("expert-service-in-conference.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;
          
        });
     </script>
     <script>
         var table =  $("#esconference_table").DataTable();
          var natureIndex = 0;
            $("#esconference_table th").each(function (i) {
                if ($($(this)).html() == "Nature") {
                    natureIndex = i; return false;

                }
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var selectedItem = $('#natureFilter').val()
                    var nature = data[natureIndex];
                    if (selectedItem === "" || nature.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );

            var quarterIndex = 0;
            $("#esconference_table th").each(function (i) {
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
            $("#esconference_table th").each(function (i) {
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
            $("#esconference_table th").each(function (i) {
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

            $("#natureFilter").change(function (e) {
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