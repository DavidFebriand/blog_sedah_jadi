<?php
include 'koneksi.php';
?>
<?php
session_start();
if(empty($_SESSION['nama'])){
    header("location:login.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Main Menu</title>
    <link rel="stylesheet" href="styles/stylescontent.css" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <style>
        .table-hover tbody tr:hover {
            background-color: #f1f1f1; 
        }
        .table thead th {
            background-color: #6f42c1; 
            color: #fff; 
        }
        .action-buttons button {
            margin-right: 5px;
        }
        .action-buttons button i {
            margin-right: 5px;
        }

        /* Custom button styles */
        .btn-custom {
            background: linear-gradient(135deg, #6f42c1, #5a318e);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .btn-danger {
            background: linear-gradient(135deg, #dc3545, #b02a37);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-danger:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="body">
<header>
<nav class="navbar fixed-top" style="background-color: #483c9e;">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="logo barticle2.png" alt="logo barticle" style="height: 30px;" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-end" style="background-color: #34269f; color: #fff;" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Navigation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="main_menu_admin.php" style="color: #fff;">Homepage</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="webpage_list_admin.php" style="color: #fff;">Go to Article</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #fff;">Users</a>
                        <ul class="dropdown-menu" style="background-color: #483c9e;">
                            <li><a class="dropdown-item" href="#" style="color: #fff; text-decoration:none"><?php echo $_SESSION['nama'];?></a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item" href="logout.php" style="color: #fff;">Logout</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_panel.php" style="color: #fff; font-weight:bold">Control Panel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kategori_admin.php" style="color: #fff;">kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="user_panel.php" style="color: #fff;">User</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
</header>

<main>
    <br><br><br>
    <h1 style="text-align: center;">ADMIN CONTROL PANEL</h1>
    <div class="container">
        <div class="mb-3 text-center">
            <a href="tambah_artikel_admin.php" class="w-100">
                <button type="button" class="btn btn-custom w-100"><i class="bx bx-plus"></i> Tambah Artikel</button>
            </a>
        </div>
        
        <table class="table table-bordered border-dark table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Judul</th>
                    <th scope="col">Tanggal Publish</th>
                    <th scope="col">Isi Artikel</th>
                    <th scope="col">Cover</th>
                    <th scope="col">Status Aktif</th>
                    <th scope="col">Nama Kategori</th>
                    <th scope="col">Nama User</th>
                    <th scope="col">Email</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $artikel = mysqli_query($koneksi, "SELECT artikel.id, artikel.judul, artikel.tanggal_publish, artikel.isi_artikel, artikel.cover, artikel.status_aktif, kategori.nama_kategori, users.nama_user, users.email FROM artikel JOIN kategori ON kategori.id = artikel.kategori_id JOIN users ON users.id = artikel.user_id");
                while ($row = mysqli_fetch_array($artikel)) {
                ?>
                <tr>
                    <td><?php echo $row['id'] ?></td>
                    <td><?php echo $row['judul'] ?></td>
                    <td><?php echo $row['tanggal_publish'] ?></td>
                    <td><?php echo htmlspecialchars(substr($row['isi_artikel'], 0, 110)) . (strlen($row['isi_artikel']) > 110 ? '...' : ''); ?></td>
                    <td><img src="file_cover/<?php echo $row['cover'] ?>" width="100" alt="Cover Image"></td>
                    <td><?php echo $row['status_aktif'] ?></td>
                    <td><?php echo $row['nama_kategori'] ?></td>
                    <td><?php echo $row['nama_user'] ?></td>
                    <td><?php echo $row['email'] ?></td>
                    <td class="action-buttons">
                        <a href="edit_artikel_admin.php?id=<?php echo $row['id'] ?>">
                            <button type="button" class="btn btn-success"><i class="bx bxs-edit"></i> Edit</button>
                        </a>
                        <a href="hapus_artikel_admin.php?id=<?php echo $row['id'] ?>">
                            <button type="button" class="btn btn-danger"><i class="bx bxs-trash"></i> Hapus</button>
                        </a>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</main>

<div class="footer-copyright text-center py-4" style="background-color: #483c9e; color: #fff;">
    <footer class="footer">
        <nav class="navbar navbar-expand-lg" style="background-color: #483c9e; padding: 1rem 0;">
            <div class="container-fluid">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="#" style="color: #fff; text-decoration: none; transition: color 0.3s;">Terms Of Service</a></li>
                        <li class="breadcrumb-item"><a href="#" style="color: #fff; text-decoration: none; transition: color 0.3s;">Privacy</a></li>
                        <li class="breadcrumb-item"><a href="#" style="color: #fff; text-decoration: none; transition: color 0.3s;">Content Policy</a></li>
                    </ol>
                </nav>
                <div class="social-icons">
                    <a href="#" class="text-white me-3" style="text-decoration: none;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-3" style="text-decoration: none;"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white" style="text-decoration: none;"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </nav>
        <p style="margin: 0;">© 2024 Barticle. All rights reserved.</p>
    </footer>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
