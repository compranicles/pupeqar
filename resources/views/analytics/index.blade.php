<x-app-layout>
    @section('title', 'Analytics |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Analytics</h2>
            </div>
        </div>
        <div class="row mt-3">
            {{-- Seminar Card --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Hours in Attending Seminars</h5>
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link " id="pills-seminar-quarter-tab" data-toggle="pill" href="#pills-seminar-quarter" role="tab" aria-controls="pills-seminar-quarter" aria-selected="false">Quarterly</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-seminar-year-tab" data-toggle="pill" href="#pills-seminar-year" role="tab" aria-controls="pills-seminar-year" aria-selected="true">Annually</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade " id="pills-seminar-quarter" role="tabpanel" aria-labelledby="pills-seminar-quarter-tab">
                                <div>
                                    <canvas id="seminar-quarter-chart"></canvas>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="pills-seminar-year" role="tabpanel" aria-labelledby="pills-seminar-year-tab">
                                <div>
                                    <canvas id="seminar-year-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Seminar Card --}}
            {{-- Training Card --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Hours in Attending Trainings</h5>
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link " id="pills-training-quarter-tab" data-toggle="pill" href="#pills-training-quarter" role="tab" aria-controls="pills-training-quarter" aria-selected="false">Quarterly</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-training-year-tab" data-toggle="pill" href="#pills-training-year" role="tab" aria-controls="pills-training-year" aria-selected="true">Annually</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade " id="pills-training-quarter" role="tabpanel" aria-labelledby="pills-training-quarter-tab">
                                <div>
                                    <canvas id="training-quarter-chart"></canvas>
                                </div>
                            </div>
                            <div class="tab-pane fade show active" id="pills-training-year" role="tabpanel" aria-labelledby="pills-training-year-tab">
                                <div>
                                    <canvas id="training-year-chart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Training Card --}}
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- seminar year --}}
    <script>
        const seminarYearData = {
            labels: JSON.parse('{!! json_encode($years) !!}'),
            datasets: [{
                label: 'No. of Seminar Hours per Year',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: JSON.parse('{!! json_encode($seminar_hours_per_year) !!}'),
            }]
        };

        const seminarYearConfig = {
            type: 'line',
            data: seminarYearData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const seminarYearChart = new Chart(
            document.getElementById('seminar-year-chart'),
            seminarYearConfig
        );
    </script>
    {{-- seminar quarter --}}
    <script>
        const seminarQuarterData = {
            labels: JSON.parse('{!! json_encode($quarters) !!}'),
            datasets: [{
                label: 'No. of Seminar Hours per Quarter',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: JSON.parse('{!! json_encode($seminar_hours_per_quarter) !!}'),
            }]
        };

        const seminarQuarterConfig = {
            type: 'line',
            data: seminarQuarterData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const seminarQuarterChart = new Chart(
            document.getElementById('seminar-quarter-chart'),
            seminarQuarterConfig
        );
    </script>
    {{-- training year --}}
    <script>
        const trainingYearData = {
            labels: JSON.parse('{!! json_encode($years) !!}'),
            datasets: [{
                label: 'No. of Training Hours per Year',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: JSON.parse('{!! json_encode($training_hours_per_year) !!}'),
            }]
        };

        const trainingYearConfig = {
            type: 'line',
            data: trainingYearData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const trainingYearChart = new Chart(
            document.getElementById('training-year-chart'),
            trainingYearConfig
        );
    </script>
    {{-- training quarter --}}
    <script>
        const trainingQuarterData = {
            labels: JSON.parse('{!! json_encode($quarters) !!}'),
            datasets: [{
                label: 'No. of Training Hours per Quarter',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: JSON.parse('{!! json_encode($training_hours_per_quarter) !!}'),
            }]
        };

        const trainingQuarterConfig = {
            type: 'line',
            data: trainingQuarterData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        const trainingQuarterChart = new Chart(
            document.getElementById('training-quarter-chart'),
            trainingQuarterConfig
        );
    </script>
    @endpush
</x-app-layout>
