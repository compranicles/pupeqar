<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Show Announcement Details') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @include('maintenances.navigation-bar')
            </div>
            
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th>Announcement ID</th>
                                    <td>{{ $announcement->id }}</td>
                                </tr>
                                <tr>
                                    <th>Date Created</th>
                                    <td>{{ $announcement->created_at }}</td>
                                </tr>
                                <tr>
                                    <th>Subject</th>
                                    <td>{{ $announcement->subject }}</td>
                                </tr>
                                <tr>
                                    <th>Message</th>
                                    <td>{{ Illuminate\Mail\Markdown::parse($announcement->message) }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if ($announcement->status == 1)
                                            {{ __('Showed') }}
                                        @elseif ($announcement->status == 2)
                                            {{ __('Hidden') }}
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="mb-0">
                            <div class="d-flex justify-content-end align-items-baseline">
                                <a href="{{ route('announcements.index') }}" class="btn btn-secondary">Back to List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</x-app-layout>