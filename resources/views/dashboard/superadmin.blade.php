<div class="db-col mb-2">
    <div class="db-card">
        <h5 class="card-header font-weight-bold text-center">Super Admin</h5>
        <div class="card-body d-flex justify-content-center">
            <div class="data-card shadow-sm" style="background-color: #373E45;">
                <div class="db-text">
                    <p class="db-stat">{{ $countRegisteredUsers }}</p>
                    <a class="db-text" style="word-wrap: break-word;" href="{{ route('admin.users.index') }}">USERS</a>

                </div>
            </div>
        </div>
    </div>
</div>
