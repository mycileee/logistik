<?php 
session_start();
$conn = mysqli_connect("localhost", "root", "topabis", "stockbarang");


// Menambah barang baru
if(isset($_POST['addnewbarang'])){
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    // Validasi data
    $cek = mysqli_query($conn, "select * from stock where namabarang='$namabarang'");
    $hitung = mysqli_num_rows($cek);

    if($hitung<1){
    // Jika Belum Ada

    $addtotable = mysqli_query($conn, "insert into stock (namabarang, deskripsi, stock) values('$namabarang','$deskripsi','$stock')");
    if($addtotable){
            header('location:index.php');
        } else {
            echo 'gagal';
            header('location:index.php');
        }
            } else {
        // Jika sudah ada
        echo '
        <script>
            alert("Nama barang sudah terdaftar";
            windows.location.href="index.php";
        </script>
        ';
    }
};



/// Menambah barang masuk
if (isset($_POST['addbarangmasuk'])) {
    $barangnya = intval($_POST['barangnya']);
    $divisi = htmlspecialchars(trim($_POST['divisi']));
    $penerima = htmlspecialchars(trim($_POST['penerima']));
    $keterangan = htmlspecialchars(trim($_POST['keterangan']));
    $qty = intval($_POST['qty']);

    // Proses upload gambar
    $imgmasuk = $_FILES['imgmasuk']['name'];
    $target_dir = "imgmasuk/";
    $target_file = $target_dir . basename($imgmasuk);
    $uploadOk = true;

    // Validasi file gambar
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'png', 'jpeg', 'pdf',];

    if (!in_array($imageFileType, $allowedTypes)) {
        echo "Jenis file tidak didukung. Hanya JPG, PNG, JPEG, dan PDF yang diperbolehkan.";
        $uploadOk = false;
    }

    if ($_FILES['imgmasuk']['size'] > 5000000) { // Maksimal 5MB
        echo "Ukuran file terlalu besar.";
        $uploadOk = false;
    }

    if ($uploadOk && move_uploaded_file($_FILES['imgmasuk']['tmp_name'], $target_file)) {
        // Validasi koneksi database
        if (!$conn) {
            die("Koneksi database gagal.");
        }

        // Mulai transaksi
        mysqli_begin_transaction($conn);

        try {
            // Ambil stok saat ini
            $cekstocksekarang = mysqli_prepare($conn, "SELECT stock FROM stock WHERE idbarang = ?");
            mysqli_stmt_bind_param($cekstocksekarang, "i", $barangnya);
            mysqli_stmt_execute($cekstocksekarang);
            $result = mysqli_stmt_get_result($cekstocksekarang);
            $ambildatanya = mysqli_fetch_assoc($result);

            if (!$ambildatanya) {
                throw new Exception("Barang tidak ditemukan.");
            }

            $stocksekarang = $ambildatanya['stock'];
            $tambahkanstocksekarangdenganquantity = $stocksekarang + $qty;

            // Tambahkan data ke tabel masuk
            $addtomasuk = mysqli_prepare($conn, "INSERT INTO masuk (idbarang, penerima, qty, divisi, keterangan, imgmasuk) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($addtomasuk, "isisss", $barangnya, $penerima, $qty, $divisi, $keterangan, $imgmasuk);
            mysqli_stmt_execute($addtomasuk);

            // Perbarui stok di tabel stock
            $updatestockmasuk = mysqli_prepare($conn, "UPDATE stock SET stock = ? WHERE idbarang = ?");
            mysqli_stmt_bind_param($updatestockmasuk, "ii", $tambahkanstocksekarangdenganquantity, $barangnya);
            mysqli_stmt_execute($updatestockmasuk);

            // Commit transaksi
            mysqli_commit($conn);

            // Redirect ke halaman masuk.php
            header('Location: masuk.php');
            exit();
        } catch (Exception $e) {
            // Rollback jika terjadi error
            mysqli_rollback($conn);
            echo "Gagal menambahkan barang masuk: " . $e->getMessage();
        }
    } else {
        echo "Gagal mengunggah gambar.";
    }
}





// Menambah barang keluar
if (isset($_POST['addbarangkeluar'])) {
    $barangnya = intval($_POST['barangnya']);
    $penerima = htmlspecialchars(trim($_POST['penerima']));
    $divisi = htmlspecialchars(trim($_POST['divisi']));
    $qty = intval($_POST['qty']);
    $keterangan = htmlspecialchars(trim($_POST['keterangan']));

    // Validasi stok barang
    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);
    $stocksekarang = $ambildatanya['stock'];

    if ($stocksekarang >= $qty) {
        // Jika stok mencukupi
        $tambahkanstocksekarangdenganquantity = $stocksekarang - $qty;

        
        // Tambahkan data ke tabel keluar
        $addtokeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, qty, divisi, keterangan) VALUES ('$barangnya', '$penerima', '$qty', '$divisi', '$keterangan')");

        // Perbarui stok barang
        $updatestock = mysqli_query($conn, "UPDATE stock SET stock='$tambahkanstocksekarangdenganquantity' WHERE idbarang='$barangnya'");

        if ($addtokeluar && $updatestock) {
            header('location:keluar.php');
        } else {
            echo 'Gagal menambahkan barang keluar';
            header('location:keluar.php');
        }
    } else {
        // Jika stok tidak mencukupi
        echo '
        <script>
            alert("Stok saat ini tidak cukup");
            window.location.href="keluar.php";
        </script>
        ';
    }
}



