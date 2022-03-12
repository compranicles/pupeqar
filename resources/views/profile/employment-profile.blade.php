<x-app-layout>
    <x-slot name="header">
        @include('profile.navigation')
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Employment Profile</h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Initial Date of Employment in PUP</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Initial Employment Status</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Present Employment Status</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Date of Last Promotion</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Present Academic Rank</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group input-group-sm">
                                    <label for="">Salary Grade</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group input-group-sm">
                                    <label for="">Step Increment</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <!-- The designation and office must be grouped together in each and every designation. Multiply it if the user has more than 1 designation -->
                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label for="">Designation</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label for="">Office</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <!-- The PLE, year obtained, and validity must be grouped together in each and every PLE. Multiply it if the user has more than 1 PLE -->
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Professional Licensure Examination</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Year Obtained</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Validity</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <!-- The CSE and year obtained must be grouped together in each and every CSE. Multiply it if the user has more than 1 CSE -->
                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label for="">Civil Service Eligibility</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group input-group-sm">
                                    <label for="">Year Obtained</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>