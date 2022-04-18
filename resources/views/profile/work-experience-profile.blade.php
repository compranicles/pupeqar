<x-app-layout>
    <x-slot name="header">
        @include('profile.navigation')
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('profile.workExperience') }}"><i class="bi bi-chevron-double-left"></i>Back to all Work Experience</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Work Experience</h3>
                        <hr>
                        <fieldset>
                            <div class="row m-1">
                                <div class="col-md-12">
                                    <div class="form-group input-group-sm">
                                        <label for="">Position </label>
                                        <input type="text" readonly class="form-control" value="{{ $workExperience[0]->Position }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row m-1">
                                <div class="col-md-12">
                                    <div class="form-group input-group-sm">
                                        <label for="">Company</label>
                                        <input type="text" readonly class="form-control" value="{{ $workExperience[0]->Company }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group input-group-sm">
                                        <label for="">Inclusive Date</label>
                                        <input type="text" readonly class="form-control" value="{{ date('m/d/Y', strtotime($workExperience[0]->StartDate)).' - '. date('m/d/Y', strtotime($workExperience[0]->EndDate))}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row m-1">
                                <div class="col-md-4">
                                    <div class="form-group input-group-sm">
                                        <input type="checkbox" disabled {{ $workExperience[0]->IsGovernment == 'Y' ? 'checked' : '' }}>
                                        <label for="">Government Service</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-1">
                                <div class="col-md-6">
                                    <div class="form-group input-group-sm">
                                        <label for="">Employment Status</label>
                                        <input type="text" readonly class="form-control" value="{{ $employeeStatus }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row m-1">
                                <div class="col-md-4">
                                    <div class="form-group input-group-sm">
                                        <label for="">Salary Grade</label>
                                        <input type="text" readonly class="form-control" value="{{ $workExperience[0]->SalaryGrade }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group input-group-sm">
                                        <label for="">Step Increment</label>
                                        <input type="text" readonly class="form-control" value="{{ $workExperience[0]->Step }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group input-group-sm">
                                        <label for="">Monthly Salary</label>
                                        <input type="text" readonly class="form-control" value="{{ $workExperience[0]->Salary }}">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>