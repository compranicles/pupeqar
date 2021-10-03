<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Submissions') }}
        </h2>
    </x-slot>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                        @endif
                    </div>
                    {{-- Form selector --}}
                    <form action="{{ route('submissions.select') }}" method="POST">
                        @csrf
                        <div class="row mb-n1 mx-2">
                            <div class="col-lg-2 px-1">
                                <div class="form-group">
                                    <label class="mt-2 ml-1">Select Quarter and Year: </label>
                                </div>
                            </div>
                            <div class="col-lg-1 px-1">
                                <div class="form-group">
                                    <select name="quarterSelect" id="quarterSelect" class="form-control custom-select" required>
                                        <option value="1st">1st</option>
                                        <option value="2nd">2nd</option>
                                        <option value="3rd">3rd</option>
                                        <option value="4th">4th</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1 px-1">
                                <div class="form-group">
                                    <select name="yearSelect" id="yearSelect" class="form-control custom-select"></select>
                                </div>
                            </div>
                            <div class="col-lg-10 px-1">
                                <div class="d-flex flex-column mt-1 mb-1">
                                    <div class="form-group">
                                        <select name="form" class="form-control-lg custom-select" id="form" required>
                                            <option value="" selected disabled>Select Form... </option>
                                            @foreach ($forms as $form)
                                            <option value="{{ $form->id }}">{{ $form->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>  
                                </div>
                            </div>
                            <div class="col-lg-2 px-1">
                                <div class="d-flex flex-column">
                                    <button class="btn btn-lg btn-success" type="submit"><i class="fas fa-plus mr-2"></i>Create</button>
                                </div>   
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {{-- Filter  --}}
                    <form action="{{ route('submissions.index') }}" method="get">
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
                        <div class="col-md-1 px-1 text-md-right">
                            <div class="form-group">
                                <button class="btn btn btn-dark" type="submit"></i>Filter</button>
                        </form>
                            </div>
                        </div>

                        <div class="col-md-1 px-1 text-md-left">
                            <div class="form-group">
                                <form action="{{ route('submissions.index') }}" method="get">
                                    <input type="hidden" name="reset" value="reset">
                                    <button class="btn btn btn-secondary" id="resetFilter" type="submit"></i>Reset</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            {{-- table display --}}
                            <div class="table-responsive text-center">
                                @php
                                    $count = 0;
                                @endphp
                                @forelse ($formsHasSubmission as $form)
                                    <hr>
                                    <h5>
                                        {{ $form->name }}
                                    </h5>
                                    <table class="table table-hover table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                @foreach ($fieldsPerForm[$count][$form->id] as $field)
                                                <th>{{ $field['label'] }}</th>
                                                @endforeach
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($submissionsPerForm[$count][$form->id] as $submission)
                                                <tr>
                                                    <td><input type="checkbox" name="" id=""></td>
                                                    @php
                                                        $data = json_decode($submission['data']);
                                                    @endphp
                                                    @foreach ($data as $line)
                                                    <td>
                                                        @php
                                                            if(is_array($line)){
                                                                foreach ($line as $item) {
                                                                    echo '<div>'.date('m/d/Y',strtotime($item)).'</div>';
                                                                }
                                                            }else{
                                                                echo $line;
                                                            }
                                                        @endphp   
                                                    </td> 
                                                    @endforeach
                                                    <td class="text-nowrap"> 
                                                        <a href="{{ route('submissions.edit', $submission['id']) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                                        <button class="btn btn-danger btn-sm deletebutton" data-toggle="modal" 
                                                                                        data-target="#deleteModal" 
                                                                                        data-id="{{ $submission['id'] }}"
                                                                                        data-name="{{ $submission['form_name'] }}"><i class="far fa-trash-alt"></i></button>
                                                    </td>                                                       
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @php
                                        $count++;
                                    @endphp
                                @empty
                                    <h5>No Submissions Yet</h5>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
@include('submissions.delete')

@push('scripts')
    <script>
        // auto hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
    <script>
        // putting id to form action (delete Modal)
        $(document).on('click', '.deletebutton', function (){
            let currID =  $(this).data('id');
            let url = document.getElementById('submission_delete').action;
            let name = $(this).data('name');
            url = url.replace(':id', currID);
            document.getElementById('submission_delete').action = url;
            document.getElementById('sdisplay').innerHTML = name;
        });
    </script>
    <script>
        let dateDropdown = document.getElementById('yearSelect'); 
        
        let currentYear = new Date().getFullYear();    
        let earliestYear = 1970;     
        while (currentYear >= earliestYear) {      
            let dateOption = document.createElement('option');          
            dateOption.text = currentYear;      
            dateOption.value = currentYear;        
            dateDropdown.add(dateOption);      
            currentYear -= 1;    
       }
    </script>
    <script>
        jQuery(document).ready(function($){
            var today = new Date();
            if(new Date(today.getDate(), 00, 01) <= new Date() && new Date(today.getFullYear(), 02, 31) >= new Date()){
                document.getElementById('quarterSelect').value = "1st";
            }
            else if(new Date(today.getDate(), 03, 01) <= new Date() && new Date(today.getFullYear(), 05, 30) >= new Date()){
                document.getElementById('quarterSelect').value = "2nd";
            }
            else if(new Date(today.getDate(), 06, 01) <= new Date() && new Date(today.getFullYear(), 08, 30) >= new Date()){
                document.getElementById('quarterSelect').value = "3rd";
            }
            else if(new Date(today.getDate(), 09, 01) <= new Date() && new Date(today.getFullYear(), 11, 31) >= new Date()){
                document.getElementById('quarterSelect').value = "4th";
            }
            
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
        });
    </script>
@endpush
</x-app-layout>