
@php
$table_columns_json = json_encode($table_columns, JSON_FORCE_OBJECT);
$table_contents_json = json_encode($table_contents, JSON_FORCE_OBJECT);
$table_format_json = json_encode($table_format, JSON_FORCE_OBJECT);
@endphp

<!-- These breaks give spaces for the heading formed in the AccomplishmentReportExport -->
<!-- HTML & CSS not working with aligning multiple elements in one cell row -->
{{-- foreach through the format --}}
@foreach ($table_format as $format)
    @if ($format->is_table == "1")
        @if ($format->name != '')
            <h2>{{ $format->name }}</h2>
            <h2>Year Covered: <u>{{ $year }}</u></h2>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm">
                <thead> 
                    <tr>
                        <th>Quarter</th>
                        <th>Sector</th>
                        <th>Delivery Unit</th>
                        <th>Department</th>
                        <th>Name of Employee</th>
                        {{-- load the addtl columns --}}
                        @foreach ($table_columns[$format->id] as $column)
                            <th>{{ $column['name'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($table_contents[$format->id] as $content)
                        @php
                            $data = json_decode($content['report_details'], true);
                            $documents =  json_decode($content['report_documents'], true);
                        @endphp
                        <tr>
                            <td>{{ $content['report_quarter'] }}</td>
                            <td>{{ $content['sector_name'] }}</td>
                            <td>{{ $content['college_name'] }}</td>
                            <td>{{ $content['department_name'] }}</td>
                            <td>{{ $content['faculty_name'] }}</td>
                            @foreach ($table_columns[$format->id] as $column )
                                @if (isset($data[$column['report_column']]))
                                    <td>{{ $data[$column['report_column']] }}</td>
                                @else
                                    @if ($column == 'fund_source' && $data[$column['report_column']] == 0)
                                        <td>Not Paid</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                @endif
                            @endforeach
                            <td><a href="{{ route('report.generate.document-view', $content['id']) }}" target="_blank">View Documents</a></td>
                            <td></td>
                        </tr>
                            
                    @empty
                    <tr>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
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

<p>This report was generated using the PUP eQAR system on {{ date(' m/d/Y h:i:s a') }} </p>