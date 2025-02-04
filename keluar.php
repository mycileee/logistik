<?php 

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
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark" style="background-image: url('/assets/img/icon.png')">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="line.php"><i class="far fa-clone"></i> STOCK BARANG</a>
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
                            <a class="nav-link" href="login.php">
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
                        <h1 class="mt-4">Pengambilan Barang</h1>

                        
  
                        <div class="card mb-4">
                            <div class="card-header">
                            <!-- Button to Open the Modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
                                Add
                            </button>
                            <a href="exportkeluar.php" class="btn btn-info">Export</a>
                            </div>

                            <!-- The Modal -->
                            <div class="modal fade" id="myModal">
                                <div class="modal-dialog">
                                <div class="modal-content">

                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Tambah Barang Keluar</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <!-- Modal body -->
                                 <form method="post">
                                <div class="modal-body">
                                <select name="barangnya" class="form-control">
                                        <?php
                                            $ambilsemuadatanya = mysqli_query($conn,"select * from stock");
                                            while($fetcharray = mysqli_fetch_array($ambilsemuadatanya)){
                                                $namabarangnya = $fetcharray['namabarang'];
                                                $idbarangnya = $fetcharray['idbarang'];
                                            
                                        ?>
                                        
                                        <option value="<?=$idbarangnya;?>"><?=$namabarangnya;?></option>
                                    
                                        <?php       
                                            }
                                        ?>
                                    </select>
                                    <br>
                                    <select name="divisi" class="form-control" placeholder="Customer">
                                    <option value='NONE' selected>- Divisi -</option>
                                        <option>Interkoneksi</option>
                                        <option>Critical</option>
                                        <option>Listrik</option>
                                    </select>
                                    <br>
                                    <input type="number" name="qty" class="form-control" placeholder="Quantity" required>
                                    <br>
                                    <input type="text" name="penerima" placeholder="Penerima" class="form-control" required>
                                    <br>
                                    <textarea type="text" name="keterangan" placeholder="Keterangan" rows="3" class="form-control" required></textarea>  
                                    <br>
                                    <button type="submit" class="btn btn-success" name="addbarangkeluar">Submit</button>
                                </div>
                                </form>
                                </div>
                            </div>

                                                      
                            </div>
                            
                            <div class="card-body">
                                <table id="datatablesSimple" class="table table-dark table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Divisi</th>
                                            <th>Jumlah</th>
                                            <th>Penerima</th>    
                                            <th>Keterangan</th>
                                            <th>Aksi</th>                                       
                                        </tr>
                                    </thead>                                   
                                    <tbody>
                                        
                                    <?php 
                                        $ambilsemuadatastock =  mysqli_query($conn, "select * from keluar k, stock s where s.idbarang = k.idbarang");
                                        $i = 1;
                                        while($data=mysqli_fetch_array($ambilsemuadatastock)){
                                            $idk = $data['idkeluar'];
                                            $idb = $data['idbarang'];
                                            $tanggal = $data['tanggal'];
                                            $namabarang = $data['namabarang'];
                                            $divisi = $data['divisi'];
                                            $qty = $data['qty'];
                                            $penerima = $data['penerima'];
                                            $keterangan = $data['keterangan'];
                                        ?>

                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td><?= date('d-m-Y', strtotime($tanggal)); ?></td>
                                            <td><?=$namabarang;?></td>
                                            <td><?=$divisi;?></td>
                                            <td><?=$qty;?></td>
                                            <td><?=$penerima;?></td>
                                            <td><?=$keterangan;?></td>
                                            <td>
                                            <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#edit<?=$idk;?>">
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#delete<?=$idk;?>">
                                                Delete
                                            </button>
                                            </td>
                                        </tr>

                                             <!-- Edit Modal -->
                            <div class="modal fade" id="edit<?=$idk;?>">
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
                                <div class="form-group">
                                        <strong><label for="namabarang">Nama Barang</label>
                                        <input type="text" id="namabarang" class="form-control" value="<?=$namabarang;?>" readonly>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <strong><label for="divisi">Divisi</label></strong>
                                        <select name="divisi" id="divisi" class="form-control" placeholder="Customer">
                                         <option><?php echo $divisi;?></option>
                                        <option>Interkoneksi</option>
                                        <option>Critical</option>
                                        <option>Listrik</option>
                                    </select>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                       <strong><label for="penerima">Penerima</label></strong> 
                                        <input type="text" id="penerima" name="penerima" class="form-control" value="<?=$penerima;?>" required>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <strong><label for="qty">QTY</label></strong>
                                        <input type="number" id="QTY" name="qty" class="form-control" value="<?=$qty;?>" required>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                      <strong><label for="keterangan">Keterangan</label></strong>
                                     <input type="text" id="keterangan" name="keterangan" class="form-control" value="<?=$keterangan;?>" required>
                                    </div>
                                    <br>
                                    <input type="hidden" name="idb" value="<?=$idb;?>">
                                    <input type="hidden" name="idk" value="<?=$idk;?>">
                                    <button type="submit" class="btn btn-primary" name="updatebarangkeluar">Ubah</button>
                                </div>
                                </form>
                                </div>
                            </div>
                        </div>
<!-- hapus modal -->
                        <!-- Hapus Modal -->
                        <div class="modal fade" id="delete<?=$idk;?>">
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
                                    Apakah ANDA yakin ingin menghapus <?=$namabarang;?>
                                    <input type="hidden" name="idb" value="<?=$idb;?>">
                                    <input type="hidden" name="kty" value="<?=$qty;?>">
                                    <input type="hidden" name="idk" value="<?=$idk;?>">
                                    <br>
                                    <br>
                                    <button type="submit" class="btn btn-primary" name="hapusbarangkeluar">Delete</button>
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
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Build &copy; By Team Support</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
                          
</html>