<div class="modal fade" id="collegeModal" data-backdrop="static" tabindex="-1" aria-labelledby="collegeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="collegeModalLabel">College/Department Functions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="college_functions_table">
                        <thead>
                            <tr>
                                <th>Brief Description of Activity</th>
                                <th>College</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($collegeFunctions as $row)
                            <tr>
                                <td>{{ $row->activity_description }}</td>
                                <td>{{ $row->college_name }}</td>
                                <td>
                                    <a href="{{ route('attendance-function.create').'?id='.$row->id.'&type=college' }}" class="btn btn-sm btn-success ">Add</a>
                                </td>
                            </tr>
                            @endforeach
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
    <script>
        $('#college_functions_table').DataTable();
    </script>
@endpush
