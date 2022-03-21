<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Notifications') }}
        </h2>
    </x-slot>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                        <table class="table table-sm table-hover" style="width: 100%; overflow:scroll;" id="notification_seeall_table">
                            <tbody>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        $(function(){
            getAllNotifications();
        });

        function getAllNotifications(){
            var count = 0;
            var countALL = 0;
            $('.notification-seeall-content').remove();

            $.get('/get-notifications', function (data){
                var countColumns = 0;

                var countUnread = 0;
                data.forEach(function(item){
                    

                    countColumns ++;
                    $('#notification_nav_table').append('<tr role="button" id="notification-'+countColumns+'" class="d-flex notification-content"></tr>');

                    if(item.data.type == 'received'){
                        if(item.data.accomplishment_type == 'individual'){
                            $('#notification-'+countColumns)
                            .append(
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="">received</span> your '+item.data.category_name+' accomplishment.'+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div>'
                            );
                        }
                        else if(item.data.accomplishment_type == 'department'){
                            $('#notification-'+countColumns)
                            .append(
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="">received</span> the '+item.data.category_name+' accomplishment'+
                                ' of '+item.data.department_name+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div>'
                            );
                        }
                        else if(item.data.accomplishment_type == 'college'){
                            $('#notification-'+countColumns)
                            .append(
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="">received</span> the '+item.data.category_name+' accomplishment'+
                                ' of '+item.data.college_name+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div>'
                            );
                        }
                    }
                    else if(item.data.type == 'returned'){
                        if(item.data.accomplishment_type == 'individual'){
                            $('#notification-'+countColumns)
                            .append(
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="">returned</span> your '+item.data.category_name+' accomplishment.'+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div>'
                            );
                        }
                        else if(item.data.accomplishment_type == 'department'){
                            $('#notification-'+countColumns)
                            .append(
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="">returned</span> the '+item.data.category_name+' accomplishment'+
                                ' of '+item.data.department_name+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div>'
                            );
                        }
                        else if(item.data.accomplishment_type == 'college'){
                            $('#notification-'+countColumns)
                            .append(
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="">returned</span> the '+item.data.category_name+' accomplishment'+
                                ' of '+item.data.college_name+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div>'
                            );
                        }
                    }
                    //
                    else if(item.data.type == 'invite'){
                        $('#notification-'+countColumns)
                            .append('<td class="notification-content">'+
                                '<div id="noti-info-'+countColumns+'" data-url="{{ route("research.index") }}" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark">'+
                                item.data.sender+' invited you as Co-Researcher in a Research titled : "'+item.data.title+'"'+
                                '</div>'+
                                '<div><a href="'+item.data.url_accept+'?id='+item.id+'"class="btn btn-sm notif-row btn-primary mr-2">Confirm</a>'+
                                '<a href="'+item.data.url_deny+'?id='+item.id+'"class="btn btn-sm notif-row btn-seconday mr-2">Cancel</a></div>'
                                +'<div class="text-muted"><small>'+item.data.date+'</small></div></td>'
                            );
                    }
                    else if(item.data.type == 'confirm'){
                        $('#notification-'+countColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-info-'+countColumns+'" data-url="{{ route("research.index") }}" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark p-2">'+
                                item.data.sender+' accepted your invitation to be part of Research titled : "'+item.data.title+'"'+
                                '</div>'+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div></td>'
                            );
                    }
                    else if(item.data.type == 'research'){
                        $('#notification-'+countColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark">'+
                                    'Your research titled '+item.data.research_title+' is due on '+item.data.target_date+'.'+
                                '</div>'+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div></td>'
                            );
                    }
                    else if(item.data.type == 'deadline'){
                        $('#notification-'+countColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark">'+
                                    'Please be informed that the deadline of submission of eQAR is on '+item.data.deadline_date+'.'+
                                    ' You only have '+item.data.days_remaining+' day/s remaining to finalize.'+
                                '</div>'+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div></td>'
                            );
                    }


                    if(item.read_at == null){
                        countUnread++;
                        $('#noti-info-'+countColumns).addClass("font-weight-bold");
                    }
                   
                    countALL++;
                });


                if(countALL == 0){
                    $('#notification_seeall_table').append('<tr id="notification-empty" class="notification-seeall-content"></tr>');
                    $('#notification-empty')
                        .append('<td class="notification-seeall-content text-center">No Notifications</td>');
                }

                $('#notificationCounter').text(countUnread);
                if(countUnread > 0){
                    document.getElementById('notificationCounter').classList.remove('badge-light'); 
                    document.getElementById('notificationCounter').classList.add('badge-danger'); 
                }
            });
        }

       
        $(document).on('click', '.notif-row', function(){
            var id = $(this).data("id");
            var url = $(this).data("url")
            // alert(1);
            window.location.replace('{{ url('') }}'+'/notifications/mark-as-read?u='+url+'&v='+id);
        });
    </script>
@endpush
</x-app-layout>