<x-app-layout>
    <x-slot name="header">
        @include('profile.navigation')
    </x-slot>

    <!-- Multiply BASIC,HIGHER,POST BAC,ADVANCED EDUCATION sections INSIDE THE FIELDSET AND SEPARATE WITH <hr> if the user has more than 1 teaching discipline -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Teaching Discipline</h3>
                        <hr>
                        <!-- PLS. CHECK IF CODE IS EVERY TEACHING DISCIPLINE OR NOT -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Code</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <hr>
                            <fieldset>
                                <legend>Basic Education</legend>
                                <div class="row m-1">
                                    @include('profile.teaching-profile-template')
                                </div>
                            </fieldset>
                        <hr>
                            <fieldset>
                                <legend>Higher Education</legend>
                                <div class="row m-1">
                                    @include('profile.teaching-profile-template')
                                </div>
                            </fieldset>
                        <hr>
                            <fieldset>
                                <legend>Post Baccalaureate</legend>
                                <div class="row m-1">
                                    @include('profile.teaching-profile-template')
                                </div>
                            </fieldset>
                        <hr>
                            <fieldset>
                                <legend>Advanced Education</legend>
                                <div class="row m-1">
                                    @include('profile.teaching-profile-template')
                                </div>
                            </fieldset>
                    </div>
                </div>
                <!-- IN THE GIVEN FORMAT HAS MANY FIELDS WHERE ONLY 1 SUBJECT MAY HAVE A DATA (AM I RIGHT?). IF NOT POSSIBLE, PLEASE TELL IT TO ME RIGHT AWAY SO I CAN CHANGE THE CODE AND STICK WITH THE GIVEN FORMAT -->
                <!-- <div class="card mt-3">
                    <div class="card-body">
                        <h3>No. of Subjects Aligned with Area of Specialization</h3>
                        <hr>
                        <fieldset>
                            <legend>Basic Education</legend>
                            <div class="row m-1">
                                @include('profile.teaching-specialization-template')
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>Higher Education</legend>
                            <div class="row m-1">
                                @include('profile.teaching-specialization-template')
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>Advanced Education</legend>
                            <div class="row m-1">
                                @include('profile.teaching-specialization-template')
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>Non-Traditional</legend>
                            <div class="row m-1">
                                @include('profile.teaching-specialization-template')
                            </div>
                        </fieldset>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            autoclose: true,
        format: 'mm/dd/yyyy',
        immediateUpdates: true,
        todayBtn: "linked",
        todayHighlight: true
        });
    });
</script>  
@endpush
</x-app-layout>