<div class="col-md-3 mb-4">
    <div class="rounded shadow-sm px-3 py-3" style="background-color: #820001;">
        <!-- <div>
            <i class="bi bi-file-bar-graph home-icons" style="color: #820001;"></i>
        </div> -->
        <div class="d-flex align-items-center">
            <p class="db-text db-stat">{{ $countRegisteredUsers }}</p>
            <a class="db-text" style="word-wrap: break-word;" href="{{ route('admin.users.index') }}">Registered Users</a>
        </div>
    </div>
</div>