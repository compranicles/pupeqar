
    @php
      $table_columns_json = json_encode($table_columns, JSON_FORCE_OBJECT);
      $table_contents_json = json_encode($table_contents, JSON_FORCE_OBJECT);
      $table_format_json = json_encode($table_format, JSON_FORCE_OBJECT);
    @endphp
    
    <!-- These breaks give spaces for the heading formed in the AccomplishmentReportExport -->
    <!-- HTML & CSS not working with aligning multiple elements in one cell row -->
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    {{-- foreach through the format --}}
    @foreach ($table_format as $format)
    {{-- if its not table(0) output only the name else output name and table--}}
    @if ($format->is_table == "0")
    <h2 class="mt-2">{{ $format->name }}</h2>
    @else
            <h2>{{ $format->name }}</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead> 
                        <tr>
                            @if ($format->is_individual == "1" && $source_type != "individual")
                            <!-- <th>Department</th> -->
                            <th>Name of the Employee</th>
                            @endif
                            @if ($source_type == "individual")
                                <!-- <th>College</th> -->
                                <th>Department</th>
                            @endif
                            {{-- load the addtl columns --}}
                            @foreach ($table_columns[$format->id] as $column)
                                <th>{{ $column['name'] }}</th>
                            @endforeach
                            @if ($source_type != "individual")
                                <th>Supporting Evidence Verified By</th>
                                <th>Status of Supporting Documents</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($table_contents[$format->id] as $content)
                            @php
                                $data = json_decode($content['report_details'], true);
                            @endphp
                            <tr>
                                @if ($format->is_individual == "1" && $source_type != "individual")
                                    <td>
                                        @if ($source_type != "department")
                                            {{ $data['department_id'] }}
                                        @else
                                        @endif
                                    </td>
                                    <td>
                                        {{ $content['faculty_name'] }}
                                    </td>
                                @endif
                                @if ($source_type == "individual")
                                    {{-- <td>{{ $data['college_id'] ?? '' }}</td> --}}
                                    <td>{{ $data['department_id'] ?? ''}}</td>
                                @endif
                                @foreach ($table_columns[$format->id] as $column )
                                    @if (isset($data[$column['report_column']]))
                                        <td>{{ $data[$column['report_column']] }}</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                @endforeach
                            </tr>
                                
                        @empty
                        <tr>
                            @if ($format->is_individual == "1" && $source_type != "individual")
                                    <td>
                                        -
                                    </td>
                                    <td>
                                        -
                                    </td>
                                @endif
                                @if ($source_type == "individual")
                                    <td>-</td>
                                    <td>-</td>
                                @endif
                                @foreach ($table_columns[$format->id] as $column )
                                    <td>-</td>
                                @endforeach
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                    @php
                        $footers = json_decode($format->footers);
                    @endphp
                    @if ($footers != null)
                        @foreach ($footers as $footer)
                        <tr>
                            <td><small>{{ $footer }}</small></td>
                        </tr>
                        <br>
                        @endforeach
                    @else
                        <tr>
                            <td><small></small></td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
            
        @endif

        
    @endforeach