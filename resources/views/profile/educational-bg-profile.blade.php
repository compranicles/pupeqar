<x-app-layout>
    <x-slot name="header">
        @include('profile.navigation')
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Educational Background</h3>
                        <hr>
                        <!-- IF ELEMENTARY BG EXISTS, THEN SHOW THIS FIELDSET - getEducationLevel (HRIS)  -->
                        <fieldset>
                            <legend>Elementary</legend>
                            @include('profile.educational-bg-profile-template', ['level' => '0'])
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>High School</legend>
                            @include('profile.educational-bg-profile-template', ['level' => '0'])
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>Junior High School</legend>
                            @include('profile.educational-bg-profile-template', ['level' => '0'])
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>Senior High School</legend>
                            @include('profile.educational-bg-profile-template', ['level' => '1'])
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>Vocational/Trade Course</legend>
                            @include('profile.educational-bg-profile-template', ['level' => '1'])
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>College</legend>
                            @include('profile.educational-bg-profile-template', ['level' => '1'])
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>Post Baccalaureate</legend>
                            @include('profile.educational-bg-profile-template', ['level' => '1'])
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>Master's Degree</legend>
                            @include('profile.educational-bg-profile-template', ['level' => '1'])
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>Doctorate Degree</legend>
                            @include('profile.educational-bg-profile-template', ['level' => '1'])
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>