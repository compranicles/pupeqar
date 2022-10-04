
<div class="card">
    <h5 class="card-header"><i class="bi bi-megaphone-fill mr-2" style="color: #820001;"></i><strong>Announcements</strong></h5>
    <div class="card-body">
        <table style="background-color: white;">
            @php $i = 0; @endphp
            @forelse($announcements ?? [] as $announcement)
            <thead>
                <tr>
                    <th role="button" style="color: #373E45; {{ $i == 0 ? ' ' : 'border-top: 1px solid #dee2e6; padding-top: 8px;' }}"
                            data-bs-toggle="modal" data-bs-target="#announcementModal" data-bs-subject="{{ $announcement->subject }}"
                            data-bs-message="{{ $announcement->message }}" data-bs-date="{{ date( 'F j, Y', strtotime($announcement->updated_at)) }}">
                        <small class="mb-2" style="color: var(--gray-dark)">{{ date( "F j, Y", strtotime($announcement->updated_at)) }}</small> &#8226;
                        <small class="mb-2">{{ $diff = Carbon\Carbon::parse($announcement->updated_at)->diffForHumans() }}</small>
                        <br>
                        {{$announcement->subject}}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="home-report-list" style="padding-bottom: 10px;"><small>{{substr_replace($announcement->message, "...", 100)}}</small></td>
                </tr>
            </tbody>
            @php $i++; @endphp
            @empty
                <p class="align-middle text-center">No announcements to show.</p>
            @endforelse
        </table>
    </div>
</div>
