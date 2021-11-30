<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Extension Program Forms Manager') }}
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
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <h4>Extension Program Forms</h4>
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
                                            @foreach ($extension_program_forms as $form)
                                            <tr>
                                                <td>{{ $form->label }}</td>
                                                <td>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input active-switch" id="is_active_{{ $form->id }}" data-id="{{ $form->id }}" {{ ($form->is_active == 1) ? 'checked': '' }}>
                                                        <label class="custom-control-label" for="is_active_{{ $form->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('extension-program-forms.edit', $form->id) }}" class="btn btn-outline-primary btn-sm">Rename</a>
                                                    <a href="{{ route('extension-program-forms.show', $form->id) }}" class="btn btn-warning edit-row btn-sm">Fields</a>
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
            $(function() {
                $('.active-switch').on('change', function(){
                    var optionID = $(this).data('id');
                    if ($(this).is(':checked')) {
                        $.ajax({
                            url: '/extension-program-forms/activate/'+optionID
                        });
                    } else {
                        $.ajax({
                            url: '/extension-program-forms/inactivate/'+optionID
                        });
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>