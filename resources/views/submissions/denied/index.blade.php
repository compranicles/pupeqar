<x-app-layout>   
    <x-slot name="header">
            @include('submissions.navigation', compact('roles', 'department_id', 'college_id'))
    </x-slot>
    @if ($message = Session::get('deny-success'))
        <div class="alert alert-success temp-alert">
            <i class="bi bi-check-circle"></i> {{ $message }}
        </div>
    @endif
    @if (in_array(1, $roles)||in_array(2, $roles)||in_array(3, $roles)||in_array(4, $roles))
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>Individual</h5>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="table-responive ">
                        <table class="table table-hover table-sm" id="report_denied">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>College/Branch/Campus/Office</th>
                                    <th>Department</th>
                                    <th>Report Category</th>
                                    <th>Date Reported</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reported_accomplishments as $row)
                                <tr role="button">
                                    <td class="button-deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}"><i class="bi bi-three-dots-vertical"></i></td>
                                    <td class="button-deny text-center">{{ $loop->iteration }}</td>
                                    <td class="button-deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">{{ $row->college_name }}</td>
                                    <td class="button-deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">{{ $row->department_name }}</td>
                                    <td class="button-deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">{{ $row->report_category }}</td>
                                    <td class="button-deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">
                                        {{ date('M d, Y h:i A', strtotime( $row->created_at)) }}
                                    </td>
                                    <td>
                                        <a href="{{ route('report.manage', [$row->id, $row->report_category_id]) }}" target="_blank" class="action-edit" id="view_accomp_documents" data-id="{{ $row->id }}"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i> Edit</a>
                                    </td>
                                </tr>
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (in_array(5, $roles))
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>Department's</h5>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="table-responive ">
                        <table class="table table-hover table-sm" id="report_denied">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Accomplishment Report</th>
                                    <th>Date Reported</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($departamental_or_collegiate_accomplishments as $row)
                                <tr>
                                    <td class="button-deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}"><i class="bi bi-three-dots-vertical"></i></td>
                                    <td class="button-deny text-center" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">{{ $loop->iteration }}</td>
                                    <td class="button-deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">{{ $row->report_category }}</td>
                                    <td class="button-deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">
                                        {{ date('M d, Y h:i A', strtotime( $row->created_at)) }}
                                    </td>
                                    <td>
                                        <a href="{{ route('report.manage', [$row->id, $row->report_category_id]) }}" target="_blank" class="action-edit" id="view_accomp_documents" data-id="{{ $row->id }}"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i> Edit</a>
                                    </td>
                                </tr>
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>Denied/Relayed By Me</h5>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="table-responive">
                        <table class="table table-hover table-sm" id="report_denied_by_me_chair">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Department</th>
                                    <th>Accomplishment Report</th>
                                    <th>Employee</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($denied_by_me as $row)
                                <tr role="button">
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->dean_approval !== 0 ) data-accept="{{ route('chairperson.undo', ':id') }}" @endif data-id="{{ $row->id }}"><i class="bi bi-three-dots-vertical"></i></td>
                                    <td class="button-view text-center" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->dean_approval !== 0 ) data-accept="{{ route('chairperson.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $loop->iteration }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->dean_approval !== 0 ) data-accept="{{ route('chairperson.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $row->department_name }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->dean_approval !== 0 ) data-accept="{{ route('chairperson.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $row->report_category }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->dean_approval !== 0 ) data-accept="{{ route('chairperson.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary button-deny" id="view_accomp_deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">Reason</button>
                                        
                                    </td>
                                </tr>
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>   
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>Denied By Dean</h5>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="table-responive">
                        <table class="table table-hover table-sm" id="report_denied_by_dean">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Accomplishment Report</th>
                                    <th>Employee</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($higher_denied_accomplishments as $row)
                                <tr role="button">
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-relay="{{ route('chairperson.relay', ':id') }}" data-id="{{ $row->id }}"><i class="bi bi-three-dots-vertical"></i></td>
                                    <td class="button-view text-center" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-relay="{{ route('chairperson.relay', ':id') }}" data-id="{{ $row->id }}">{{ $loop->iteration }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-relay="{{ route('chairperson.relay', ':id') }}" data-id="{{ $row->id }}">{{ $row->report_category }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-relay="{{ route('chairperson.relay', ':id') }}" data-id="{{ $row->id }}">{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary button-deny" id="view_accomp_deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">Reason</button>
                                        @if ($row->user_id == auth()->id())
                                            <a href="{{ route('report.manage', [$row->id, $row->report_category_id]) }}" class="btn btn-sm btn-secondary" id="view_accomp_documents" data-id="{{ $row->id }}">Edit</a>
                                        @endif

                                    </td>
                                </tr>
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (in_array(6, $roles))
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>College's</h5>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="table-responive ">
                        <table class="table table-hover table-sm" id="report_denied">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Accomplishment Report</th>
                                    <th>Date Reported</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($departamental_or_collegiate_accomplishments as $row)
                                <tr role="button">
                                    <td class="button-deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}"><i class="bi bi-three-dots-vertical"></i></td>
                                    <td class="button-deny text-center" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">{{ $loop->iteration }}</td>
                                    <td class="button-deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">{{ $row->report_category }}</td>
                                    <td class="button-deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">
                                        {{ date('M d, Y h:i A', strtotime( $row->created_at)) }}
                                    </td>
                                    <td>
                                        <a href="{{ route('report.manage', [$row->id, $row->report_category_id]) }}" class="action-edit" id="view_accomp_documents" data-id="{{ $row->id }}"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i> Edit</a>
                                    </td>
                                </tr>
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>Denied/Relayed By Me</h5>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="table-responive">
                        <table class="table table-hover table-sm" id="report_denied_by_me_dean">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Department</th>
                                    <th>Accomplishment Report</th>
                                    <th>Employee</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($denied_by_me as $row)
                                <tr role="button">
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if( $row->sector_approval == null && $row->chairperson_approval == 1) data-accept="{{ route('dean.undo', ':id') }}" @endif data-id="{{ $row->id }}"><i class="bi bi-three-dots-vertical"></i></td>
                                    <td class="button-view text-center" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if( $row->sector_approval == null && $row->chairperson_approval == 1) data-accept="{{ route('dean.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $loop->iteration }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if( $row->sector_approval == null && $row->chairperson_approval == 1) data-accept="{{ route('dean.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $row->department_name }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if( $row->sector_approval == null && $row->chairperson_approval == 1) data-accept="{{ route('dean.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $row->report_category }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if( $row->sector_approval == null && $row->chairperson_approval == 1) data-accept="{{ route('dean.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary button-deny" id="view_accomp_deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">Reason</button>
                                    </td>
                                </tr>
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>Denied By Sector Head</h5>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="table-responive">
                        <table class="table table-sm table-hover" id="report_denied_by_sector">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Department</th>
                                    <th>Accomplishment Report</th>
                                    <th>Employee</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($higher_denied_accomplishments as $row)
                                <tr role="button">
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-relay="{{ route('dean.relay', ':id') }}" data-id="{{ $row->id }}"><i class="bi bi-three-dots-vertical"></td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-relay="{{ route('dean.relay', ':id') }}" data-id="{{ $row->id }}">{{ $loop->iteration }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-relay="{{ route('dean.relay', ':id') }}" data-id="{{ $row->id }}">{{ $row->department_name }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-relay="{{ route('dean.relay', ':id') }}" data-id="{{ $row->id }}">{{ $row->report_category }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-relay="{{ route('dean.relay', ':id') }}" data-id="{{ $row->id }}">{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
                                   
                                    <td>
                                        <button class="btn btn-sm btn-primary button-deny" id="view_accomp_deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">Reason</button>
                                        @if ($row->user_id == auth()->id())
                                        <a href="{{ route('report.manage', [$row->id, $row->report_category_id]) }}" class="btn btn-sm btn-secondary" id="view_accomp_documents" data-id="{{ $row->id }}">Edit</a>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (in_array(7, $roles))
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>Denied/Relayed By Me</h5>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="table-responive">
                        <table class="table table-hover table-sm table-bordered text-center" id="report_denied_by_me_sector">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>College</th>
                                    <th>Accomplishment Report</th>
                                    <th>Employee</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($denied_by_me as $row)
                                <tr>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->ipqmso_approval == null && $row->dean_approval == 1) data-accept="{{ route('sector.undo', ':id') }}" @endif data-id="{{ $row->id }}"><i class="bi bi-three-dots-vertical"></i></td>
                                    <td class="button-view text-center" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->ipqmso_approval == null && $row->dean_approval == 1) data-accept="{{ route('sector.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $loop->iteration }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->ipqmso_approval == null && $row->dean_approval == 1) data-accept="{{ route('sector.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $row->college_name }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->ipqmso_approval == null && $row->dean_approval == 1) data-accept="{{ route('sector.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $row->report_category }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->ipqmso_approval == null && $row->dean_approval == 1) data-accept="{{ route('sector.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary button-deny" id="view_accomp_deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">Reason</button>
                                    </td>
                                </tr>
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>Denied By IPQMSO</h5>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="table-responive ">
                        <table class="table table-sm table-hover" id="report_denied_by_ipqmso">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>College</th>
                                    <th>Accomplishment Report</th>
                                    <th>Employee</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($higher_denied_accomplishments as $row)
                                <tr>
                                    <td><i class="bi bi-three-dots-vertical"></i></td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row->college_name }}</td>
                                    <td>{{ $row->report_category }}</td>
                                    <td>{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
                                   
                                    <td>
                                        <button class="btn btn-sm btn-primary button-deny" id="view_accomp_deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">Reason</button>
                                        
                                        @if ($row->user_id == auth()->id())
                                        <a href="{{ route('report.manage', [$row->id, $row->report_category_id]) }}" class="btn btn-sm btn-secondary" id="view_accomp_documents" data-id="{{ $row->id }}">Edit</a>
                                        @else
                                            <button class="btn btn-sm btn-primary button-view" id="viewButton" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-relay="{{ route('sector.relay', ':id') }}" data-id="{{ $row->id }}">Details</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if (in_array(8, $roles))
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>Denied</h5>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="table-responive">
                        <table class="table table-hover table-sm" id="report_denied_by_me_sector">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Department</th>
                                    <th>Accomplishment Report</th>
                                    <th>Employee</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($denied_by_me as $row)
                                <tr>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->sector_approval == 1 ) data-accept="{{ route('ipqmso.undo', ':id') }}" @endif data-id="{{ $row->id }}"><i class="bi bi-three-dots-vertical"></i></td>
                                    <td class="button-view text-center" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->sector_approval == 1 ) data-accept="{{ route('ipqmso.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $loop->iteration }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->sector_approval == 1 ) data-accept="{{ route('ipqmso.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $row->department_name }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->sector_approval == 1 ) data-accept="{{ route('ipqmso.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $row->report_category }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->sector_approval == 1 ) data-accept="{{ route('ipqmso.undo', ':id') }}" @endif data-id="{{ $row->id }}">{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary button-deny" id="view_accomp_deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">Reason</button>
                                        <button class="btn btn-sm btn-primary button-view" id="viewButton" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" @if($row->sector_approval == 1 ) data-accept="{{ route('ipqmso.undo', ':id') }}" @endif data-id="{{ $row->id }}">Details</button>
                                    </td>
                                </tr>
                                @empty
                                    
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="modal fade" id="viewDeny" tabindex="-1" aria-labelledby="viewDenyLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDenyLabel">Reason for Denied Accomplishment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-sm table-borderless" id=" view_deny_table">
                            <tr id="deny-1">
                                <td class="font-weight-bold">Denied By:</td>
                            </tr>
                            <tr id="deny-2">
                                <td class="font-weight-bold">Date:</td>
                            </tr>
                            <tr id="deny-3">
                                <td class="font-weight-bold">Reason:</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="viewReport" tabindex="-1" aria-labelledby="viewReportLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewReportLabel">View Denied Accomplishment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <table class="table table-sm table-borderless" id="columns_value_table">
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
                <div class="ml-auto" id="review_btn_undo">
                </div>
                <div class="ml-1" id="review_btn_relay">
                </div>
                <span style="display: inline-block;
                border-left: 1px solid #ccc;
                margin: 0px 20px 0px 20px;;
                height: 30px;"></span>
                <div class="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $('.button-deny').on('click', function () {
            var catID = $(this).data('id');
            
            var countColumns = 1;
            $.get('/reports/reject-details/'+catID, function(data){
                $('#deny-'+countColumns).append('<td class="deny-details text-left">'+data.position_name+'</td>');
                countColumns = countColumns + 1;
                $('#deny-'+countColumns).append('<td class="deny-details text-left">'+data.time+'</td>');
                countColumns = countColumns + 1;
                $('#deny-'+countColumns).append('<td class="deny-details text-left">'+data.reason+'</td>');
            });
        });

        $('.button-view').on('click', function(){
            var catID = $(this).data('id');
            var link = $(this).data('url');
            var accept = $(this).data('accept');
            var relay = $(this).data('relay');
            var countColumns = 0;
            $.get('/reports/data/'+catID, function (data){
                Object.keys(data).forEach(function(k){
                    countColumns = countColumns + 1;
                    $('#columns_value_table').append('<tr id="row-'+countColumns+'" class="report-content"></tr>')
                    $('#row-'+countColumns).append('<td class="report-content font-weight-bold">'+k+':</td>');
                    $('#row-'+countColumns).append('<td class="report-content">'+data[k]+'</td>');
                });
            });
            $.get('/reports/docs/'+catID, function (data) {
                data.forEach(function (item){
                    var newlink = link.replace(':filename', item)
                    $('#data_documents').append('<a href="'+newlink+'" target="_blank" class="report-content h5 m-1 btn btn-primary">'+item+'<a/>');
                });
            });
            if(typeof accept == 'undefined' && typeof relay != 'undefined')
                $('#review_btn_relay').append('<a href="'+relay.replace(':id', catID)+'" class="btn btn-success report-content">Relay</a>');
            if(typeof relay == 'undefined' && typeof accept != 'undefined')
                $('#review_btn_undo').append('<a href="'+accept.replace(':id', catID)+'" class="btn btn-dark report-content">Undo</a>');
        });


        $('#viewReport').on('hidden.bs.modal', function(event) {
            $('.report-content').remove();
        });


        $('#viewDeny').on('hidden.bs.modal', function(event) {
            $('.deny-details').remove();
        });

        $(function(){
            // if( $('.doc-incomplete').length != 0)
            //     $('#submitReport').remove();
            $('#report_denied').DataTable();
            $('#report_denied_by_me_chair').DataTable();
            $('#report_denied_by_dean').DataTable();
            $('#report_denied_by_me_dean').DataTable();
            $('#report_denied_by_sector').DataTable();
            $('#report_denied_by_me_sector').DataTable();
            $('#report_denied_by_ipqmso').DataTable();
        });
    </script>
    <script>
        // auto hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
    @endpush
</x-app-layout>