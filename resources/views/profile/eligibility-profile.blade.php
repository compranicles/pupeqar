<x-app-layout>
    <x-slot name="header">
        @include('profile.navigation')
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Eligibility</h3>
                        <hr>
                        
                        <div class="row">
                            <!-- FOREACH (If the user has more than 1 eligibility) -->
                            <div class="col-md-6">
                                <fieldset>
                                    <!-- Insert "Eligibility Exam" inside <legend> -->
                                    <legend></legend>
                                    <div class="row m-1">
                                        <div class="col-md-6">
                                            <div class="form-group input-group-sm">
                                                <label for="">Rating</label>
                                                <input type="text" readonly class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row m-1">
                                        <div class="col-md-6">
                                            <div class="form-group input-group-sm">
                                                <label for="">Date of Exam/Conferment</label>
                                                <input type="text" readonly class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group input-group-sm">
                                                <label for="">Place of Exam/Conferment</label>
                                                <input type="text" readonly class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- LICENSE (IF APPLICABLE) -->
                                    <div class="row m-1">
                                        <div class="col-md-12">
                                            <div class="form-group input-group-sm">
                                                <label for="">License Number</label>
                                                <input type="text" readonly class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group input-group-sm">
                                                <label for="">Date of Validity</label>
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
        </div>
    </div>
</x-app-layout>