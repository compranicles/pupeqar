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
                        @foreach ($educationLevel as $level)
                            @foreach ($educationFinal as $final)
                                @if ($level->EducationLevelID == $final->EducationLevelID)
                                    @switch($final->EducationLevelID)
                                        @case('1')
                                            <fieldset>
                                                <legend>Elementary</legend>
                                                @include('profile.educational-bg-profile-template', ['level' => '0', 'values' => $final, 'disciplines' => $educationDisciplines])
                                            </fieldset>
                                            <hr>
                                            @break
                                        @case('2')
                                            <fieldset>
                                                <legend>High School</legend>
                                                @include('profile.educational-bg-profile-template', ['level' => '0', 'values' => $final, 'disciplines' => $educationDisciplines])
                                            </fieldset>
                                            <hr>
                                            @break
                                        @case('3')
                                            <fieldset>
                                                <legend>College</legend>
                                                @include('profile.educational-bg-profile-template', ['level' => '1', 'values' => $final, 'disciplines' => $educationDisciplines])
                                            </fieldset>
                                            <hr>
                                            @break
                                        @case('4')
                                            <fieldset>
                                                <legend>Vocational/Trade Course</legend>
                                                @include('profile.educational-bg-profile-template', ['level' => '1', 'values' => $final, 'disciplines' => $educationDisciplines])
                                            </fieldset>
                                            <hr>
                                            @break
                                        @case('5')
                                            <fieldset>
                                                <legend>Master's Degree</legend>
                                                @include('profile.educational-bg-profile-template', ['level' => '1', 'values' => $final, 'disciplines' => $educationDisciplines])
                                            </fieldset>
                                            <hr>
                                            @break
                                        @case('6')
                                            <fieldset>
                                                <legend>Doctorate Degree</legend>
                                                @include('profile.educational-bg-profile-template', ['level' => '1', 'values' => $final, 'disciplines' => $educationDisciplines])
                                            </fieldset>
                                            @break
                                        @case('7')
                                            <fieldset>
                                                <legend>Post Baccalaureate</legend>
                                                @include('profile.educational-bg-profile-template', ['level' => '1', 'values' => $final, 'disciplines' => $educationDisciplines])
                                            </fieldset>
                                            <hr>
                                            @break
                                        @case('8')
                                            <fieldset>
                                                <legend>Junior High School</legend>
                                                @include('profile.educational-bg-profile-template', ['level' => '0', 'values' => $final, 'disciplines' => $educationDisciplines])
                                            </fieldset>
                                            <hr>
                                            @break
                                        @case('9')
                                            <fieldset>
                                                <legend>Senior High School</legend>
                                                @include('profile.educational-bg-profile-template', ['level' => '1', 'values' => $final, 'disciplines' => $educationDisciplines])
                                            </fieldset>
                                            <hr>
                                            @break
                                    @endswitch
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>