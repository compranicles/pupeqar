<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Technical Extension Program/ Project/ Activity') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('technical-extension.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Technical Extension Programs/ Projects/ Activities</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('technical-extension.store') }}" method="post">
                            @csrf
                            @include('form', ['formFields' => $extensionFields])
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