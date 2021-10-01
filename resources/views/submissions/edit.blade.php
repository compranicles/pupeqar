<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit Submissions') }}
        </h2>
    </x-slot>

<div class="container-fluid">
    <div class="row">
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
                    <form action="{{ route('submissions.update', $submission->id) }}" method="post">
                        <div class="row">
                            @csrf
                            {{-- including form --}}
                            @include('submissions.editform', ['formFields' => $formFields, 'value' => $values])
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>