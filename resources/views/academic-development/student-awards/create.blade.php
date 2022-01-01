<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Student Awards and Recognition') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('student-award.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Student Awards and Recognition</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('student-award.store') }}" method="post">
                            @csrf
                            @include('form', ['formFields' => $studentFields])
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Cancel</a>
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