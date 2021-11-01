<div>
    <button class="btn btn-primary" id="view_registered_research">View Registered Research</button>
</div>

<script>
    $('#view_registered_research').on('click', function () {
        $(this).closest('.container').append('@yield('registered_research-form')');
 });
</script>