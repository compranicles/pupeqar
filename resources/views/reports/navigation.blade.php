<div class="row justify-content-center">
    <div class="col-md-11">
        <div class="row">
            <div class="col-md-4">
                {{-- My Accomplishments --}}
                <h6 style="font-weight: bold; color: #eeb510">VIEW SUBMITTED ACCOMPLISHMENTS</h6>
                <p>
                    <a href="{{ route('reports.consolidate.myaccomplishments') }}" class="submission-menu {{ request()->routeIs('reports.consolidate.myaccomplishments') || request()->routeIs('reports.consolidate.myaccomplishments.*') ? 'active' : '' }} ">My Accomplishments</a>
                </p>
            </div>
            <div class="col-md-3">
                @if (in_array(10, $roles) || in_array(11, $roles) || in_array(5, $roles) || in_array(6, $roles) ||
                    in_array(7, $roles) || in_array(8, $roles) || in_array(12, $roles) || in_array(13, $roles))
                <h6 style="font-weight: bold; color: #eeb510">REVIEW ACCOMPLISHMENTS</h6>
                @endif
                @if (in_array(5, $roles))
                <a href="{{ route('chairperson.index') }}" class="submission-menu {{ request()->routeIs('chairperson.index') ? 'active' : ''}}">Department Level</a><br>
                @endif
                @if (in_array(10, $roles))
                <a href="{{ route('researcher.index') }}" class="submission-menu {{ request()->routeIs('researcher.index') ? 'active' : ''}}">Research & Invention</a><br>
                @endif
                @if (in_array(11, $roles))
                <a href="{{ route('extensionist.index') }}" class="submission-menu {{ request()->routeIs('extensionist.index') ? 'active' : ''}}">Extensions</a><br>
                @endif
                @if (in_array(6, $roles) || in_array(12, $roles))
                <a href="{{ route('director.index') }}" class="submission-menu {{ request()->routeIs('director.index') ? 'active' : ''}}">College Level</a><br>
                @endif
                @if (in_array(7, $roles) || in_array(13, $roles))
                <a href="{{ route('sector.index') }}" class="submission-menu {{ request()->routeIs('sector.index') ? 'active' : ''}}">Sector Level</a><br>
                @endif
                @if (in_array(8, $roles))
                <a href="{{ route('ipo.index') }}" class="submission-menu {{ request()->routeIs('ipo.index') ? 'active' : ''}}">IPO Level</a><br>
                @endif
            </div>
            <div class="col-md-4">
                @if (in_array(10, $roles) || in_array(11, $roles) || in_array(5, $roles) || in_array(6, $roles) ||
                    in_array(7, $roles) || in_array(8, $roles))
                <h6 style="font-weight: bold; color: #eeb510">CONSOLIDATE ACCOMPLISHMENTS</h6>
                @endif
                @if (in_array(5, $roles))
                {{-- Departments' --}}
                    @forelse ( $departments as $row)
                        <a href="{{ route('reports.consolidate.department', $row->department_id) }}" class="submission-menu  {{ isset($id) ? ($row->department_id == $id ||  request()->routeIs('reports.consolidate.department.$id') ? 'active' : '') : '' }}">  
                            {{ $row->code }} Department
                        </a><br>
                    @empty

                    @endforelse 
                @endif

                {{-- Researchers --}}
                @if (in_array(10, $roles))
                    @forelse ( $departmentsResearch as $row)
                        <a href="{{ route('reports.consolidate.research', $row->college_id) }}" class="submission-menu {{ isset($id) ? ($row->college_id == $id && request()->routeIs('reports.consolidate.research') ? 'active' : '') : '' }}">
                            {{ $row->code }} Research & Invention
                        </a><br>
                    @empty
                    @endforelse
                @endif

                {{-- Extensionist --}}
                @if (in_array(11, $roles))
                    @forelse ( $departmentsExtension as $row)
                        <a href="{{ route('reports.consolidate.extension', $row->college_id) }}" class="submission-menu {{ isset($id) ? ($row->college_id == $id && request()->routeIs('reports.consolidate.extension') ? 'active' : '') : '' }}">
                            {{ $row->code }} Extensions
                        </a><br>
                    @empty
                    @endforelse
                @endif

                {{-- Colleges/Branches/Offices --}}
                @if (in_array(6, $roles) || in_array(12, $roles))
                    @forelse ( $colleges as $row)
                        <a href="{{ route('reports.consolidate.college', $row->college_id) }}" class="submission-menu  {{ isset($id) ? ($row->college_id == $id && request()->routeIs('reports.consolidate.college') ? 'active' : '') : '' }} ">
                            {{ $row->code }} College
                        </a><br>
                    @empty
                    @endforelse
                @endif

                {{-- Sectors/VPs --}}
                @if (in_array(7, $roles) || in_array(13, $roles))
                    @forelse ( $sectors as $row)
                        <a href="{{ route('reports.consolidate.sector', $row->sector_id) }}" class="submission-menu {{ request()->routeIs('reports.consolidate.sector') || request()->routeIs('reports.consolidate.sector.*') ? 'active' : ''}}">
                            {{ $row->code }} Sector
                        </a><br>
                    @empty
                    @endforelse
                @endif

                {{-- IPQMSOs --}}
                @if (in_array(8, $roles))
                    <a href="{{ route('reports.consolidate.ipqmso') }}" class="submission-menu {{ request()->routeIs('reports.consolidate.ipqmso') || request()->routeIs('reports.consolidate.ipqmso.*') ? 'active' : ''}}">
                        IPO Level
                    </a>  
                @endif
            </div>
        </div>
    </div>
</div>