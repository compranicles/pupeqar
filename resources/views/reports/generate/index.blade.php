<div class="modal fade" id="reportGenerate" tabindex="-1" aria-labelledby="reportGenerateLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportGenerateLabel">Generate Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('report.generate.index', $data->id) }}" method="post">
                    @csrf
                    <input type="hidden" name="source_generate" value="{{ $source_type }}">
                    <div class="form-group">
                        <label for="type_generate">Format</label>
                        <select name="type_generate" id="type_generate" class="form-control" required>
                            <option value="" selected disabled>Choose...</option>
                            <option value="academic">Academic</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quarter_generate">Quarter</label>
                        <select name="quarter_generate" id="quarter_generate" class="form-control">
                            <option value="1" {{$quarter== 1 ? 'selected' : ''}} class="quarter">1</option>
                            <option value="2" {{$quarter== 2 ? 'selected' : ''}} class="quarter">2</option>
                            <option value="3" {{$quarter== 3 ? 'selected' : ''}} class="quarter">3</option>
                            <option value="4" {{$quarter== 4 ? 'selected' : ''}} class="quarter">4</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quarter_generate">Year</label>
                        <select name="year_generate" id="year_generate" class="form-control" >
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Generate</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var max = new Date().getFullYear();
        var min = 0;
        var diff = max-2022;
        min = max-diff;
        select = document.getElementById('year_generate');

        var year = {!! json_encode($year) !!};
        for (var i = max; i >= min; i--) {
            select.append(new Option(i, i));
            if (year == i) {
                document.getElementById("year_generate").value = i;
            }
        }
    </script>
@endpush