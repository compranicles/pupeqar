<x-app-layout>
    <x-slot name="header">
        @include('profile.navigation')
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- If the user has more than 1 degree indicated here, please multiply the section INSIDE THE FIELDSET AND SEPARATE IT WITH <hr> -->
                        <h3 class="mb-3">Educational Degree</h3>
                        <hr>
                            <fieldset>
                                <legend>Baccalaureate Degree</legend>
                                <div class="row m-1">
                                    @include('profile.education-profile-template')
                                </div>
                            </fieldset>
                        <hr>
                            <fieldset>
                                <legend>Post Baccalaureate/Certificate/Diploma Degree</legend>
                                <div class="row m-1">
                                    @include('profile.education-profile-template')
                                    
                                    <div class="col-md-3">
                                        <div class="form-group input-group-sm">
                                            <label for="">Belonged to Top 1000</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group input-group-sm">
                                            <label for="">Program Accreditation Status</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <hr>
                            <fieldset>
                                <legend>Master's Degree</legend>
                                <div class="row m-1">
                                    @include('profile.education-profile-template')
                                    
                                    <div class="col-md-3">
                                        <div class="form-group input-group-sm">
                                            <label for="">Belonged to Top 1000</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group input-group-sm">
                                            <label for="">Program Accreditation Status</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <hr>
                            <fieldset>
                                <legend>Doctorate Degree</legend>
                                <div class="row m-1">
                                    @include('profile.education-profile-template')
                                    
                                    <div class="col-md-3">
                                        <div class="form-group input-group-sm">
                                            <label for="">Belonged to Top 1000</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group input-group-sm">
                                            <label for="">Program Accreditation Status</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <hr>
                            <fieldset>
                                <legend>Post Doctorate (Fellowship) Degree</legend>
                                <div class="row m-1">
                                    @include('profile.education-profile-template')
                                    
                                    <div class="col-md-3">
                                        <div class="form-group input-group-sm">
                                            <label for="">Belonged to Top 1000</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group input-group-sm">
                                            <label for="">Program Accreditation Status</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <hr>

                            <fieldset>
                                <legend>Other Degree</legend>
                                <div class="row m-1">
                                    @include('profile.education-profile-template')
                                    
                                    <div class="col-md-3">
                                        <div class="form-group input-group-sm">
                                            <label for="">Belonged to Top 1000</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group input-group-sm">
                                            <label for="">Program Accreditation Status</label>
                                            <input type="text" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>