<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Student Attended Seminars and Trainings') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('student-training.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Student Attended Seminars and Trainings</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('student-training.store') }}" method="post">
                            @csrf
                            @include('form', ['formFields' => $studentFields])
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

@push('scripts')
    <script>
         $('#start_date').on('input', function(){
                var date = new Date($('#start_date').val());
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                // alert([day, month, year].join('-'));
                // document.getElementById("target_date").setAttribute("min", [day, month, year].join('-'));
                document.getElementById('end_date').setAttribute('min', [year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
                $('#end_date').val([year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
            });
    </script>
    <script>
        $('#currency_select_budget').prop('disabled', true);
    </script>
@endpush 
</x-app-layout>

