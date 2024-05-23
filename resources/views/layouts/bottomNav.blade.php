 <!-- App Bottom Menu -->
    <div class="appBottomMenu">
        <a href="/dashboard" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
            <div class="col">
               <ion-icon name="home-outline"></ion-icon>
                <strong>Home</strong>
            </div>
        </a>
        <a href="/presensi/histori" class="item">
            <div class="col">
                <ion-icon name="document-text-outline" role="img" class="md hydrated"
                    aria-label="document-text-outline"></ion-icon>
                <strong>Histori</strong>
            </div>
        </a>
        <a href="/presensi/create" class="item">
            <div class="col">
                <div class="action-button large">
                    <ion-icon name="camera" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
                </div>
            </div>
        </a>
        <a href="#" class="item">
            <div class="col">
                <ion-icon name="document-text-outline" role="img" class="md hydrated"
                    aria-label="document text outline"></ion-icon>
                <strong>Docs</strong>
            </div>
        </a>
        <a href="/proseslogout" class="item">
            <div class="col">
                <ion-icon name="log-out-outline"></ion-icon>
                <strong>Logout</strong>
            </div>
        </a>
    </div>
    <!-- * App Bottom Menu -->
