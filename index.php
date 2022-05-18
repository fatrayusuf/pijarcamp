<?php
$host    = "localhost";
$user    = "root";
$pass    = "";
$db      = "pijarcamp";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Tidak bisa terkoneksi ke database");
}
$NamaProduk     = "";
$Keterangan     = "";
$Harga          = "";
$Jumlah         = "";
$success        = "";
$error          = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}
if($op == 'delete'){
    $NamaProduk     =$_GET['NamaProduk'];
    $sql1           ="delete from produk where NamaProduk = '$NamaProduk'";
    $q1             = mysqli_query($koneksi,$sql1);
    if($q1){
        $success   = "Berhasil hapus data";
    }else{
        $error     ="Gagal menghapus data";
    }
}

if ($op == 'edit') {
    $NamaProduk     = $_GET['NamaProduk'];
    $sql1           = "select * from produk where NamaProduk = '$NamaProduk'";
    $q1             = mysqli_query($koneksi, $sql1);
    $r1             = mysqli_fetch_array($q1);
    $NamaProduk     = $r1['NamaProduk'];
    $Keterangan     = $r1['Keterangan'];
    $Harga          = $r1['Harga'];
    $Jumlah         = $r1['Jumlah'];

    if ($NamaProduk == '') {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) { // untuk create
    $NamaProduk     = $_POST['NamaProduk'];
    $Keterangan     = $_POST['Keterangan'];
    $Harga          = $_POST['Harga'];
    $Jumlah         = $_POST['Jumlah'];

    if ($NamaProduk && $Keterangan && $Harga && $Jumlah) {
        if ($op == 'edit') {
            $sql1   = "update produk set NamaProduk = '$NamaProduk,Keterangan='$Keterangan',Harga='$Harga',Jumlah='$Jumlah'where NamaProduk ='$NamaProduk'";
            $q1     = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $success = "Data berhasil diupdate";
            } else {
                $error = "Data gagal diupdate";
            }
        } else {
            $sql1 = "insert into produk(NamaProduk,Keterangan,Harga,Jumlah) values ('$NamaProduk','$Keterangan','$Harga','$Jumlah')";
            $q1   = mysqli_query($koneksi, $sql1);
            if ($q1) {
                $success = "Berhasil memasukan data baru";
            } else {
                $error  = "Gagal memasukan data";
            }
        }
    } else {
        $error = "Silakan masukan semua data";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 10px
        }
    </style>

</head>

<body>
    <div class="mx-auto">
        <!-- untuk memasukan data -->
        <div class="card">
            <div class="card-header">
                Create / Edit
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");// 5 detik
                }
                ?>
                <?php
                if ($success) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success ?>
                    </div>
                <?php
                header("refresh:5;url=index.php");
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="NamaProduk" class="col-sm-2 col-form-label">Nama Produk</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="NamaProduk" name="NamaProduk" value="<?php echo $NamaProduk ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="Keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="Keterangan" name="Keterangan" value="<?php echo $Keterangan ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="Harga" class="col-sm-2 col-form-label">Harga</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="Harga" name="Harga" value="<?php echo $Harga ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="Jumlah" class="col-sm-2 col-form-label">Jumlah</label>
                        <div class="col-sm-10">

                            <input type="text" class="form-control" id="Jumlah" name="Jumlah" value="<?php echo $Jumlah ?>">
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    </div>

                </form>
            </div>
        </div>
        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Produk
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NamaProduk</th>
                            <th scope="col">Keterangan</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Jumlah</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    <tbody>
                        <?php
                        $sql2 = "select * from produk order by NamaProduk desc";
                        $q2   = mysqli_query($koneksi, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $NamaProduk = $r2['NamaProduk'];
                            $Keterangan = $r2['Keterangan'];
                            $Harga      = $r2['Harga'];
                            $Jumlah     = $r2['Jumlah'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $NamaProduk ?></td>
                                <td scope="row"><?php echo $Keterangan ?></td>
                                <td scope="row"><?php echo $Harga ?></td>
                                <td scope="row"><?php echo $Jumlah ?></td>
                                <td scop="row">
                                    <a href="index.php?op=edit&NamaProduk=<?php echo $NamaProduk ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&NamaProduk=<?php echo $NamaProduk?>"onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
                                    
                                </td>

                            </tr>
                        <?php
                        }
                        ?>

                    </tbody>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</body>

</html>