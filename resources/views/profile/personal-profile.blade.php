<x-app-layout>
    <x-slot name="header">
        @include('profile.navigation')
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Personal Profile</h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Faculty ID No.</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label for="">College/Branch/Campus</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label for="">Program</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Surname</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">First Name</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Middle Name</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="">Birthdate</label>
                                    <input type="text" readonly class="form-control" id="birthdate">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group input-group-sm">
                                    <label for="">Sex</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group input-group-sm">
                                    <label for="">Gender</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group input-group-sm">
                                    <label for="">Residential Address</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group input-group-sm">
                                    <label for="">Permanent Address</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Civil Status</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div><div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label for="">Religion</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('.datepicker').datepicker({
            autoclose: true,
        format: 'mm/dd/yyyy',
        immediateUpdates: true,
        todayBtn: "linked",
        todayHighlight: true
        });
    });
</script>  
@endpush
</x-app-layout>