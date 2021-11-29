<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Award and Recognition Received by the College and Department') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('college-department-award.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Awards and Recognition Received by the College and Department</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('college-department-award.store') }}" method="post">
                            @csrf
                            @include('form', ['formFields' => $awardFields])
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <button type="submit" id="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>