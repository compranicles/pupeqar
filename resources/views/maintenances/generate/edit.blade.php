<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Report Table: '.$table->name) }}
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

                        {{-- Table for displaying --}}
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('maintenance.generate.view', $table->type_id) }}" class="btn btn-secondary">Back</a>
                                <hr>
                            </div>
                        </div>
                        @if ($table->is_table == "0")
                            <form action="{{ route('maintenance.generate.save', ['type_id' => $table->type_id, 'table_id' => $table->id]) }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" id="name" value="{{ old("name", $table->name) }}" class="form-control">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <div class="mb-0">
                                            <div class="d-flex justify-content-end align-items-baseline">
                                                <button type="submit" class="btn btn-success ml-auto">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @elseif ($table->is_table == "1")
                            <form action="{{ route('maintenance.generate.save', ['type_id' => $table->type_id, 'table_id' => $table->id]) }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input type="text" name="name" id="name" value="{{ old("name", $table->name) }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="report_category">Report Category</label>
                                            <select name="report_category" id="report_category" class="form-control custom-select">
                                                <option value="" disabled selected>Choose...</option>
                                                @foreach ($report_categories as $row)
                                                <option value="{{ $row->id }}" {{ ($row->id == old('report_category', $table->report_category_id))? 'selected' : '' }}>{{ $row->name }}</option>
                                                @endforeach
                                                <option value="">None</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Footers</label>
                                            <input type="text" name="footers" id="footers" value="@foreach ($footers as $footer)@if($loop->last){{ $footer }}@else{{ $footer.';'}}@endif @endforeach" class="form-control">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <div class="mb-0">
                                            <div class="d-flex justify-content-end align-items-baseline">
                                                <button type="submit" class="btn btn-success ml-auto">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @if ($table->is_table == "1")
            <div class="col-md-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Columns</h5>
                            </div>
                            <hr>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm text-center" id="column_table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Is Active</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="field_sortable">
                                            @forelse ( $columns as $row)
                                            <tr id="{{ $row->id }}">
                                                <td>{{ $row->name }}</td>
                                                <td>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input active-switch" id="is_active_{{ $row->id }}" data-id="{{ $row->id }}" {{ ($row->is_active == "1") ? 'checked': '' }}>
                                                        <label class="custom-control-label" for="is_active_{{ $row->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('maintenance.generate.edit-column', ['type_id' => $table->type_id, 'table_id' => $table->id, 'column_id' => $row->id]) }}" class="btn btn-warning btn-sm">Manage</a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3">Empty Table</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    @push('scripts')
        <script src="{{ asset('dist/selectize.min.js') }}"></script>
        <script src="{{ asset('jquery-ui/jquery-ui.js') }}"></script>
        <script>
            $('#field_sortable').sortable({
                stop: function(e, ui) {
                    var array_values = $('#field_sortable').sortable('toArray');
                    var array_values = JSON.stringify(array_values);
                    $.ajax({
                        url: '/maintenances/generate/arrange',
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

            $("#footers").selectize({
                delimiter: ";",
                persist: false,
                create: function (input) {
                    return {
                        value: input,
                        text: input,
                    };
                },
            });

            $('.active-switch').on('change', function(){
                var optionID = $(this).data('id');
                if ($(this).is(':checked')) {
                    $.ajax({
                        url: '/maintenances/generate/'+optionID+'/activate'
                    });
                } else {
                    $.ajax({
                        url: '/maintenances/generate/'+optionID+'/inactivate'
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>