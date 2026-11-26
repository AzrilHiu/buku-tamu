<?php
require_once 'function.php';
include_once('templates/header.php');

// Ambil beberapa statistik ringkas untuk dashboard
$totalTamu   = query("SELECT COUNT(*) as jml FROM buku_tamu")[0]['jml'] ?? 0;
$totalHariIni = query("SELECT COUNT(*) as jml FROM buku_tamu WHERE tanggal = CURDATE()")[0]['jml'] ?? 0;
$totalUser   = query("SELECT COUNT(*) as jml FROM users")[0]['jml'] ?? 0;
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Dashboard Admin</h1>

    <!-- Content Row -->
    <div class="row">

        <!-- Total Tamu Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Tamu</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalTamu; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tamu Hari Ini Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tamu Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalHariIni; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total User Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total User</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totalUser; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
<!-- /.container-fluid -->

<?php
include_once('templates/footer.php');
?>
