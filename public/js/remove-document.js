var url = '';
var docId = '';
$('.remove-doc').on('click', function(){
    url = $(this).data('link');   
    docId = $(this).data('id');
});
$('#deletedoc').on('click', function(){
    $.get(url, function (data){
        $('#deleteModal .close').click();
        $('#'+docId).remove();

        $('<div class="alert alert-success mt-3">Document has been removed.</div>')
            .insertBefore('#documentsSection')
            .delay(3000)
            .fadeOut(function (){
                $(this).remove();
            });

        var docCount = $('.documents-display').length
        if(docCount == 0){
            $('.docEmptyMessage').show();
        }
    });
});