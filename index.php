<?php 
session_start();
require 'function.php';
require 'cek.php';

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>IDC LOGISTIK</title>
        <link rel="shortcut icon" href="/assets/img/icon.png">
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed" onload=getDataBarang()>

    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark" style="background-image: url('/assets/img/icon.png')">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.php"><i class="far fa-clone"></i> STOCK BARANG</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            
            <!-- Navbar-->
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion" style="background-image: url('/assets/img/icon.png')">
                    <div class="sb-sidenav-menu">
                        <div class="nav">                           
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-database" style='font-size:25px'></i></div>
                                Home
                            </a>
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-database" style='font-size:25px'></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="keluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-database" style='font-size:25px'></i></div>
                                Pengambilan Barang
                            </a>
                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt" style='font-size:25px'></i></div>
                                Logout
                            </a>
                        </div>
                    </div>
                </nav>
            </div>

    
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <div class="row">
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header" style="background-image: linear-gradient(120deg, #e0c3fc 0%, #8ec5fc 100%);">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Grafik Masuk dan Keluar
                                    </div>
                                    <div class="card-body"><canvas id="myChart" width="100%" height="40"></canvas></div>
                                    
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header" style="background-image: linear-gradient(120deg, #e0c3fc 0%, #8ec5fc 100%);">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Grafik Stock
                                    </div>
                                    <div class="card-body"><canvas id="lineChart"  width="100%" height="40" ></canvas></div>
                                </div>
                            </div>
                        </div>

                        <!-- table -->
                        <div class="card mb-4">
                            <div class="card-header" style="background-image: linear-gradient(120deg, #e0c3fc 0%, #8ec5fc 100%);">
                                <i class="fas fa-table me-1"></i>
                                DataTable Stock
                            </div>
                            <div class="card-body" style="background-image: linear-gradient(to top, #6a85b6 0%, #bac8e0 100%);">
                            <?php 
                                $ambildatastock = mysqli_query($conn, "select * from stock where stock < 1");

                                while($fetch=mysqli_fetch_array($ambildatastock)){
                                    $barang = $fetch['namabarang'];
                                

                            ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <i class="far fa-bell" style='font-size:15px;color:red'></i>
                                <strong>Perhatian!</strong> Stock <strong><?=$barang;?></strong> Telah Habis
                            </div>
                            <?php 
                                }
                            ?>
                                <table id="datatablesSimple" class="table table-dark table-striped">
                                <div class="card mb-4">
                            <div class="card-header" style="background-image: linear-gradient(to top, #09203f 0%, #537895 100%);">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" style="background-image: linear-gradient(to right, #a8caba 0%, #5d4157 100%);" data-bs-toggle="modal" data-bs-target="#add">
                                Add
                            </button>
                            <a href="exportstock.php" class="btn btn-info" style="background-image: linear-gradient(to right, #a8caba 0%, #5d4157 100%);">Export</a>
                            </div>

                            <!-- The Modal -->
                            <div class="modal fade" id="add">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Stock Barang</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <!-- Modal body -->
                                 <form method="post" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <input type="text" name="namabarang" placeholder="Nama Barang" class="form-control" required>
                                    <br>
                                    <input type="text" name="deskripsi" placeholder="Deskripsi" class="form-control" required>
                                    <br>
                                    <input type="number" name="stock" placeholder="Stock" class="form-control" required>
                                    <br>
                                    <button type="submit" class="btn btn-success" name="addnewbarang">Submit</button>
                                </div>
                                </form>
                                </div>
                                
                            </div>

                            
                                    <thead>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Deskripsi</th>
                                            <th>Stock</th>  
                                            <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $ambilsemuadatastock =  mysqli_query($conn, "select * from stock");
                                        $i = 1;
                                        while($data=mysqli_fetch_array($ambilsemuadatastock)){                                        
                                        $namabarang = $data['namabarang'];
                                        $deskripsi = $data['deskripsi'];
                                        $stock = $data['stock'];
                                        $idb = $data['idbarang'];

                                        
                                        
                                        ?>
                                    <tr>
                                    
                                            <td><?=$i++;?></td>
                                            <td><?=$namabarang;?></td>
                                            <td><?=$deskripsi;?></td>
                                            <td><?=$stock;?></td>
                                            <td>
                                            <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#edit<?=$idb;?>">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#delete<?=$idb;?>">
                                                    Delete
                                                </button>
                                            </td>
                                            
                                        </tr>
<!-- edit modal -->

                            <!-- Edit Modal -->
                            <div class="modal fade" id="edit<?=$idb;?>">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Edit Barang</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <!-- Modal body -->
                                 <form method="post">
                                <div class="modal-body" >
                                    <input type="text" name="namabarang" value="<?=$namabarang;?>" class="form-control" required>
                                    <br>
                                    <input type="text" name="deskripsi" value="<?=$deskripsi;?>" class="form-control" required>
                                    <br>
                                    <input type="hidden" name="idb" value="<?=$idb;?>">
                                    <button type="submit" class="btn btn-primary" name="updatebarang">Ubah</button>
                                </div>
                                </form>
                                </div>
                            </div>
                        </div>
<!-- hapus modal -->
                        <!-- Hapus Modal -->
                        <div class="modal fade" id="delete<?=$idb;?>">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Hapus Barang</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <!-- Modal body -->
                                 <form method="post">
                                <div class="modal-body" >
                                    Apakah ANDA yakin ingin menghapus <strong><?=$namabarang;?></strong>
                                    <input type="hidden" name="idb" value="<?=$idb;?>">
                                    <br>
                                    <br>
                                    <button type="submit" class="btn btn-primary" name="hapusbarang">Delete</button>
                                </div>
                                </form>
                                </div>
                            </div>
                        </div>
                                        <?php 
                                        };
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/collect.js/4.36.1/collect.min.js" integrity="sha512-aub0tRfsNTyfYpvUs0e9G/QRsIDgKmm4x59WRkHeWUc3CXbdiMwiMQ5tTSElshZu2LCq8piM/cbIsNwuuIR4gA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- Chart -->

<script>
const data = {
  labels: [
    'Masuk',
    'Keluar',
    
  ],
  datasets: [{
    label: 'Jumlah Barang Masuk & Keluar',
    data: [
        <?php
        $getmasuk = mysqli_query($conn,"select * from masuk");
        $mskb = $getmasuk->num_rows;
        echo $mskb;
        ?>,
        <?php
        $getkeluar = mysqli_query($conn,"select * from keluar");
        $mskk = $getkeluar->num_rows;
        echo $mskk;
        ?>
        
    ],
    backgroundColor: [
      'rgb(184, 54, 235)',
      'rgb(248, 12, 91)',
    ],
    hoverOffset: 4
  }]
};
  const config = {
    type: 'bar',
    data: data,
  };
  const myChart = new Chart(
      document.getElementById('myChart'),
      config
    )
</script>

<script>
function getDataBarang() {
$.ajax({
    type: 'GET',
    url: 'chart_backend.php',
    data: {
    functionName: 'getDataStock'
    },
    success: function(response) {
    let barang = JSON.parse(response)
    //  console.log(barang)

    let jenisbarang = collect(barang).map(function(item) {
        return item.namabarang
    }).all()

    let jumlah = collect(barang).map(function(item) {
        return item.stock
    }).all()


    const ctx = document.getElementById("lineChart");

    new Chart(ctx, {
        type: "line",
        data: {
        labels: jenisbarang,
        datasets: [
            {
            label: "Stock Barang",
            data: jumlah,
            borderColor: [
                'rgb(177, 99, 180)',
            ],
            borderWidth: 3,
            hoverBorderWidth: 0,
            },
        ],
        },
        options: {
        scales: {
            y: {
            beginAtZero: true,
            },
        },
        },
    });
        }
        })
    }

</script>
    </body>
</html>
        