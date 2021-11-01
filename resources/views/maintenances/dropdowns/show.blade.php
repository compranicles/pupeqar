<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Dropdowns') }}
        </h2>
    </x-slot>
     
    <div class="container mt-n4">
        
        <div class="row mt-3 mb-3">
            <div class="col-md-12">
                @include('maintenances.navigation-bar')
            </div>
          
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="offset-md-3 col-md-6 mb-3 text-center">
                                <h4>Dropdown Details</h4>
                            </div>
                        </div>
                          {{-- Success Message --}}
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                        @endif
                        {{-- Rename --}}
                        <form data-action="{{ route('dropdowns.update', $dropdown->id) }}" id="update_dropdown" class="needs-validation" method="POST" novalidate>
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-2 text-md-right">
                                    <label class="mt-2">Name</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                    
                                        <input class="form-control" type="text" value="{{ $dropdown->name }}" name="name" required>
                                                
                                        <div class="invalid-feedback">
                                            This is required. 
                                        </div>
                                    </div>
                
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        {{-- Table for displaying dropdown options --}}
                        <div class="row">
                            <div class="col-md-12 mb-3 text-center">
                                <h4>Dropdown Options</h4>
                            </div>
                        </div>
                         {{-- Success Message --}}
                         @if ($message = Session::get('success-options'))
                         <div class="alert alert-success">
                             {{ $message }}
                         </div>
                         @endif
                        <form action="{{ route('dropdowns.options.add', $dropdown->id) }}" class="needs-validation" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-2 text-md-right">
                                    <label class="mt-2">Option</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="name" required>
                                        <div class="invalid-feedback">
                                            This is required. 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-success">Add</button>
                                </div>
                            </div>
                        </form>
                            <hr>

                        <div class="row justify-content-center">
                            <div class="col-md-12 text-center">
                                <p><b>Instructions: </b>Drag and Drop the table rows to sort the dropdown's options. Toggle the switch to show or hide the option.</p>
                                <hr>
                            </div>

                            <div class="col-md-6 text-center">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Active</th>
                                            </tr>
                                        </thead>
                                        <tbody id="option_sortable">
                                            @foreach ($dropdown_options as $option)
                                            <tr id="{{ $option->id }}"> 
                                                <td><div id="option_name">{{ $option->name }}</div></td>
                                                <td>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input active-switch" id="is_active_{{ $option->id }}" data-id="{{ $option->id }}" {{ ($option->is_active == 1) ? 'checked': '' }}>
                                                        <label class="custom-control-label" for="is_active_{{ $option->id }}"></label>
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
        </div>
    </div>
    
    @push('scripts')
        <script src="{{ asset('jquery-ui/jquery-ui.js') }}"></script>
        <script src="{{ asset('js/dropdown.js') }}"></script>
        <script>
            $(function (){
                $('#option_sortable').sortable({
                    stop: function(e, ui) {
                        console.log($('#option_sortable').sortable('toArray'));
                        var array_values = $('#option_sortable').sortable('toArray');
                        var array_values = JSON.stringify(array_values);
                        $.ajax({
                            url: '/dropdowns/options/arrange',
                            type: "POST",
                            data: {data: array_values},
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (success){
                                console.log(success.data);
                            },
                        });
                    }
                });

                $('.active-switch').on('change', function(){
                    var optionID = $(this).data('id');
                    if ($(this).is(':checked')) {
                        $.ajax({
                            url: '/dropdowns/options/activate/'+optionID
                        });
                    } else {
                        $.ajax({
                            url: '/dropdowns/options/inactivate/'+optionID
                        });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>