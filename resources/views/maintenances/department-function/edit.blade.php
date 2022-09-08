<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="font-weight-bold mr-2">Edit Department/Section Function</h3>
                <p>
                    <a class="back_link" href="{{ route('department-function-manager.index') }}"><i class="bi bi-chevron-double-left"></i>Back to University Functions Manager</a>
                </p>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-index">
                        <i class="bi bi-check-circle"></i> {{ $message }}
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-index">
                        {{ $message }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ route('department-function-manager.update', $department_function_manager->id) }}" id="add_form" method="POST">
                                    @csrf
                                    @method('put')
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Brief Description of Activity') }}" />

                                        <input type="text" name="activity_description" class="form-control" value="{{ old('activity_description', $department_function_manager->activity_description) }}" required/>
                                    </div>
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Department/Section') }}" />
                                        <select name="department_id" class="form_control custom-select " required>
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}" {{ old('department_id', $department_function_manager->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <button type="submit" class="btn btn-success mb-2 mr">Update</button>
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
</x-app-layout>
