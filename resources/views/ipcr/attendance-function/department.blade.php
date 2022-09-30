<div class="modal fade" id="departmentModal" data-backdrop="static" tabindex="-1" aria-labelledby="departmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="departmentModalLabel">Department/Section Functions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('attendance-function.tag-department') }}" method="post">
                    @csrf
                    <div class="d-flex">
                        <div class="col-md-9 px-0">
                            <label for=""><strong>Tag the department/section you are reporting to:</strong></label>
                            <select name="selected_department[]" id="selected_department" readonly required>
                                <option value="">Choose...</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-inline-flex mt-3 p-2">
                            <button type="button" onclick="tagDepartment();" class="btn btn-warning w-100">Add</button>
                            <button type="submit" class="btn btn-success w-100">Save</button>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="department_functions_table">
                        <thead>
                            <tr>
                                <th>Department/Section</th>
                                <th>Brief Description of Activity</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($departmentFunctions as $row)
                            <tr>
                                <td>{{ $row->department_name }}</td>
                                <td>{{ $row->activity_description }}</td>
                                <td><strong>{{ $row->remarks }}</strong></td>
                                <td>
                                    <a href="{{ route('attendance-function.create').'?id='.$row->id.'&type=department' }}" class="btn btn-sm btn-success ">Add</a>
                                </td>
                            </tr>
                            @empty
                            
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('dist/selectize.min.js') }}"></script>
    <script>
        $('#department_functions_table').DataTable();

        $(function() {
            $('#selected_department')[0].selectize.lock();
        });
        function tagDepartment() {
            $('#selected_department')[0].selectize.unlock();
        }

        $("#selected_department").selectize({
            maxItems: null,
            valueField: 'id',
            labelField: 'name',
            sortField: "name",
            searchField: "name",
            options: @json($departments),
            items: @json($departmentsID),
        });
    </script>
@endpush
