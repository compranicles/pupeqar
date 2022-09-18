<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Report Column: '.$column_info->name) }}
        </h2>
    </x-slot>
     
    <div class="container mt-n4">
        
        <div class="row mt-3 mb-3">
            <div class="col-md-12">
                @include('maintenances.navigation-bar')
            </div>
            <div class="col-md-12 d-flex">
                <h2 class="font-weight-bold mb-2">Report Modals > Categories > Content > Column</h2>
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('maintenance.generate.type') }}">Report Modals</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('maintenance.generate.view', $type_id) }}">Categories</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('maintenance.generate.edit', ['type_id' => $type_id, 'table_id' => $table_id]) }}">Content</a></li>
                    <li class="breadcrumb-item active">Edit Column</li>
                </ol>
                </nav>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Success Message --}}
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                        @endif

                        {{-- Table for displaying --}}
                        <form action="{{ route('maintenance.generate.save-column', ['type_id' => $type_id, 'table_id' => $table_id, 'column_id' => $column_id]) }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" value="{{ old("name", $column_info->name) }}" class="form-control">
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <button type="submit" class="btn btn-success ml-auto">Save</button>
                                        </div>
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