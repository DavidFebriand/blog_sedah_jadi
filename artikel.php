<?php
include 'koneksi.php';
session_start();

// Get the article ID from the query parameter
$article_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Prepare the SQL statement for the article
$query = "SELECT a.*, u.nama_user, k.nama_kategori 
          FROM artikel a
          JOIN users u ON u.id = a.user_id
          JOIN kategori k ON k.id = a.kategori_id
          WHERE a.id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Article not found.");
}

$article = $result->fetch_assoc();
$stmt->close();

// Check if user is logged in
if (empty($_SESSION['nama'])) {
    header("location:login.php");
    exit;
}

// Handle comment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    $comment = trim($_POST['comment']);
    $user_name = $_SESSION['nama']; // Use user name instead of ID

    if (!empty($comment)) {
        $query = "INSERT INTO komentar (artikel_id, nama_user, komentar) VALUES (?, ?, ?)";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("iss", $article_id, $user_name, $comment);
        
        if ($stmt->execute()) {
            // Redirect to see the new comment
            header("Location: {$_SERVER['PHP_SELF']}?id=$article_id");
            exit;
        } else {
            echo "<script>alert('Error submitting comment: " . htmlspecialchars($stmt->error) . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Comment cannot be empty.');</script>";
    }
}

// Fetch comments for the article
$query = "SELECT komentar.komentar, komentar.nama_user, komentar.time_post 
          FROM komentar 
          WHERE artikel_id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $article_id);
$stmt->execute();
$komentar_result = $stmt->get_result();
$stmt->close();

// time zone

date_default_timezone_set('GMT+7');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($article['judul']); ?></title>
    <link rel="stylesheet" href="styles/stylescontent.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
</head>
<body>
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
                            <li><a class="dropdown-item" href="#" style="color: #fff; text-decoration:none"><?php echo $_SESSION['nama']; ?></a></li>
                            <li><hr class="dropdown-divider" /></li>
                            <li><a class="dropdown-item" href="logout.php" style="color: #fff;">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
</header>

<main style="margin-top: 75px;">
    <div class="container">
        <h3><?php echo htmlspecialchars($article['judul']); ?></h3>
        <img src="file_cover/<?php echo htmlspecialchars($article['cover']); ?>" class="img-fluid" alt="Cover Image">
        <p><?php echo nl2br(htmlspecialchars($article['isi_artikel'])); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($article['nama_kategori']); ?></p>
        <p><strong>Author:</strong> <?php echo htmlspecialchars($article['nama_user']); ?></p>

        <h4>Comments</h4>
        <form method="POST" action="">
            <div class="mb-3">
                <textarea name="comment" class="form-control" rows="3" placeholder="Leave a comment..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <div class="komentar mt-4">
            <?php while ($komentar = $komentar_result->fetch_assoc()): ?>
                <div class="comment">
                    <strong><?php echo htmlspecialchars($komentar['nama_user']); ?></strong>
                    <p><?php echo nl2br(htmlspecialchars($komentar['komentar'])); ?></p>
                    <small><?php echo date('d/m/Y H:i:s', strtotime($komentar['time_post'])); ?></small>
                </div>
                <hr>
            <?php endwhile; ?>
        </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
