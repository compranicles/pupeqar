<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Activity Log') }}
        </h2>
    </x-slot>

        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Activity Log</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-bordered" id="log_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Event</th>
                                        <th>URL</th>
                                        <!-- <th>Method</th>
                                        <th>IP</th>
                                        <th>User Agent</th> -->
                                        <th>Date</th>
                                    </tr>   
                            </thead>
                               <tbody>
                                   @if($logs->count())
                                        @foreach($logs as $key => $log)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $log->subject }}</td>
                                            <td class="text-success">{{ $log->url }}</td>
                                            {{--<td><label class="label label-info">{{ $log->method }}</label></td>
                                            <td class="text-secondary">{{ $log->ip }}</td>
                                            <td class="text-danger">{{ $log->agent }}</td>--}}
                                            <td>
                                                <?php $created_at = strtotime( $log->created_at );
                                                            $created_at = date( 'M d, Y h:i A', $created_at ); ?>  
                                                        {{ $created_at }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endif
                               </tbody>
                            </table>
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
                $('#log_table').DataTable();
            });
        </script>
    @endpush
</x-app-layout>