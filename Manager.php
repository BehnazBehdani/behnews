<?php
include 'db.php';
session_start();

// Authentication check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$action = $_GET['action'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>پنل مدیریت</title>
    <link rel="stylesheet" href="manager.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="manager.php" class="logo">پنل مدیریت</a>
            <nav>
                <ul class="navbar">
                    <li><a href="manager.php">داشبورد</a></li>
                    <li><a href="index.php">خروج</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="content-wrapper">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li class="sidebar-item has-submenu">
                        <a href="#" class="sidebar-link">اخبار</a>
                        <ul class="submenu">
                            <li><a href="manager.php?action=list-news">لیست اخبار</a></li>
                            <li><a href="manager.php?action=add-news">درج خبر جدید</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-item has-submenu">
                        <a href="#" class="sidebar-link">دسته‌بندی‌ها</a>
                        <ul class="submenu">
                            <li><a href="manager.php?action=list-categories">لیست دسته‌بندی‌ها</a></li>
                            <li><a href="manager.php?action=add-category">درج دسته‌بندی جدید</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-item has-submenu">
                        <a href="#" class="sidebar-link">کاربران</a>
                        <ul class="submenu">
                            <li><a href="manager.php?action=list-users">لیست کاربران</a></li>
                            <li><a href="manager.php?action=add-user">درج کاربر جدید</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <div class="container">
                <?php
                switch ($action) {
                    case 'list-news':
                        include 'manage_news.php';
                        break;
                    case 'add-news':
                        include 'add_news.php';
                        break;
                    case 'edit-news':
                        include 'edit_news.php';
                        break;
                    case 'list-categories':
                        include 'manage_categories.php';
                        break;
                    case 'add-category':
                        include 'add_category.php';
                        break;
                    case 'list-users':
                        include 'manage_users.php';
                        break;
                    case 'add-user':
                        include 'add_user.php';
                        break;
                    default:
                        echo '<h1>خوش آمدید به پنل مدیریت</h1>';
                        break;
                }
                ?>
            </div>
        </main>
    </div>

    <footer>
        <div class="footer-content container">
            <div class="footer-section">
                <h3>درباره ما</h3>
                <p>خبرگزاری در ارائه آخرین اخبار روز به صورت لحظه‌ای و معتبر</p>
            </div>
            <div class="footer-section">
                <h3>راه های ارتباط با ما</h3>
                <p>ایمیل: info@example.com</p>
                <p>آدرس: بیرجند، دانشگاه بیرجند</p>
            </div>
        </div>
        <pre class="footer-bottom">© ۱۴۰۳ کلیه حقوق برای خبرگزاری محفوظ است</pre>
    </footer>

    <script>
        // Sidebar toggle functionality
        document.querySelectorAll('.has-submenu').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                this.classList.toggle('active');
                const submenu = this.querySelector('.submenu');
                submenu.style.maxHeight = submenu.style.maxHeight ? null : submenu.scrollHeight + 'px';
            });
        });

        // Close other submenus when opening one
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.addEventListener('click', function(e) {
                if (!this.classList.contains('active')) {
                    document.querySelectorAll('.sidebar-item').forEach(otherItem => {
                        if (otherItem !== this && otherItem.classList.contains('active')) {
                            otherItem.classList.remove('active');
                            otherItem.querySelector('.submenu').style.maxHeight = null;
                        }
                    });
                }
            });
        });

        // Image preview functionality
        function initImagePreview() {
            const imageInputs = document.querySelectorAll('input[type="file"]');
            imageInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const preview = this.parentElement.querySelector('.image-preview');
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.innerHTML = `<img src="${e.target.result}" alt="Image Preview">`;
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            });
        }

        // Initialize all scripts when content changes
        function initDynamicContent() {
            initImagePreview();
            // Initialize other scripts here
        }

        // Initial load
        initDynamicContent();

        // Re-initialize when navigating back/forward
        window.addEventListener('popstate', initDynamicContent);
    </script>
</body>
</html>
