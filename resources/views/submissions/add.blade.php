<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Create Submission') }}
        </h2>
    </x-slot>

    <div class="row">
        <div class="container">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <a href="{{ route('submissions.index') }}" class="btn btn-secondary btn-sm">Back</a>
                            <hr>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            {{-- form name --}}
                            <h5>{{ $formDetails->name }}</h5>
                        </div>
                    </div>
                    <form action="{{ route('submissions.store', $formDetails->id) }}" method="post">
                        @csrf
                        <div class="row">
                            {{-- including form --}}
                            @include('submissions.form', ['formFields' => $formFields])
                            @if(count($formFields) != 0)
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <button type="submit" id="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        {{ $formDetails->javascript }}
    </script>
@endpush
</x-app-layout>