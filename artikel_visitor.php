<?php
include 'koneksi.php';

// Get the article ID from the query parameter
$article_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Prepare the SQL statement
$query = "SELECT artikel.judul, artikel.isi_artikel, artikel.cover, kategori.nama_kategori, users.nama_user 
          FROM artikel 
          JOIN kategori ON kategori.id = artikel.kategori_id 
          JOIN users ON users.id = artikel.user_id 
          WHERE artikel.id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $stmt->error);
}

$article = $result->fetch_assoc();
$stmt->close();
?>

<!-- Import font -->
<style>
  @import url("https://fonts.googleapis.com/css2?family=Raleway:wght@100..900&display=swap");
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metas -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Title -->
    <title><?php echo htmlspecialchars($article['judul'] ?? 'Article Not Found'); ?></title>
    <!-- Packages -->
    <link rel="stylesheet" href="styles/stylescontent.css" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
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
                        <a class="nav-link active" aria-current="page" href="main_menu_visitor.php" style="color: #fff;">Homepage</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="webpage_list_visitor.php" style="color: #fff;">Go to Article</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #fff;">Want to use our service?</a>
                        <ul class="dropdown-menu" style="background-color: #483c9e;">
                            <li><a class="dropdown-item" href="sign_in.php" style="color: #fff;">Login</a></li>
                            <li><a class="dropdown-item" href="register.php" style="color: #fff;">Register</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
</header>

<!-- Main content -->
<main style="margin-top: 75px; text-align: center;">
    <div class="container">
        <?php
        if ($article) {
            echo '<h3>' . htmlspecialchars($article['judul']) . '</h3>';
            echo '<img src="file_cover/' . htmlspecialchars($article['cover']) . '" class="img-fluid" alt="Cover Image" style="width: 500px;">';
            echo '<p>' . nl2br(htmlspecialchars($article['isi_artikel'])) . '</p>';
            echo '<p><strong>Category:</strong> ' . htmlspecialchars($article['nama_kategori']) . '</p>';
            echo '<p><strong>Author:</strong> ' . htmlspecialchars($article['nama_user']) . '</p>';
        } else {
            echo '<p>Article not found.</p>';
        }
        ?>
    </div>
</main>

<!-- Footer -->
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

<!-- Include Font Awesome for icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<!-- Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
