<x-app-layout>
    <x-slot name="header">
        @include('profile.navigation')
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('profile.workExperience') }}"><i class="bi bi-chevron-double-left"></i>Back to all Work Experience</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3">Voluntary Work</h3>
                        <hr>
                        <!-- FOREACH (If the user has more than 1 work) -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group input-group-sm">
                                    <label for="">Organization</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group input-group-sm">
                                    <label for="">Address</label>
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
                            <div class="col-md-3">
                                <div class="form-group input-group-sm">
                                    <label for="">No. of Hours</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group input-group-sm">
                                    <label for="">Position/Nature of Work</label>
                                    <input type="text" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>