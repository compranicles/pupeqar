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
                        <div class="col-md-12">
                            <div class="table-responsive text-center ">
                                @php
                                    $count = 0;
                                @endphp
                                @foreach ($forms as $form)
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
                                                    <td class="text-nowrap">{{ $submission['last_name'].", ".$submission['first_name']." ".$submission['middle_name'] }}</td>
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
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <hr>
                                    @php
                                        $count++;
                                    @endphp
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>