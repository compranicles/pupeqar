<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Academic Module Forms Manager') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('maintenances.navigation-bar')
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>         
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <h4>Academic Module Forms</h4>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table text-center">
                                        <thead>
                                            <tr>
                                                <th>Form Name</th>
                                                <th>Active</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($academicforms as $form)
                                            <tr>
                                                <td>{{ $form->label }}</td>
                                                <td>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input active-switch" id="is_active_{{ $form->id }}" data-id="{{ $form->id }}" {{ ($form->is_active == 1) ? 'checked': '' }}>
                                                        <label class="custom-control-label" for="is_active_{{ $form->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('academic-module-forms.edit', $form->id) }}" class="btn btn-outline-primary btn-sm mr-2">Rename</a>
                                                    <a href="{{ route('academic-module-forms.show', $form->id) }}" class="btn btn-warning edit-row btn-sm">Fields</a>
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
        <script>
             $('.active-switch').on('change', function(){
                var optionID = $(this).data('id');
                if ($(this).is(':checked')) {
                    $.ajax({
                        url: '/academic-module-forms/activate/'+optionID
                    });
                } else {
                    $.ajax({
                        url: '/academic-module-forms/inactivate/'+optionID
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>