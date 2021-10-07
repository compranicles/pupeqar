<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Reports') }}
        </h2>
    </x-slot>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-center">
                                Quarterly Accomplishment Report
                            </h3>
                            <hr>
                        </div>
                    </div>
                    {{-- Filter  --}}
                    <form action="{{ route('reports.index') }}" method="get">
                    <div class="row">
                        <div class="col-md-1 text-md-center px-1">
                            <label class="mt-2 mr-n3">Quarter: </label>
                        </div>
                        <div class="col-md-1 px-1">
                            <div class="form-group">
                                <select name="quarterFilter" id="quarterFilter" class="form-control custom-select">
                                    <option value="1st" {{ ($filter['quarter'] == "1st") ? 'selected' : '' }}>1st</option>
                                    <option value="2nd" {{ ($filter['quarter'] == "2nd") ? 'selected' : '' }}>2nd</option>
                                    <option value="3rd" {{ ($filter['quarter'] == "3rd") ? 'selected' : '' }}>3rd</option>
                                    <option value="4th" {{ ($filter['quarter'] == "4th") ? 'selected' : '' }}>4th</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 text-md-center px-1">
                            <label class="mt-2 mr-n3">Year: </label>
                        </div>
                        <div class="col-md-1 px-1">
                            <div class="form-group">
                                <select name="yearFilter" id="yearFilter" class="form-control custom-select"></select>
                            </div>
                        </div>
                        <div class="col-md-1 text-md-center px-1">
                            <label class="mt-2 mr-n3">Form: </label>
                        </div>
                        <div class="col-md-5 px-1">
                            <div class="form-group">
                                <select name="formFilter" id="formFilter" class="form-control custom-select">
                                    <option value="All" {{ ($filter['form'] == "All") ? 'selected' : '' }}>All</option>
                                    @foreach ($forms as $form)
                                    <option value="{{ $form->id }}" {{ ($filter['form'] == $form->id) ? 'selected' : '' }}>{{ $form->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1 text-md-center px-1">
                            <label class="mt-2 mr-n3">Faculty: </label>
                        </div>
                        <div class="col-md-4 px-1">
                            <div class="form-group">
                                <select name="facultyFilter" id="facultyFilter" class="form-control custom-select">
                                    <option value="All" {{ ($filter['faculty'] == 'All') ? 'selected' : '' }}>All</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ ($filter['faculty'] == $user->id) ? 'selected' : '' }}
                                        >{{ $user->last_name.', '.$user->first_name.' '.$user->middle_name.' ' }}{{ ($user->suffix == '') ? '' : $user->suffix }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 text-md-center px-1">

                        </div>
                        <div class="col-md-4 px-1"></div>
                        <div class="col-md-1 px-1 text-md-right">
                            <div class="form-group">
                                <button class="btn btn btn-dark" type="submit"></i>Filter</button>
                        </form>
                            </div>
                        </div>

                        <div class="col-md-1 px-1 text-md-left">
                            <div class="form-group">
                                <form action="{{ route('reports.index') }}" method="get">
                                    <input type="hidden" name="reset" value="reset">
                                    <button class="btn btn btn-secondary" id="resetFilter" type="submit"></i>Reset</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <hr>
                    {{-- Table starts --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive text-center ">
                                @php
                                    $count = 0;
                                @endphp
                                @forelse ($formsHasSubmission as $form)
                                    <h5>
                                        {{ $form->name }}
                                    </h5>
                                    <table class="table table-hover table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>
                                                    
                                                </th>
                                                <th>Name</th>
                                                @foreach ($fieldsPerForm[$count][$form->id] as $field)
                                                <th>{{ $field['label'] }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($submissionsPerForm[$count][$form->id] as $submission)
                                                <tr>
                                                    <td><input type="checkbox" name="" id=""></td>
                                                    <td class="text-nowrap">{{ $submission['last_name'].", ".$submission['first_name']." ".$submission['middle_name'].' ' }} {{ ($submission['suffix'] == '') ? '' : $submission['suffix'] }}</td>
                                                    @php
                                                        $data = json_decode($submission['data'], true);
                                                    @endphp
                                                     @foreach ($fieldsPerForm[$count][$form->id] as $field)
                                                     <td>
                                                        @if(!array_key_exists($field['name'], $data) || $data[$field['name']] == '')
                                                            {{ '-' }}   
                                                        @elseif(is_array($data[$field['name']]))
                                                            @foreach ($data[$field['name']] as $item) {
                                                                {{ date('m/d/Y',strtotime($item)) }}
                                                            @endforeach
                                                        @else
                                                            {{ $data[$field['name']] }}
                                                        @endif 
                                                     </td>
                                                     @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <hr>
                                    @php
                                        $count++;
                                    @endphp
                                @empty
                                    <h4>No Data Available</h4>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript" src="{{ asset('dist/selectize.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('dist/selectize.bootstrap4.css') }}" />
    <script type="text/javascript" src="{{ asset('dist/selectize-plugin-clear.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset("dist/selectize-plugin-clear.css") }}" />
    <script>
        jQuery(document).ready(function($){
            let dateDropdown = document.getElementById('yearFilter'); 
        
            let currentYear = new Date().getFullYear();    
            let earliestYear = 1970;     
            while (currentYear >= earliestYear) {      
                let dateOption = document.createElement('option');          
                dateOption.text = currentYear;      
                dateOption.value = currentYear;        
                dateDropdown.add(dateOption);      
                currentYear -= 1;    
            }
            dateDropdown.value = "{{ $filter['year'] }}";

            $("#formFilter").selectize({
                sortField: "text",
                plugins: ["clear_button"]
            });
            $("#facultyFilter").selectize({
                sortField: "text",
                plugins: ["clear_button"]
            });
        });
    </script>
@endpush

</x-app-layout>