<x-app-layout>
    <div class="container db-container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    @IsReporting
                    <div class="col-md-4 mb-3">
                        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px;">
                            <div class="d-flex p-3">
                                <div>
                                    <h4 class="text-left">{{ $totalReports }}</h4>
                                    <p class="text-left" id="quarter"></p>
                                </div>
                                <i class="far fa-star home-icons" style="padding-left: 14px; padding-top: 15px;"></i>
                            </div>
                        </div>
                    </div>
                    @endIsReporting
                    @FacultyAdmin
                    <div class="col-md-4 mb-3">
                        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px;">
                            <div class="d-flex p-3">
                                <div>
                                    <h4 class="text-left">{{ $department_reported }}</h4>
                                    <p class="text-left">Departments you reported with within this quarter</p>
                                </div>
                                <i class="far fa-building home-icons" style="padding-left: 90px; padding-top: 8px;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px;">
                            <div class="d-flex p-3">
                                <div>
                                    <h4 class="text-left">{{ $cbco_reported }}</h4>
                                    <p class="text-left">College/Branch/Campus/Offices you reported with within this quarter</p>
                                </div>
                                <i class="far fa-building home-icons" style="padding-left: 14px; padding-top: 8px;"></i>
                            </div>
                        </div>
                    </div>
                    @endFacultyAdmin
                    @IsReceiving
                    <div class="col-md-4 mb-3">
                        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px;">
                            <div class="d-flex p-3">
                                <div>
                                    @chairperson
                                    <h4 class="text-left">{{ $chairpersonReceived }}</h4>
                                    @endchairperson
                                    @director
                                    <h4 class="text-left">{{ $deanReceived }}</h4>
                                    @enddirector
                                    @sectorHead
                                    <h4 class="text-left">{{ $vpReceived }}</h4>
                                    @endsectorHead
                                    @ipqmso
                                    <h4 class="text-left">{{ $ipqmsoReceived }}</h4>
                                    @endipqmso
                                    <p class="text-left" id="received">Accomplishments received this quarter</p>
                                </div>
                                <i class="bi bi-collection home-icons" style="padding-left: 14px; padding-top: 8px;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px;">
                            <div class="d-flex p-3">
                                <div>
                                    @chairperson
                                    <h4 class="text-left">{{ $chairpersonNotReceived }}</h4>
                                    @endchairperson
                                    @director
                                    <h4 class="text-left">{{ $deanNotReceived }}</h4>
                                    @enddirector
                                    @sectorHead
                                    <h4 class="text-left">{{ $vpNotReceived }}</h4>
                                    @endsectorHead
                                    @ipqmso
                                    <h4 class="text-left">{{ $ipqmsoNotReceived }}</h4>
                                    @endipqmso
                                    <p class="text-left" id="notReceived">Accomplishments need to receive</p>
                                </div>
                                <i class="bi bi-collection home-icons" style="padding-left: 14px; padding-top: 8px;"></i>
                            </div>
                        </div>
                    </div>
                    @endIsReceiving
                    @chairperson
                    <div class="col-md-4 mb-3">
                        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px;">
                            <div class="d-flex p-3">
                                <div>
                                    <h4 class="text-left">{{ $chairpersonReturned }}</h4>
                                    <p class="text-left" id="returned">Accomplishments returned by you this quarter</p>
                                </div>
                                <i class="bi bi-arrow-left-square home-icons" style="padding-left: 14px; padding-top: 8px;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px;">
                            <div class="d-flex p-3">
                                <div>
                                    <h4 class="text-left">{{ $deanReturned }}</h4>
                                    <p class="text-left" id="deanReturned">Department accomplishments returned this quarter</p>
                                </div>
                                <i class="bi bi-arrow-left-square home-icons" style="padding-left: 14px; padding-top: 8px;"></i>
                            </div>
                        </div>
                    </div>
                    @endchairperson
                    @director
                    <div class="col-md-4 mb-3">
                        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px;">
                            <div class="d-flex p-3">
                                <div>
                                    <h4 class="text-left">{{ $deanReturned }}</h4>
                                    <p class="text-left" id="deanReturned">Accomplishments returned by you this quarter</p>
                                </div>
                                <i class="bi bi-arrow-left-square home-icons" style="padding-left: 14px; padding-top: 8px;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px;">
                            <div class="d-flex p-3">
                                <div>
                                    <h4 class="text-left">{{ $sectorReturned }}</h4>
                                    <p class="text-left" id="sectorReturned">College accomplishments returned this quarter</p>
                                </div>
                                <i class="bi bi-arrow-left-square home-icons" style="padding-left: 14px; padding-top: 8px;"></i>
                            </div>
                        </div>
                    </div>
                    @enddirector
                    @sectorHead
                    <div class="col-md-4 mb-3">
                        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px;">
                            <div class="d-flex p-3">
                                <div>
                                    <h4 class="text-left">{{ $vpReturned }}</h4>
                                    <p class="text-left">Accomplishments returned by you this quarter</p>
                                </div>
                                <i class="bi bi-arrow-left-square home-icons" style="padding-left: 14px; padding-top: 8px;"></i>
                            </div>
                        </div>
                    </div>
                    @endsectorHead
                    @ipqmso
                    <div class="col-md-4 mb-3">
                        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px;">
                            <div class="d-flex p-3">
                                <div>
                                    <h4 class="text-left">{{ $ipqmsoReturned }}</h4>
                                    <p class="text-left">Accomplishments returned by you this quarter</p>
                                </div>
                                <i class="bi bi-arrow-left-square home-icons" style="padding-left: 14px; padding-top: 8px;"></i>
                            </div>
                        </div>
                    </div>
                    @endipqmso
                </div>
            </div>
        </div>
        <hr>
        @ipqmso
        <div class="row">
            <div class="col-md-6">
                <div class="card card-vertical">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="h5">Activity Log</div>
                            </div>
                            <div class="col-md-2">
                                <a href="" class="btn btn-link btn-sm">View All</a>                                
                            </div>
                            <div class="col-md-12">
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped text-center table-bordered fixed_header" id="log_activity_table">
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endipqmso
    </div>

    <script>
        const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];

        const d = new Date();
        if (d.getMonth() == 0 || d.getMonth() == 1 ||d.getMonth() == 2) {
            var month1 = month[0];
            var month4 = month[2];
        }
        else if (d.getMonth() == 3 || d.getMonth() == 4 ||d.getMonth() == 5) {
            var month1 = month[3];
            var month4 = month[5];
        }
        else if (d.getMonth() == 6 || d.getMonth() == 7 ||d.getMonth() == 8) {
            var month1 = month[6];
            var month4 = month[8];
        }
        else if(d.getMonth() == 9 || d.getMonth() == 10 ||d.getMonth() == 11){
            var month1 = month[9];
            var month4 = month[11];
        }

        if (document.getElementById("quarter") != null) {
            document.getElementById("quarter").innerHTML = "Accomplishments reported this quarter " + {{$quarter}} + " of " + new Date().getFullYear(); //"Reported accomplishments from " + month1 + ' - ' + month4 + ' ' + new Date().getFullYear();
        }
    </script>
    <script>
        $(function(){
            getLog();

            setInterval(getLog, 60000);
        });

        function getLog(){
            $('.activity-log-content').remove();

            $.get('/get-dashboard-list', function (data){
                var countColumns = 0;

                data.forEach(function(item){
                    $('#log_activity_table').append('<tr id="activity-log-'+countColumns+'" class=" activity-log-content"></tr>');
                    $('#activity-log-'+countColumns)
                        .append('<td class="activity-log-content">'+
                                item.name
                            +'</td>'
                        );
                    $('#activity-log-'+countColumns)
                        .append('<td class="activity-log-content">'+
                                item.subject
                            +'</td>'
                        );
                    $('#activity-log-'+countColumns)
                        .append('<td class="activity-log-content">'+
                                item.created_at
                            +'</td>'
                        );
                    countColumns++;
                });
            });
        }
    </script>
</x-app-layout>
