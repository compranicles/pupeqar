<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Maintenances') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">
                            <h3>Manage Categories</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.accrelevel') }}" class="btn btn-lg btn-dark">Program Accreditation Level...</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.support') }}" class="btn btn-lg btn-dark">Type of Support</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.studystatus') }}" class="btn btn-lg btn-dark">Advanced/ Professional Study Status</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.facultyaward') }}" class="btn btn-lg btn-dark">Faculty Awards Classification</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.level') }}" class="btn btn-lg btn-dark">Levels</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.facultyofficer') }}" class="btn btn-lg btn-dark">Faculty Officership Classification</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.developclass') }}" class="btn btn-lg btn-dark">Development Program Classification</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.developnature') }}" class="btn btn-lg btn-dark">Development Program Nature</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.fundingtype') }}" class="btn btn-lg btn-dark">Source of Fund/ Type of Funding</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.trainclass') }}" class="btn btn-lg btn-dark">Training Classification</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.researchclass') }}" class="btn btn-lg btn-dark">Research Classification</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.researchcategory') }}" class="btn btn-lg btn-dark">Research Category</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.researchagenda') }}" class="btn btn-lg btn-dark">University Research Agenda</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.researchinvolve') }}" class="btn btn-lg btn-dark">Nature of Research Involvement</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.researchtype') }}" class="btn btn-lg btn-dark">Type of Research</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ route('admin.maintenances.researchlevel') }}" class="btn btn-lg btn-dark">Research Level</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Indexing Platform of the Journal...</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Invention Classification</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Invention Status</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Expert Service as Consultant...</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Expert Service in Conferences...</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Expert Service in Academic Journals...</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Nature of Expert Services</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Nature of Extension Program Involvement</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Classification of the Extension Activity</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Extension Program Status</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Type of Partner Institution</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Nature of Collaboration</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Collaboration Deliverable</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Target Beneficiaries</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Nature of Engagement</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3 text-center">
                <div class="card">
                    <div class="card-body">
                        <a href="" class="btn btn-lg btn-dark">Type of Faculty Involvement</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</x-app-layout>