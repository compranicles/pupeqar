<x-app-layout> 
    <div class="container">
            @if (in_array(9, $roles))
                @include('dashboard.superadmin')
            @endif
            @if (in_array(1, $roles) || in_array(3, $roles))
                @include('dashboard.faculty-admin')
            @endif
            @if (in_array(5, $roles))
                @include('dashboard.chairperson')
            @endif
            @if (in_array(6, $roles))
                @include('dashboard.director')
            @endif
            @if (in_array(7, $roles))
                @include('dashboard.sector-head')
            @endif
            @if (in_array(8, $roles))
                @include('dashboard.ipqmso')
            @endif
            @if (in_array(10, $roles))
                @include('dashboard.researcher')
            @endif
            @if (in_array(11, $roles))
                @include('dashboard.extensionist')
            @endif
        </div>
    </div>

    
    
                
    @push('scripts')
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
            document.getElementById("quarter").innerHTML = "Accomplishments reported this quarter " + {{ $currentQuarterYear->current_quarter }} + " of " + {{ $currentQuarterYear->current_year }}; //"Reported accomplishments from " + month1 + ' - ' + month4 + ' ' + new Date().getFullYear();
        }
    </script>
    <script>
        $(function(){
            getLog();
            getLogInd();

            setInterval(getLog, 60000);
            setInterval(getLogInd, 60000);
        });

        function getLog(){
            $('.activity-log-content').remove();

            $.get('/get-dashboard-list', function (data){
                var countColumns = 0;

                data.forEach(function(item){
                    $('#log_activity_table').append('<tr id="activity-log-'+countColumns+'" class="activity-log-content"></tr>');
                    $('#activity-log-'+countColumns)
                        .append('<td class="activity-log-content text-small">'+
                                item.name
                            +'</td>'
                        );
                    $('#activity-log-'+countColumns)
                        .append('<td class="activity-log-content text-small">'+
                                item.subject
                            +'</td>'
                        );
                    $('#activity-log-'+countColumns)
                        .append('<td class="activity-log-content text-small">'+
                                item.created_at
                            +'</td>'
                        );
                    countColumns++;
                });
            });
        }
        function getLogInd(){
            $('.activity-log-indi-content').remove();

            $.get('/get-dashboard-list', function (data){
                var countColumns = 0;

                data.forEach(function(item){
                    $('#log_activity_individual_table').append('<tr id="activity-log-indi-'+countColumns+'" class=" activity-log-indi-content"></tr>');
                    $('#activity-log-indi-'+countColumns)
                        .append('<td class="activity-log-indi-content text-small">'+
                                item.subject
                            +'<div class="text-muted"><small>'+item.created_at+'</small></div></td>'
                        );
                    // $('#activity-log-indi-'+countColumns)
                    //     .append('<td class="activity-log-indi-content text-small">'+
                    //             item.created_at
                    //         +'</td>'
                    //     );
                    countColumns++;
                });
            });
        }
    </script>
    @endpush
</x-app-layout>
