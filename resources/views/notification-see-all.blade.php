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
            getSeeAllNotifications();

            setInterval(getSeeAllNotifications, 30000);
        });

        function getSeeAllNotifications(){
            var count = 0;
            var countALL = 0;
            $('.notification-seeall-content').remove();

            $.get('/get-notifications', function (data){
                var countAllColumns = 0;

                var countUnread = 0;
                data.forEach(function(item){
                    

                    countAllColumns ++;
                    $('#notification_seeall_table').append('<tr role="button" id="notification-all-'+countAllColumns+'" class="d-flex notification-seeall-content"></tr>');

                    if(item.data.type == 'received'){
                        if(item.data.accomplishment_type == 'individual'){
                            $('#notification-all-'+countAllColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-all-info-'+countAllColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-all-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="text-success">received</span> your '+item.data.category_name+' accomplishment.'+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div></td>'
                            );
                        }
                        else if(item.data.accomplishment_type == 'department'){
                            $('#notification-all-'+countAllColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-all-info-'+countAllColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-all-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="text-success">received</span> the '+item.data.category_name+' accomplishment'+
                                ' of '+item.data.department_name+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div></td>'
                            );
                        }
                        else if(item.data.accomplishment_type == 'college'){
                            $('#notification-all-'+countAllColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-all-info-'+countAllColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-all-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="text-success">received</span> the '+item.data.category_name+' accomplishment'+
                                ' of '+item.data.college_name+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div></td>'

                            );
                        }
                    }
                    else if(item.data.type == 'returned'){
                        if(item.data.accomplishment_type == 'individual'){
                            $('#notification-all-'+countAllColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-all-info-'+countAllColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-all-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="text-danger">returned</span> your '+item.data.category_name+' accomplishment.'+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div></td>'
                            );
                        }
                        else if(item.data.accomplishment_type == 'department'){
                            $('#notification-all-'+countAllColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-all-info-'+countAllColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-all-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="text-danger">returned</span> the '+item.data.category_name+' accomplishment'+
                                ' of '+item.data.department_name+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div></td>'
                            );
                        }
                        else if(item.data.accomplishment_type == 'college'){
                            $('#notification-all-'+countAllColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-all-info-'+countAllColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-all-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="text-danger">returned</span> the '+item.data.category_name+' accomplishment'+
                                ' of '+item.data.college_name+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div></td>'
                            );
                        }
                    }
                    //
                    else if(item.data.type == 'res-invite'){
                        $('#notification-all-'+countAllColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-all-info-'+countAllColumns+'" data-url="{{ route("research.index") }}" data-id="'+item.id+'" class="text-decoration-none notif-all-row noti-message text-dark">'+
                                item.data.sender+' invited you as Co-Researcher in a Research titled : "'+item.data.title+'"'+
                                '</div>'+
                                '<div><a href="'+item.data.url_accept+'?id='+item.id+'"class="btn btn-sm  btn-primary mr-2">Confirm</a>'+
                                '<a href="'+item.data.url_deny+'?id='+item.id+'"class="btn btn-sm btn-seconday mr-2">Cancel</a></div>'
                                +'<div class="text-muted"><small>'+item.data.date+'</small></div></td>'
                            );
                    }
                    else if(item.data.type == 'ext-invite'){
                        $('#notification-all-'+countAllColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-all-info-'+countAllColumns+'" data-url="{{ route("research.index") }}" data-id="'+item.id+'" class="text-decoration-none notif-all-row noti-message text-dark">'+
                                item.data.sender+' added you in an extension accomplishment. '+
                                '</div>'+
                                '<div><a href="'+item.data.url_accept+'?id='+item.id+'"class="btn btn-sm  btn-primary mr-2">Confirm</a>'+
                                '<a href="'+item.data.url_deny+'?id='+item.id+'"class="btn btn-sm btn-seconday mr-2">Cancel</a></div>'
                                +'<div class="text-muted"><small>'+item.data.date+'</small></div></td>'
                            );
                    }
                    else if(item.data.type == 'res-confirm'){
                        $('#notification-all-'+countAllColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-all-info-'+countAllColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-all-row noti-message text-dark ml-2">'+
                                item.data.sender+' accepted your invitation to be part of Research titled : "'+item.data.title+'".'+
                                '</div>'+
                                '<div class="text-muted ml-2"><small>'+item.data.date+'</small></div></td>'
                            );
                    }
                    else if(item.data.type == 'ext-confirm'){
                        $('#notification-all-'+countAllColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-all-info-'+countAllColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-all-row noti-message text-dark ml-2">'+
                                item.data.sender+' confirmed your invitation to be part of an extension accomplishment.'+
                                '</div>'+
                                '<div class="text-muted ml-2"><small>'+item.data.date+'</small></div></td>'
                            );
                    }
                    else if(item.data.type == 'research'){
                        $('#notification-all-'+countAllColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-all-info-'+countAllColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-all-row noti-message text-dark">'+
                                    'Your research titled '+item.data.research_title+' is due on '+item.data.target_date+'.'+
                                '</div>'+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div></td>'
                            );
                    }
                    else if(item.data.type == 'deadline'){
                        $('#notification-all-'+countAllColumns)
                            .append('<td class="notification-seeall-content">'+
                                '<div id="noti-all-info-'+countAllColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-all-row noti-message text-dark">'+
                                    'Please be informed that the deadline of submission of eQAR is on '+item.data.deadline_date+'.'+
                                    ' You only have '+item.data.days_remaining+' day/s remaining to finalize.'+
                                '</div>'+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div></td>'
                            );
                    }


                    if(item.read_at == null){
                        countUnread++;
                        $('#noti-all-info-'+countAllColumns).addClass("font-weight-bold");
                    }
                   
                    countALL++;
                });
            });
        }

       
        $(document).on('click', '.notif-all-row', function(){
            var id = $(this).data("id");
            var url = $(this).data("url")
            // alert(1);
            window.location.replace('{{ url('') }}'+'/notifications/mark-as-read?u='+url+'&v='+id);
        });
    </script>
@endpush
</x-app-layout>