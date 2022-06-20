<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Submission: '.$report_category->name) }}
        </h2>
    </x-slot>

    <div class="container mt-n4">

        <div class="row mt-3 mb-3">
            <div class="col-md-12">
                @include('maintenances.navigation-bar')
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        {{-- Success Message --}}
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                        @endif
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <a href="{{ route('report-types.show', $report_category->report_type_id) }}" class="btn btn-secondary">Back</a>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Columns</a>
                                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Rename</a>
                                </div><hr>
                            </div>
                            <div class="col-md-9">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-success" data-toggle="modal" data-target="#addModal">
                                                    <i class="fas fa-plus"></i> Add Column
                                                </button>
                                                <hr>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="table-responsive text-center">
                                                    <table id="report_columns_table" class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Table</th>
                                                                <th>Column</th>
                                                                <th>Active</th>
                                                                {{-- <th>Action</th> --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody id="field_sortable">
                                                            @foreach ($report_columns as $report_column)
                                                            <tr id="{{ $report_column->id }}">
                                                                <td>{{ $report_column->name }}</td>
                                                                <td>{{ $report_column->table }}</td>
                                                                <td>{{ $report_column->column }}</td>
                                                                <td>
                                                                    <div class="custom-control custom-switch">
                                                                        <input type="checkbox" class="custom-control-input active-switch" id="is_active_{{ $report_column->id }}" data-id="{{ $report_column->id }}" {{ ($report_column->is_active == 1) ? 'checked': '' }}>
                                                                        <label class="custom-control-label" for="is_active_{{ $report_column->id }}"></label>
                                                                    </div>
                                                                </td>
                                                                {{-- <td>
                                                                    <a href="{{ route('report-categories.report-columns.edit', [$report_category->id, $report_column->id]) }}" class="btn btn-warning btn-sm edit-row">Manage</a>
                                                                </td>     --}}
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                        <form action="{{ route('report-categories.update', $report_category->id) }}" method="POST">
                                            @csrf
                                            @method('put')
                                            <div class="row">
                                                <div class="col-md-12">
                                                    {{-- Name Input --}}
                                                    <div class="form-group">
                                                        <x-jet-label value="{{ __('Name') }}" />

                                                        <input class="form-control" type="text" id="label_edit" name="name" value="{{ old('name', $report_category->name) }}" required>

                                                        <div class="invalid-feedback">
                                                            This is required.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="mb-0">
                                                        <div class="d-flex justify-content-end align-items-baseline">
                                                            <button type="submit" id="submit" class="btn btn-success">Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('maintenances.reports.categories.add', ['report_category' => $report_category->id])

    @push('scripts')
        <script src="{{ asset('jquery-ui/jquery-ui.js') }}"></script>
        <script>
            var url1 = "{{ url('report-columns/arrange') }}";
            $('#field_sortable').sortable({
                stop: function(e, ui) {
                    var array_values = $('#field_sortable').sortable('toArray');
                    var array_values = JSON.stringify(array_values);
                    $.ajax({
                        url: url1,
                        type: "POST",
                        data: {data: array_values},
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (){
                        },
                    });
                }
            });

            var urlAct =  "{{ url('report-columns/activate/:id') }}";
			var urlInaAct =  "{{ url('report-columns/inactivate/:id') }}";
			$('.active-switch').on('change', function(){
				var optionID = $(this).data('id');
				var url1 = urlAct.replace(':id', optionID);
				var url2 = urlInaAct.replace(':id', optionID);
				if ($(this).is(':checked')) {
					$.ajax({
						url: url1
					});
				} else {
					$.ajax({
						url: url2
					});
				}
			});
            
        </script>
    @endpush
</x-app-layout>

