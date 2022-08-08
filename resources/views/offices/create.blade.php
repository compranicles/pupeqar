<x-app-layout>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h3>Set Up Your Account</h3>
                <p>
                    <a class="back_link" href="{{ session('url') ? url(session('url')) : route('account') }}"><i class="bi bi-chevron-double-left"></i>Back to my account.</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('offices.store') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="">I am a/an {{ $role }} of:</label>
                                    <select name="cbco[]" id="cbco">
                                        <option value="">Choose...</option>
                                    </select>
                                    <br>
                                </div>
                                <div class="col-md-12">
                                    <p>Do you have a designation as {{ $role == "Admin" ? 'Faculty' : 'Admin' }}?</p>
                                    <div class="form-group input-group-md">
                                        <label for="yes">Yes</label>
                                        <input type="checkbox" name="yes" id="yes" {{ $existingCol2 != null ? 'checked' : '' }}>
                                        @if ($role == "Admin")
                                            <!-- If the current role is admin, then the designee type is faculty -->
                                            <!-- Role in seeder -->
                                            <input type="hidden" name="role" value="3"> 
                                            <!-- Role as type -->
                                            <input type="hidden" name="role_type" value="A">
                                            <input type="hidden" name="designee_type" value="F">
                                        @elseif ($role == "Faculty")
                                            <input type="hidden" name="role" value="1">
                                            <input type="hidden" name="role_type" value="F">
                                            <input type="hidden" name="designee_type" value="A">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12 designation-div">
                                    <label for="designee_cbco">As {{ $role == "Admin" ? 'a Faculty Designee' : 'an Admin Designee' }}, please select your designated College/Branch/Campus/Office:</label>
                                    <select name="designee_cbco[]" id="designee_cbco">
                                        <option value="">Choose...</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <a href="{{ session('url') ? url(session('url')) : route('account') }}" class="btn btn-secondary mr-2">Cancel</a>
                                            <button type="submit" id="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('dist/selectize.min.js') }}"></script>
    <script>
        $(function() {
            $('.designation-div').hide();
            $("#cbco").selectize({
                maxItems: null,
                valueField: 'id',
                labelField: 'name',
                sortField: "name",
                searchField: "name",
                options: @json($cbco),
                items: @json($existingCol),
            });
            $("#designee_cbco").selectize({
                maxItems: null,
                valueField: 'id',
                labelField: 'name',
                sortField: "name",
                searchField: "name",
                options: @json($cbco),
                items: @json($existingCol2),
            });

            if ($('#yes').prop('checked')) {
                $('.designation-div').show();
            } else {
                $('.designation-div').hide();
            }
        });
    </script>
    <script>
        $('#yes').on('input', function () {
            if ($('#yes').prop('checked')) {
                $('.designation-div').show();
            } else {
                $('.designation-div').hide();
            }
        });

    </script>
    @endpush
</x-app-layout>