// Update Info Barang

if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn, "update stock set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang ='$idb' ");
    if($update){
        header('location:index.php');
    } else {
        echo 'gagal';
        header('location:index.php');
    }
}

// Hapus Barang

if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];
    
    $hapus = mysqli_query($conn, "delete from stock where idbarang='$idb'");
    if($hapus){
        header('location:index.php');
    } else {
        echo 'gagal';
        header('location:index.php');
    }
}

// Mengubah Data Barang Masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];  
    $idm = $_POST['idm'];
    $penerima = $_POST['penerima'];
    $divisi = $_POST['divisi'];
    $qty = $_POST['qty'];
    $keterangan = $_POST['keterangan'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from masuk where idmasuk='$idm'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg + $selisih; 
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty', penerima='$penerima', keterangan='$keterangan', divisi='$divisi' where idmasuk='$idm'");
            if($kurangistocknya&&$updatenya){
                header('location:masuk.php');
            } else {
                echo 'gagal';
                header('location:masuk.php');
            }

    }   else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg - $selisih; 
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update masuk set qty='$qty', penerima='$penerima', keterangan='$keterangan', divisi='$divisi' where idmasuk='$idm'");
            if($kurangistocknya&&$updatenya){
                header('location:masuk.php');
            } else {
                echo 'gagal';
                header('location:masuk.php');
            }

    }

}





/// Menghapus Barang Masuk
if (isset($_POST['hapusbarangmasuk'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    // Ambil informasi data barang dan gambar
    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $dataStock = mysqli_fetch_array($getdatastock);
    $stock = $dataStock['stock'];

    $getdatabarangmasuk = mysqli_query($conn, "SELECT imgmasuk FROM masuk WHERE idmasuk='$idm'");
    $dataMasuk = mysqli_fetch_array($getdatabarangmasuk);
    $gambar = $dataMasuk['imgmasuk'];

    // Hitung stok baru setelah penghapusan
    $selisih = $stock - $qty;

    // Update stok barang
    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idb'");

    // Hapus data barang masuk dari database
    $hapusdata = mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk='$idm'");

    // Hapus gambar dari server
    if ($hapusdata && $gambar) {
        $path = "imgmasuk/" . $gambar; // Lokasi file gambar
        if (file_exists($path)) {
            unlink($path); // Menghapus gambar
        }
    }

    // Redirect ke halaman masuk.php
    if ($update && $hapusdata) {
        header('location:masuk.php');
    } else {
        echo "Gagal menghapus data barang masuk.";
    }
}


// Mengubah Data Barang Keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];  
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $divisi = $_POST['divisi'];
    $qty = $_POST['qty'];
    $keterangan = $_POST['keterangan'];

    $lihatstock = mysqli_query($conn, "select * from stock where idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stockskrg = $stocknya['stock'];

    $qtyskrg = mysqli_query($conn, "select * from keluar where idkeluar='$idk'");
    $qtynya = mysqli_fetch_array($qtyskrg);
    $qtyskrg = $qtynya['qty'];

    if($qty>$qtyskrg){
        $selisih = $qty-$qtyskrg;
        $kurangin = $stockskrg - $selisih; 
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima' where idkeluar='$idk'");
            if($kurangistocknya&&$updatenya){
                header('location:keluar.php');
            } else {
                echo 'gagal';
                header('location:keluar.php');
            }

    }   else {
        $selisih = $qtyskrg-$qty;
        $kurangin = $stockskrg + $selisih; 
        $kurangistocknya = mysqli_query($conn, "update stock set stock='$kurangin' where idbarang='$idb'");
        $updatenya = mysqli_query($conn, "update keluar set qty='$qty', penerima='$penerima', divisi='$divisi', keterangan='$keterangan' where idkeluar='$idk'");
            if($kurangistocknya&&$updatenya){
                header('location:keluar.php');
            } else {
                echo 'gagal';
                header('location:keluar.php');
            }

    }

}


// Menghapus Barang Keluar
if (isset($_POST['hapusbarangkeluar'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];


    // Ambil data stok barang
    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock + $qty;

    // Perbarui stok dan hapus data keluar
    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idk'");

    if ($update && $hapusdata) {
        header('location:keluar.php');
    } else {
        echo "Gagal menghapus data barang keluar.";
        header('location:keluar.php');
    }
}

// Download Gambar
if (isset($_GET['imgmasuk'])) {
    $file = 'imgmasuk/' . basename($_GET['imgmasuk']);
    if (file_exists($file)) {
        header('Content-Description: File= Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    } else {
        echo "File tidak ditemukan!";
    }
}


?>

