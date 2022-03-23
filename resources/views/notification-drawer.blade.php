<table class="table table-sm table-borderless" style="width: 100%;" id="notification_nav_table">
    <tbody>
    </tbody>
</table>
</div>
<div class="col-md-12 text-center see_all_div" style="border-top: 2px ridge rgba(169,169,169,0.1); padding-top: 10px;">
    <a href="{{ route('notif.all') }}" id="see_all_notif_link">See all notifications</a>
</div>

{{-- Data that Can be Displayed --}}
    {{-- 
        
        item.data.sender = name of the sender
        item.data.category_name = name of report category
        item.data.url = link to redirect
        item.data.date = when the notification sent.
        item.data.accomplishment_type = individual or department/college
        item.data.type = returned or received (can be added for other notifications)

        if returned
            item.data.reason = reason why 

        if data.read_at == null
            pakilagyan na naka bold para madistinguish yung mga nakita na sa hindi thnks


        nagrereset sya every 1min
        
        maganda kung scrollable din sya hehe
        thankss
        iniisip ko kung modal na lang sya ehh

        please help designing this hehe

    --}}
    
@push('scripts')
    <script>
        $(function(){
            getNotifications();

            setInterval(getNotifications, 30000);
        });

        function getNotifications(){
            var count = 0;
            var countALL = 0;
            $('.notification-content').remove();

            $.get('/get-notifications', function (data){
                var countColumns = 0;

                var countUnread = 0;
                data.forEach(function(item){
                    

                    countColumns ++;
                    $('#notification_nav_table').append('<tr role="button" id="notification-'+countColumns+'" class="d-flex notification-content"></tr>');

                    if(item.data.type == 'received'){
                        if(item.data.accomplishment_type == 'individual'){
                            $('#notification-'+countColumns)
                            .append('<td class="notification-content">'+
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="">received</span> your '+item.data.category_name+' accomplishment.'+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div></td>'
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
                            .append('<td class="notification-content">'+
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="">received</span> the '+item.data.category_name+' accomplishment'+
                                ' of '+item.data.college_name+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div></td>'
                            );
                        }
                    }
                    else if(item.data.type == 'returned'){
                        if(item.data.accomplishment_type == 'individual'){
                            $('#notification-'+countColumns)
                            .append('<td class="notification-content">'+
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="">returned</span> your '+item.data.category_name+' accomplishment.'+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div></td>'
                            );
                        }
                        else if(item.data.accomplishment_type == 'department'){
                            $('#notification-'+countColumns)
                            .append('<td class="notification-content">'+
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="">returned</span> the '+item.data.category_name+' accomplishment'+
                                ' of '+item.data.department_name+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div></td>'
                            );
                        }
                        else if(item.data.accomplishment_type == 'college'){
                            $('#notification-'+countColumns)
                            .append('<td class="notification-content">'+
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark p-2">'+
                                item.data.sender+' <span class="">returned</span> the '+item.data.category_name+' accomplishment'+
                                ' of '+item.data.college_name+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div>' +
                                '</div></td>'
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
                                '<div><a href="'+item.data.url_accept+'?id='+item.id+'"class="btn btn-sm  btn-primary mr-2">Confirm</a>'+
                                '<a href="'+item.data.url_deny+'?id='+item.id+'"class="btn btn-sm btn-seconday mr-2">Cancel</a></div>'
                                +'<div class="text-muted"><small>'+item.data.date+'</small></div></td>'
                            );
                    }
                    else if(item.data.type == 'confirm'){
                        $('#notification-'+countColumns)
                            .append('<td class="notification-content">'+
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark p-2">'+
                                item.data.sender+' accepted your invitation to be part of Research titled : "'+item.data.title+'"'+
                                '</div>'+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div></td>'
                            );
                    }
                    else if(item.data.type == 'research'){
                        $('#notification-'+countColumns)
                            .append('<td class="notification-content">'+
                                '<div id="noti-info-'+countColumns+'" data-url="'+item.data.url+'" data-id="'+item.id+'" class="text-decoration-none notif-row noti-message text-dark">'+
                                    'Your research titled '+item.data.research_title+' is due on '+item.data.target_date+'.'+
                                '</div>'+
                                '<div class="text-muted"><small>'+item.data.date+'</small></div></td>'
                            );
                    }
                    else if(item.data.type == 'deadline'){
                        $('#notification-'+countColumns)
                            .append('<td class="notification-content">'+
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
                        $('#noti-info-'+countColumns).css("color", "white");
                    }
                   
                    countALL++;
                });


                if(countALL == 0){
                    $('#notification_nav_table').append('<tr id="notification-empty" class="notification-content"></tr>');
                    $('#notification-empty')
                        .append('<td class="notification-content text-center text-dark">No Notifications</td>');
                        $('.see_all_div').remove();
                }
            });

            $.get('/notifications/count-not-viewed', function (data){

                $('#notificationCounter').text(data);
                if(data > 0){
                    document.getElementById('notificationCounter').classList.remove('notif-badge'); 
                    document.getElementById('notificationCounter').classList.add('badge-danger'); 
                }
                else{
                    document.getElementById('notificationCounter').classList.add('notif-badge'); 
                    document.getElementById('notificationCounter').classList.remove('badge-danger'); 
                }

            });
        }

        $(document).on('click', '.notif-row', function(){
            var id = $(this).data("id");
            var url = $(this).data("url")
            // alert(1);
            if( url != ''){
                window.location.replace('{{ url('') }}'+'/notifications/mark-as-read?u='+url+'&v='+id);
            }
        });
    </script>
    <script>
        $('#notificationLink').on('click', function() {
            $( this ).toggleClass("active");

            $.get('/notifications/count-reset', function (data){

                $('#notificationCounter').text(data);
                if(data > 0){
                    document.getElementById('notificationCounter').classList.remove('notif-badge'); 
                    document.getElementById('notificationCounter').classList.add('badge-danger'); 
                }
                else{
                    document.getElementById('notificationCounter').classList.add('notif-badge'); 
                    document.getElementById('notificationCounter').classList.remove('badge-danger'); 
                }

            });

        });
    </script>
    <script>
        $(document).click((event) => {
                if (!$(event.target).closest('#notificationDropdown').length) {
                    // the click occured outside
                    if (!$(event.target).closest('.notifDiv').length) {
                        document.getElementById('notificationLink').classList.remove('active'); 
                    }      
                }  
            });
    </script>
@endpush