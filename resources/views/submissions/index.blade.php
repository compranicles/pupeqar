<x-app-layout>
    <x-slot name="header">
        @include('submissions.navigation')
    </x-slot>
    <?php $ctr = 0; ?>
    @foreach ( $report_tables as $table)
        @if(array_key_exists($table->id, $report_array))
            @if (count($report_array[$table->id]) == 0)
                <?php $ctr = 0; ?>
            @else
                <?php $ctr = 1; ?>
                @break
            @endif
        @endif
    @endforeach

    @if ($ctr == 0)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success text-center p-5" role="alert">
                <h5>
                    No accomplishments to finalize so far.
                </h5>
                <small>Submissions this quarter {{$currentQuarterYear->current_quarter}} of {{ $currentQuarterYear->current_year }}: {{$totalReports}}</small>
            </div>
        </div>
    </div>
    @else
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {{-- ========= ALERT DETAILS ========= --}}

                @if ($message = Session::get('success'))
                <div class="alert alert-success temp-alert">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>
                @endif

                @if ($message = Session::get('error-message'))
                <div class="alert alert-danger temp-alert">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>
                @endif
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 ml-2">
                        <div class="submission-list">
                            <!-- <div class="row">
                                <div class="col-md-12 ml-2"> -->
                                        <ul class="nav justify-content-center">
                                            @foreach ( $report_tables as $table)
                                                @if(array_key_exists($table->id, $report_array))
                                                    @if (count($report_array[$table->id]) == 0)
                                                        @continue
                                                    @endif
                                                @endif
                                            <li class="nav-item">
                                                @if ($table->id >= 30 && $table->id <= 32)
                                                    @if ($role == 'faculty')
                                                        @if ($table->id == 30)
                                                            <x-jet-nav-link href="#Special Tasks - Quality" class="text-dark"  class="text-dark">
                                                                {{ __('Special Tasks - Quality') }} <span class="badge bg-primary">{{ count($report_array[$table->id]) }}</span>
                                                            </x-jet-nav-link>
                                                        @elseif ($table->id == 31)
                                                            <x-jet-nav-link href="#Special Tasks - Efficiency" class="text-dark"  class="text-dark">
                                                                {{ __('Special Tasks - Efficiency') }} <span class="badge bg-primary">{{ count($report_array[$table->id]) }}</span>
                                                            </x-jet-nav-link>
                                                        @elseif ($table->id == 32)
                                                            <x-jet-nav-link href="#Special Tasks - Timeliness" class="text-dark"  class="text-dark">
                                                                {{ __('Special Tasks - Timeliness') }} <span class="badge bg-primary">{{ count($report_array[$table->id]) }}</span>
                                                            </x-jet-nav-link>
                                                        @endif
                                                    @else
                                                        @if ($table->id == 30)
                                                            <x-jet-nav-link href="#Accomplishments Based on OPCR - Quality" class="text-dark"  class="text-dark">
                                                                {{ __('Accomplishments Based on OPCR - Quality') }} <span class="badge bg-primary">{{ count($report_array[$table->id]) }}</span>
                                                            </x-jet-nav-link>
                                                        @elseif ($table->id == 31)
                                                            <x-jet-nav-link href="#Accomplishments Based on OPCR - Efficiency" class="text-dark"  class="text-dark">
                                                                {{ __('Accomplishments Based on OPCR - Efficiency') }} <span class="badge bg-primary">{{ count($report_array[$table->id]) }}</span>
                                                            </x-jet-nav-link>
                                                        @elseif ($table->id == 32)
                                                            <x-jet-nav-link href="#Accomplishments Based on OPCR - Timeliness" class="text-dark"  class="text-dark">
                                                                {{ __('Accomplishments Based on OPCR - Timeliness') }} <span class="badge bg-primary">{{ count($report_array[$table->id]) }}</span>
                                                            </x-jet-nav-link>
                                                        @endif
                                                    @endif
                                                @else
                                                    <x-jet-nav-link href="#{{$table->name}}" class="text-dark"  class="text-dark">
                                                        {{ __($table->name) }} <span class="badge bg-primary">{{ count($report_array[$table->id]) }}</span>
                                                    </x-jet-nav-link>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                <!-- </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <button class="btn btn-primary mb-3 mt-3" data-toggle="modal" data-target="#submitReportModal" id="submitReport" style="width: 100%;">Submit</button>
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex flex-row">
                            <div class="col-md-4 mt-1">
                                <input type="checkbox" id="all-submit" checked /> <label for="all-submit" class="font-weight-bold all-submit mr-4">Select All</label>
                                <button onClick="window.location.reload();" class="btn btn-secondary" style="color:white;"><i class="bi bi-arrow-clockwise" style="color:white;"></i> Refresh</button>
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex">
                                    <p class="mt-2 mr-2">College/Branch/Campus/Office: </p>
                                    <select id="collegeFilter" class="custom-select">
                                        <option value="all" {{ $collegeID == "all" ? 'selected' : '' }}>All</option>
                                        @foreach($colleges as $college)
                                        <option value="{{ $college->id }}" {{ $collegeID == $college->id ? 'selected' : '' }}>{{ $college->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                                <!-- <button class="btn btn-secondary ml-1"><i class="bi bi-filter"></i></button> -->

                        </div>
                    </div>
                </div>
            </div>

        </div>
        @endif
        @php
            $tableCount = 1;
            $count = 1;
        @endphp
        @foreach ( $report_tables as $table)
            @if(!array_key_exists($table->id, $report_array))
                @continue
            @endif
            @if (count($report_array[$table->id]) == 0)
                @continue
            @endif
        @if ($table->id >= 30 && $table->id <= 32)
            @if ($role == 'faculty')
                @if ($table->id == 30)
                    <h3 id="Special Tasks - Quality" class="submission-categories jumptarget">Special Tasks - Quality</h3>
                @elseif ($table->id == 31)
                    <h3 id="Special Tasks - Efficiency" class="submission-categories jumptarget">Special Tasks - Efficiency</h3>
                @elseif ($table->id == 32)
                    <h3 id="Special Tasks - Timeliness" class="submission-categories jumptarget">Special Tasks - Timeliness</h3>
                @endif
            @else
                @if ($table->id == 30)
                    <h3 id="Accomplishments Based on OPCR - Quality" class="submission-categories jumptarget">Accomplishments Based on OPCR - Quality</h3>
                @elseif ($table->id == 31)
                    <h3 id="Accomplishments Based on OPCR - Efficiency" class="submission-categories jumptarget">Accomplishments Based on OPCR - Efficiency</h3>
                @elseif ($table->id == 32)
                    <h3 id="Accomplishments Based on OPCR - Timeliness" class="submission-categories jumptarget">Accomplishments Based on OPCR - Timeliness</h3>
                @endif
            @endif
        @else
            <h3 id="{{ $table->name }}" class="submission-categories jumptarget">{{ $table->name }}</h3>
        @endif
        <div class="card h-100 card-submission">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-hover submissions_table">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="table-{{ $tableCount }}" class="select-submit-table" data-id='{{ $tableCount }}' checked></th>
                                        <th>#</th>
                                        @if($table->id <= 7)
                                        <th>Code</th>
                                        <th>Title</th>
                                        <th>Classification</th>
                                        <th>College/Branch/Campus/Office</th>
                                        @elseif($table->id == 8)
                                        <th>Title</th>
                                        <th>Classification</th>
                                        <th>College/Branch/Campus/Office</th>
                                        @elseif($table->id == 9)
                                        <th>Title</th>
                                        <th>Classification</th>
                                        <th>College/Branch/Campus/Office</th>
                                        @elseif($table->id == 10)
                                        <th>Publication/AVP</th>
                                        <th>Classification</th>
                                        <th>College/Branch/Campus/Office</th>
                                        @elseif($table->id == 11)
                                        <th>Title</th>
                                        <th>Nature</th>
                                        <th>College/Branch/Campus/Office</th>
                                        @elseif($table->id == 12)
                                        <th>Title</th>
                                        <th>Nature of Involvement</th>
                                        <th>College/Branch/Campus/Office</th>
                                        @elseif($table->id == 13)
                                        <th>Title</th>
                                        <th>Nature of Collaboration</th>
                                        <th>College/Branch/Campus/Office</th>
                                        @elseif($table->id == 14)
                                        <th>Description</th>
                                        <th>Type</th>
                                        <th>College/Branch/Campus/Office</th>
                                        @elseif($table->id == 15)
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>College/Branch/Campus/Office</th>
                                        @elseif($table->id == 16)
                                        <th>Course Title</th>
                                        <th>Assigned Task</th>
                                        <th>College/Branch/Campus/Office</th>
                                        @elseif($table->id == 17)
                                        <th>No. of Request</th>
                                        <th>Description</th>
                                        <th>College/Branch/Campus/Office</th>
                                        @elseif($table->id == 18)
                                        <th>Student Name</th>
                                        <th>Award Name</th>
                                        <th>Certifying Body</th>
                                        @elseif($table->id == 19)
                                        <th>No. of Student Attendees</th>
                                        <th>Title</th>
                                        <th>Classification</th>
                                        @elseif($table->id == 20)
                                        <th>Name</th>
                                        <th>Start Date</th>
                                        @elseif($table->id == 21)
                                        <th>Award Name</th>
                                        <th>Certifying Body</th>
                                        @elseif($table->id == 22)
                                        <th>Program Title</th>
                                        <th>Date</th>
                                        @elseif($table->id == 23)
                                        <th>Title</th>
                                        <th>Classification of Adoptor</th>
                                        @elseif ($table->id == 29)
                                        <th>Brief Descripiton of Accomplishment</th>
                                        @elseif ($table->id == 30)
                                        <th>Final Output</th>
                                        @elseif ($table->id == 31)
                                        <th>Final Output</th>
                                        @elseif ($table->id == 32)
                                        <th>Final Output</th>
                                        @endif
                                        <th>Date Last Accessed</th>
                                        <th>Reporting Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($report_array[$table->id] as $row)
                                        <tr role="button">
                                            <td><input type="checkbox" class="select-submit table-submit-{{ $tableCount }} all-submit" data-id='{{ $count }}' data-table-id={{ $tableCount }}
                                                @isset($row->id)
                                                    @if ( count($report_document_checker[$table->id][$row->id]) == 0)
                                                        disabled
                                                    @else
                                                        checked
                                                    @endif
                                                @else
                                                    @if ( count($report_document_checker[$table->id][$row->research_code]) == 0)
                                                        disabled
                                                    @else
                                                        checked
                                                    @endif
                                                @endisset
                                                ></td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $loop->iteration }}</td>
                                            @if($table->id <= 7)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="{{ $row->id }}">{{ $row->research_code }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->title }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->classification_name }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->college_name }}</td>
                                            @elseif($table->id == 8 )
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->title }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->classification_name }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->college_name }}</td>
                                            @elseif($table->id == 9 )
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->title }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->classification_name }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->college_name }}</td>
                                            @elseif($table->id == 10 )
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->title }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->nature_name }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->college_name }}</td>
                                            @elseif($table->id == 11 )
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->publication_or_audio_visual }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->classification_name }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->college_name }}</td>
                                            @elseif($table->id == 12)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ ($row->title_of_extension_program != null ? $row->title_of_extension_program : ($row->title_of_extension_project != null ? $row->title_of_extension_project : ($row->title_of_extension_activity != null ? $row->title_of_extension_activity : ''))) }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->nature_of_involvement_name }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->college_name }}</td>
                                            @elseif($table->id == 13)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->title_of_partnership }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->collab_nature }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->college_name }}</td>
                                            @elseif($table->id == 14)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->mobility_description }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->type }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->college_name }}</td>
                                            @elseif($table->id == 15)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->title }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->category_name }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->college_name }}</td>
                                            @elseif($table->id == 16)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->course_title }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->assigned_task_name }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->college_name }}</td>
                                            @elseif($table->id == 17)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->no_of_request }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->description_of_request }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->college_name }}</td>
                                            @elseif($table->id == 18)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->name_of_student }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->name_of_award }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->certifying_body }}</td>
                                            @elseif($table->id == 19)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->no_of_students }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->title }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->classification_name }}</td>
                                            @elseif($table->id == 20)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->name }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->start_date }}</td>
                                            @elseif($table->id == 21)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->name_of_award }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->certifying_body }}</td>
                                            @elseif($table->id == 22)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->title_of_the_program }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->date }}</td>
                                            @elseif($table->id == 23)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ ($row->program_title != null ? $row->program_title : ($row->project_title != null ? $row->project_title : ($row->activity_title != null ? $row->activity_title : ''))) }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->classification_of_adoptor_name }}</td>
                                            @elseif($table->id == 29)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->accomplishment_description }}</td>
                                            @elseif($table->id == 30)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->final_output }}</td>
                                            @elseif($table->id == 31)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->final_output }}</td>
                                            @elseif($table->id == 32)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->final_output }}</td>
                                            @endif
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">
                                                {{ date( 'M d, Y h:i A', strtotime($row->updated_at) ) }}
                                            </td>
                                            <td>
                                                @isset($row->id)

                                                    @if ( count($report_document_checker[$table->id][$row->id]) == 0)
                                                        @if($table->id == 1)
                                                        <a href="{{ route('research.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 2)
                                                        <a href="{{ url('research/'.$row->research_id.'/completed/'.$row->id.'/edit') }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 3)
                                                        <a href="{{ url('research/'.$row->research_id.'/publication/'.$row->id.'/edit') }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 4)
                                                        <a href="{{ url('research/'.$row->research_id.'/presentation/'.$row->id.'/edit') }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 5)
                                                        <a href="{{ url('research/'.$row->research_id.'/citation/'.$row->id.'/edit') }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 6)
                                                        <a href="{{ url('research/'.$row->research_id.'/utilization/'.$row->id.'/edit') }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 7)
                                                        <a href="{{ url('research/'.$row->research_id.'/copyrighted/'.$row->id.'/edit') }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 8)
                                                        <a href="{{ route('invention-innovation-creative.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 9)
                                                        <a href="{{ route('expert-service-as-consultant.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 10)
                                                        <a href="{{ route('expert-service-in-conference.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 11)
                                                        <a href="{{ route('expert-service-in-academic.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 12)
                                                        <a href="{{ route('extension-service.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 13)
                                                        <a href="{{ route('partnership.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 14)
                                                        <a href="{{ route('mobility.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 15)
                                                        <a href="{{ route('rtmmi.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 16)
                                                        <a href="{{ route('syllabus.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 17)
                                                        <a href="{{ route('request.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 18)
                                                        <a href="{{ route('student-award.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 19)
                                                        <a href="{{ route('student-training.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 20)
                                                        <a href="{{ route('viable-project.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 21)
                                                        <a href="{{ route('college-department-award.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 22)
                                                        <a href="{{ route('outreach-program.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 23)
                                                        <a href="{{ route('technical-extension.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 29)
                                                        <a href="{{ route('admin-special-tasks.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 30)
                                                        <a href="{{ route('special-tasks.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 31)
                                                        <a href="{{ route('special-tasks.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @elseif($table->id == 32)
                                                        <a href="{{ route('special-tasks.edit', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @endif
                                                    @else
                                                        @if($table->id == 1)
                                                        <a href="{{ route('research.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 2)
                                                        <a href="{{ url('research/'.$row->research_id.'/completed/'.$row->id.'/edit') }}#upload-document" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 3)
                                                        <a href="{{ url('research/'.$row->research_id.'/publication/'.$row->id.'/edit') }}#upload-document" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 4)
                                                        <a href="{{ url('research/'.$row->research_id.'/presentation/'.$row->id.'/edit') }}#upload-document" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 5)
                                                        <a href="{{ url('research/'.$row->research_id.'/citation/'.$row->id.'/edit') }}#upload-document" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 6)
                                                        <a href="{{ url('research/'.$row->research_id.'/utilization/'.$row->id.'/edit') }}#upload-document" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 7)
                                                        <a href="{{ url('research/'.$row->research_id.'/copyrighted/'.$row->id.'/edit') }}#upload-document" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 8)
                                                        <a href="{{ route('invention-innovation-creative.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 9)
                                                        <a href="{{ route('expert-service-as-consultant.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 10)
                                                        <a href="{{ route('expert-service-in-conference.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 11)
                                                        <a href="{{ route('expert-service-in-academic.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 12)
                                                        <a href="{{ route('extension-service.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 13)
                                                        <a href="{{ route('partnership.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 14)
                                                        <a href="{{ route('mobility.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 15)
                                                        <a href="{{ route('rtmmi.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 16)
                                                        <a href="{{ route('syllabus.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 17)
                                                        <a href="{{ route('request.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 18)
                                                        <a href="{{ route('student-award.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 19)
                                                        <a href="{{ route('student-training.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 20)
                                                        <a href="{{ route('viable-project.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 21)
                                                        <a href="{{ route('college-department-award.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 22)
                                                        <a href="{{ route('outreach-program.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 23)
                                                        <a href="{{ route('technical-extension.show', $row->id) }}" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 29)
                                                        <a href="{{ route('admin-special-tasks.show', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 30)
                                                        <a href="{{ route('special-tasks.show', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 31)
                                                        <a href="{{ route('special-tasks.show', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @elseif($table->id == 32)
                                                        <a href="{{ route('special-tasks.show', $row->id) }}#upload-document" target="_blank" class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</a>
                                                        @endif
                                                    @endif

                                                @endisset
                                            </td>
                                        </tr>
                                        @php
                                            $count++;
                                        @endphp
                                    @empty
                                        <tr>
                                            <td colspan="3">Empty</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php
            $tableCount++;
        @endphp
        @endforeach
    </div>
    {{-- VIew Report --}}
    <div class="modal fade" id="viewReport" tabindex="-1" aria-labelledby="viewReportLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewReportLabel">View Accomplishment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-md-11">
                            <table class="table table-sm table-borderless table-hover" id="columns_value_table">
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row justify-content-center">
                        <div class="col-md-11 h5 font-weight-bold">Documents</div>
                        <div class="col-md-11" id="data_documents">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Submit Report --}}
    <div class="modal fade" id="submitReportModal" tabindex="-1" aria-labelledby="submitReportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submitReportLabel">Submit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">Are you sure you want to submit? Your finalized accomplishment reports will proceed to the college/department/office.</div>
                    </div>
                    <form action="{{ route('to-finalize.store') }}" class="needs-validation" method="POST" novalidate>
                        @csrf
                        @php
                            $count2 = 1;
                        @endphp
                        @foreach ( $report_tables as $table)
                            @if(!array_key_exists($table->id, $report_array))
                                @continue
                            @endif
                            @foreach ($report_array[$table->id] as $row)
                                @isset($row->id)
                                    @if ( count($report_document_checker[$table->id][$row->id]) > 0)
                                        <input id="report-{{ $count2 }}" type="hidden" value="{{ ($row->research_code ?? '*').','.$table->id.','.($row->id ?? '*').','.($row->research_id ?? '*') }}" name="report_values[]">
                                    @endif
                                @else
                                    @if ( count($report_document_checker[$table->id][$row->research_code]) > 0)
                                        <input id="report-{{ $count2 }}" type="hidden" value="{{ ($row->research_code ?? '*').','.$table->id.','.($row->id ?? '*').','.($row->research_id ?? '*') }}" name="report_values[]">
                                    @endif
                                @endisset
                                @php
                                    $count2++;
                                @endphp
                            @endforeach
                        @endforeach
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success mb-2 mr-2">Submit Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <i class="bi bi-arrow-up-circle-fill" id="scrollUpButton" role="button" onclick="topFunction()"></i>
    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $('#adddocbutton').remove();
        $(document).on('click', '.report-view', function(){
            let catID = $(this).data('id');
            let rowID = $(this).data('code');
            let link = $(this).data('url');

            var countColumns = 0;
            var countValues = 0;
			setTimeout(function (){
				var url = "{{ url('reports/tables/data/:id') }}";
				var newlink = url.replace(':id', catID);
				$.get(newlink, function (data){
					data.forEach(function (item){
						countColumns = countColumns + 1;
						$('#columns_value_table').append('<tr id="row-'+countColumns+'" class="report-content d-flex"></tr>')
						$('#row-'+countColumns).append('<td class="report-content font-weight-bold">'+item.name+':</td>');
					});
				});
			}, 1000 );

			setTimeout(function (){
				var url2 = "{{ url('reports/tables/data/:id/:rowID') }}";
				var newlink2 = url2.replace(':id', catID);
				newlink2 = newlink2.replace(':rowID', rowID);
				$.get(newlink2, function (data){
					data.forEach(function (item){
						countValues = countValues + 1;
						if(item == null)
							$('#row-'+countValues).append('<td class="report-content text-left">-  </td>');
						else
							$('#row-'+countValues).append('<td class="report-content text-left">'+item+'</td>');
					});
				});
			}, 1000 );

			setTimeout(function (){
				var url3 = "{{ url('reports/tables/data/documents/:id/:rowID') }}";
				var newlink3 = url3.replace(':id', catID);
				newlink3 = newlink3.replace(':rowID', rowID);
				$.get(newlink3, function (data){
					if(data == false){
						$('#data_documents').append('<a class="report-content btn-link text-dark">No Document Attached</a>');
					}
					else{
						data.forEach(function (item){
							var newlink = link.replace(':filename', item)
								$('#data_documents').append('<a href="'+newlink+'" target="_blank" class="report-content btn btn-success m-1">'+item+'</a>');
						});
					}
				});
			}, 1000 );


        });


        $('.select-submit').on('click', function(){
            var inputId = $(this).data('id');
            var tableId = $(this).data('table-id');
            if(this.checked){
                $('#report-'+inputId).removeAttr('disabled');
            }
            else{
                $('#report-'+inputId).attr('disabled', true);
            }
            var allChecked = 0;
            var flag = true;

            $(".select-submit").each(function(index, element){
                if(this.checked){
                    allChecked++;
                    flag = true;
                }
                else{
                    flag = false;
                    return false;
                }
            });
            if(allChecked == 0){
                $('#table-'+tableId).prop('checked', false);
            }
            if(flag == true){
                $('#table-'+tableId).prop('checked', true);
            }else{
                $('#table-'+tableId).prop('checked', false);
            }

            var allSubmitChecked = 0;
            var flagSubmit = true;
            $(".all-submit").each(function(index, element){
                if(this.checked){
                    allSubmitChecked++;
                    flagSubmit = true;
                }
                else{
                    flagSubmit = false;
                    return false;
                }
            });
            if(allSubmitChecked == 0){
                $('#all-submit').prop('checked', false);
            }
            if(flagSubmit == true){
                $('#all-submit').prop('checked', true);
            }else{
                $('#all-submit').prop('checked', false);
            }

        });
        $('.select-submit-table').on('change', function(){
            var tableId = $(this).data('id');
            if(this.checked){
                $('.table-submit-'+tableId).prop('checked', true);
                $('.table-submit-'+tableId).each(function(){
                    var inputId = $(this).data('id');
                    if(this.checked){
                        $('#report-'+inputId).removeAttr('disabled');
                    }
                    else{
                        $('#report-'+inputId).attr('disabled', true);
                    }
                });
            }
            else{
                $('.table-submit-'+tableId).prop('checked', false);
                $('.table-submit-'+tableId).each(function(){
                    var inputId = $(this).data('id');
                    if(this.checked){
                        $('#report-'+inputId).removeAttr('disabled');
                    }
                    else{
                        $('#report-'+inputId).attr('disabled', true);
                    }
                });
            }

            var allChecked = 0;
            var flag = true;
            $(".select-submit-table").each(function(index, element){
                if(this.checked){
                    allChecked++;
                    flag = true;
                }
                else{
                    flag = false;
                    return false;
                }
            });
            if(allChecked == 0){
                $('#all-submit').prop('checked', false);
            }
            if(flag == true){
                $('#all-submit').prop('checked', true);
            }else{
                $('#all-submit').prop('checked', false);
            }
        });
        $('#all-submit').on('click', function(){
            if(this.checked){
                $('.select-submit-table').prop('checked', true);
                $('.all-submit').prop('checked', true);
                $('.all-submit').each(function(){
                    var inputId = $(this).data('id');
                    if(this.checked){
                        $('#report-'+inputId).removeAttr('disabled');
                    }
                    else{
                        $('#report-'+inputId).attr('disabled', true);
                    }
                });
            }
            else{
                $('.select-submit-table').prop('checked', false);
                $('.all-submit').prop('checked', false);
                $('.all-submit').each(function(){
                    var inputId = $(this).data('id');
                    if(this.checked){
                        $('#report-'+inputId).removeAttr('disabled');
                    }
                    else{
                        $('#report-'+inputId).attr('disabled', true);
                    }
                });
            }
        });

        $('.button-deny').on('click', function () {
            var catID = $(this).data('id');

            var countColumns = 1;

			var urldetails = "{{ url('reports/reject-details/:id') }}";
			var newlink2 = urldetails.replace(':id', catID);
			$.get(newlink2, function (data) {
                $('#deny-'+countColumns).append('<td class="deny-details h5 text-left">'+data.position_name+'</td>');
                countColumns = countColumns + 1;
                $('#deny-'+countColumns).append('<td class="deny-details h5 text-left">'+data.time+'</td>');
                countColumns = countColumns + 1;
                $('#deny-'+countColumns).append('<td class="deny-details h5 text-left">'+data.reason+'</td>');
            });
        });

        $('#viewReport').on('hidden.bs.modal', function(event) {
            $('.report-content').remove();
        });

        $('#viewDeny').on('hidden.bs.modal', function(event) {
            $('.deny-details').remove();
        });

        $(function(){
            if( ($('.doc-incomplete').length != 0) && ($('.doc-complete').length == 0)) {
                $('#submitReport').hide();
                $('#all-submit').hide();
                $('.all-submit').hide();
                $('.select-submit-table').hide();
                $('.select-submit').hide();

            }
            if(($('.doc-incomplete').length != 0) && ($('.doc-complete').length != 0)){
                $('#submitReport').show();

            }
            $('#report_denied').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
          $('.submissions_table').DataTable({
            'aoColumnDefs': [{
                  'bSortable': false,
                  'aTargets': [0], /* 1st one, start by the right */
                  'searchable': false,
              }],
              "order": [],
        } );
        });
    </script>
    <script>
        var scrollUp = document.getElementById("scrollUpButton");
        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollUp.style.display = "block";
            } else {
                scrollUp.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
    <script>
        // auto hide alert
        window.setTimeout(function() {
            $(".temp-alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 4000);
    </script>
    <script>
        $('#collegeFilter').on('change', function () {
            var collegeId = $('#collegeFilter').val();
            var link = "{{ route('submissions.getCollege', ':collegeId') }}";
            var newLink = link.replace(':collegeId', collegeId);
            window.location.replace(newLink);
        });
    </script>
    @endpush

</x-app-layout>
