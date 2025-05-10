<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <div class="logo d-flex align-items-center">
            {{-- <img src="assets/img/icon-kbi.png" alt=""> --}}
            <span class="d-none d-lg-block"><strong>Kyoraku Blowmolding Indonesia</strong></span>
        </div>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->


    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <!-- Digital Clock -->
            <div id="digital-clock" class="ms-auto me-4"></div>
            <!-- End Digital Clock -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->

<script>
    function updateClock() {
        const clock = document.getElementById('digital-clock');
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        clock.textContent = `${hours}:${minutes}:${seconds}`;
    }

    setInterval(updateClock, 1000); // Update setiap detik
    updateClock(); // Inisialisasi
</script>
