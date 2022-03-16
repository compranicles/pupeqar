<x-app-layout>
    <x-slot name="header">
        @include('profile.navigation')
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Work Experience</h3>
                        <hr>
                        <!-- FOREACH (If the user has more than 1 work experience) -->
                        <fieldset>
                            <!-- Insert "Position Title" inside <legend> -->
                            <legend></legend>
                            <div class="row m-1">
                                <div class="col-md-12">
                                    <div class="form-group input-group-sm">
                                        <label for="">Department/Agency/Office/Company</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group input-group-sm">
                                        <label for="">Inclusive Date</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group input-group-sm">
                                        <label for="">-</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row m-1">
                                <div class="col-md-3">
                                    <div class="form-group input-group-sm">
                                        <input type="checkbox" readonly>
                                        <label for="">Government Service</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-1">
                                <div class="col-md-6">
                                    <div class="form-group input-group-sm">
                                        <label for="">Employment Status</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row m-1">
                                <div class="col-md-4">
                                    <div class="form-group input-group-sm">
                                        <label for="">Salary Grade</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group input-group-sm">
                                        <label for="">Step Increment</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group input-group-sm">
                                        <label for="">Monthly Salary</label>
                                        <input type="text" readonly class="form-control">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>