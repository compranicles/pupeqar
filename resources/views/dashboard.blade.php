<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="row justify-content-center my-5">
        <div class="col-md-12">
            <div class="card shadow bg-light">
                <div class="card-body bg-white px-5 py-3 border-bottom rounded-top">
                    <div class="mx-3 my-3">
                        <div>
                            <img src="{{ asset('img/android-chrome-192x192.png') }}" width="100" class="d-inline mr-3 mb-3">
                            <h1 class="d-inline align-middle "><b style="white-space: nowrap;">PUP-Taguig</b> Quarterly Accomplishment Report System</h1>
                        </div>
    
                        <h3 class="h3 my-4">
                            Welcome to PUPT-QARS!
                        </h3>

                        <div class="text-muted">
                            The PUP-Taguig Quarterly Accomplishment Report System aims to meet the end users’ requirements which are 
                            (1) to automate and ease the manual process of storing event-related and accomplishment documents of the faculty, 
                            (2) to compile and combine the reports of each faculty, and (3) to generate the overall quarterly faculty reports 
                            for the PUPT head of academic programs (HAP). 
                        </div>
                    </div>
                </div>
                {{-- <div class="row g-0">
                    <div class="col-md-12">
                        <div class="card rounded-0 bg-light border-top">
                            <div class="card-header">
                                <h4 style="color:maroon" class="m-0">Announcements</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-hover p-0 m-0">
                                        <tbody>
                                            @foreach ($announcements as $announcement)
                                                <tr style="cursor: pointer;" class="mb-1 announce" id="messageRow" data-toggle="modal" data-target="#viewMessage" data-id="{{ $announcement->id }}">
                                                    <td style="color:maroon" class="text-center">hap@puptfqrs</td>
                                                    <td>{{ $announcement->title }}</td>
                                                    <td class="text-truncate">{{ $announcement->subject }}<br></td>
                                                    <td>{{ date('F j, Y', strtotime($announcement->created_at)) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewMessage" tabindex="-1" aria-labelledby="viewMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="viewMessageLabel">Modal title</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                  <div class="col">
                      <p id="viewSubject"></p>
                      <p id="viewDatePosted"></p>
                      <p id="viewSender"></p>
                      <p id="viewMessagePosted"></p>
                  </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
    @push('scripts');
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        $(document).ready( function() {
            $(".announce").click(function (e){
                let currID = $(this).data("id");
                $.get("announcement/"+ currID, function(data){
                    const date = new Date(data.created_at);
                    document.getElementById('viewMessageLabel').innerHTML = data.title;
                    document.getElementById('viewSubject').innerHTML = "<b>Subject: </b>"+data.subject;
                    document.getElementById('viewDatePosted').innerHTML = "<b>Date Posted: </b>"+date.toDateString();
                    document.getElementById('viewSender').innerHTML = "<b>From: </b>hap@puptfqrs";
                    document.getElementById('viewMessagePosted').innerHTML = "<b>Message: </b> <br><br>"+ marked(data.message);
                });
            });
        });
        $('#viewMessage').on('hidden.bs.modal', function(event) {
            document.getElementById('viewMessageLabel').innerHTML = "";
            document.getElementById('viewSubject').innerHTML = "";
            document.getElementById('viewDatePosted').innerHTML = "";
            document.getElementById('viewSender').innerHTML = "";
            document.getElementById('viewMessagePosted').innerHTML = "";
        });
    </script>
    @endpush
</x-app-layout>