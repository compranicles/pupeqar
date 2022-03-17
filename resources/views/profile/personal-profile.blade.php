<x-app-layout>
    <x-slot name="header">
        @include('profile.navigation')
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Personal Profile</h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Employee No.</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail1[0]->EmpNo }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Date Hired</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail1[0]->HireDate }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label for="">College/Branch/Campus</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label for="">Program</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">  
                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="">Surname</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail1[0]->LName }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="">First Name</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail1[0]->FName }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="">Middle Name</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail1[0]->MName }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group input-group-sm">
                                    <label for="">Suffix</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail1[0]->EName }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="">Birthdate</label>
                                    <input type="text" readonly class="form-control" id="birthdate" value="{{ $employeeDetail1[0]->BirthDate }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group input-group-sm">
                                    <label for="">Sex</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail2[0]->Gender }}">
                                </div>
                            </div>
                            <!-- NOTE: You can join 2 citizenships in one field (for dual citizenship), but separate with comma (I suggest but it depends on you) -->
                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label for="">Citizenship</label>
                                    <input type="text" readonly class="form-control" value="{{ $citizenship }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="">Height (m)</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail2[0]->Height }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="">Weight (kg)</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail2[0]->Weight }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="">Blood Type</label>
                                    <input type="text" readonly class="form-control" value="{{ $employeeDetail2[0]->BloodType }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label for="">Religion</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Civil Status</label>
                                    <input type="text" readonly class="form-control" value="{{ $civilStatus }}">
                                </div>
                            </div>
                            <!-- CONCATENATE INFO (CHECK EXISTENCE OF INFO) OR SEPARATE THEM (LIKE WHAT I COMMENTED BELOW) -->
                            <div class="col-md-12">
                                <div class="form-group input-group-sm">
                                    <label for="">Place of Birth</label>
                                    <input type="text" readonly class="form-control" value="{{ $placeOfBirth }}">
                                </div>
                            </div>
                        </div>
                        {{-- <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Email Address</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Mobile #</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <!-- CONCATENATE INFO (CHECK EXISTENCE OF INFO) OR SEPARATE THEM (LIKE WHAT I COMMENTED BELOW) -->
                            <div class="col-md-12">
                                <div class="form-group input-group-sm">
                                    <label for="">Permanent Address</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <!-- CONCATENATE INFO (CHECK EXISTENCE OF INFO) OR SEPARATE THEM (LIKE WHAT I COMMENTED BELOW) -->
                            <div class="col-md-12">
                                <div class="form-group input-group-sm">
                                    <label for="">Residential Address</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                        </div> --}}
                            <!-- <fieldset>
                                <legend>Place of Birth</legend>
                                <div class="row m-1">
                                    <div class="col-md-6">
                                        <div class="form-group input-group-sm">
                                            <label for="">Country</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group input-group-sm">
                                            <label for="">Region</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group input-group-sm">
                                            <label for="">Province</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group input-group-sm">
                                            <label for="">City/Municipality</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Email Address</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Mobile #</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <fieldset>
                            <legend>Permanent Address</legend>
                            <div class="row m-1">
                                <div class="col-md-6">
                                    <div class="form-group input-group-sm">
                                        <label for="">Country</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group input-group-sm">
                                        <label for="">Region</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group input-group-sm">
                                        <label for="">Province</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group input-group-sm">
                                        <label for="">City/Municipality</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group input-group-sm">
                                        <label for="">Unit, No., Street, Subdivision/Barangay</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group input-group-sm">
                                        <label for="">Zip Code</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group input-group-sm">
                                        <label for="">Telephone #</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="mt-3"></div>
                        <fieldset>
                            <legend>Residential Address</legend>
                            <div class="row m-1">
                                <div class="col-md-6">
                                    <div class="form-group input-group-sm">
                                        <label for="">Country</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group input-group-sm">
                                        <label for="">Region</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group input-group-sm">
                                        <label for="">Province</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group input-group-sm">
                                        <label for="">City/Municipality</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group input-group-sm">
                                        <label for="">Unit, No., Street, Subdivision/Barangay</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group input-group-sm">
                                        <label for="">Zip Code</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group input-group-sm">
                                        <label for="">Telephone #</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                        </fieldset> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>