<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-7 col-lg-4">
            <div class="card shadow-sm login-card">
                <div class="d-flex justify-content-center mb-3 login-logo border-bottom">
                    <div class="mb-3">
                        {{ $logo }}
                    </div>
                    
                    <div class="system-name">
                        <img src="{{ URL('storage/slogan.png') }}" width="100" alt="">
                    </div>
                </div>
                <div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</div>