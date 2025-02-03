<?php
include 'db.php';

// Get all distinct categories
$categories = $pdo->query("SELECT DISTINCT category FROM news")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خبرگزاری به‌نیوز</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            <a href="index.php" class="logo">خبرگزاری به‌نیوز</a>
            <nav>
                <ul class="navbar">
                    <li class="dropdown">
                        <a href="#">دسته‌بندی اخبار</a>
                        <ul class="dropdown-menu">
                            <?php foreach($categories as $category): ?>
                            <li><a href="category.php?name=<?= urlencode($category) ?>"><?= htmlspecialchars($category) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li><a href="about.php">درباره ما</a></li>
                </ul>
            </nav>
            <a href="login.php" class="login-btn">ورود</a>
        </div>
    </header>

    <main class="container">
        <?php foreach($categories as $category): 
            $stmt = $pdo->prepare("SELECT * FROM news WHERE category = ? ORDER BY date_created_at DESC LIMIT 4");
            $stmt->execute([$category]);
            $newsItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <section class="category">
            <h2><a href="category.php?name=<?= urlencode($category) ?>" class="category-link"><?= htmlspecialchars($category) ?></a></h2>
            <div class="news-list">
                <?php foreach($newsItems as $news): ?>
                <div class="news-item">
                    <?php if($news['image']): ?>
                    <img src="<?= htmlspecialchars($news['image']) ?>" alt="News Image">
                    <?php endif; ?>
                    <h3><?= htmlspecialchars($news['title']) ?></h3>
                    <p><?= mb_substr(htmlspecialchars($news['content']), 0, 100, 'UTF-8') ?>...</p>
                    <a href="newsDetail.php?id=<?= $news['id'] ?>">خواندن ادامه...</a>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endforeach; ?>
    </main>

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

document.getElementById('signupbutton').addEventListener('click', function() {
   
 window.location.href = '/signup.html';
 
}); 

document.getElementById('More').addEventListener('click', function() {
   
 window.location.href = '/newsDetail.html';
});

document.getElementById('categorypolitics').addEventListener('click', function() {
    
 window.location.href = '/category.html';
 
}); 
document.getElementById('categorysport').addEventListener('click', function() {
    
 window.location.href = '/category.html';
 
}); 
function startAutoScroll() {
    const newsLists = document.querySelectorAll('.news-list');
    
    newsLists.forEach(list => {
        let autoScroll;
        const startScrolling = () => {
            let scrollAmount = 0;
            const scrollStep = list.querySelector('.news-item').offsetWidth + 20; 
            
            autoScroll = setInterval(() => {
                const maxScroll = list.scrollWidth - list.clientWidth;
                scrollAmount += scrollStep;
                
                if (scrollAmount >= maxScroll) {
                    scrollAmount = 0;
                    list.scrollLeft = 0;
                }
                
                list.scrollTo({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            }, 3000);
        };

        
        startScrolling();

        
        list.addEventListener('mouseenter', () => clearInterval(autoScroll));
        list.addEventListener('mouseleave', startScrolling);
    });
}


window.addEventListener('load', () => {
    setTimeout(startAutoScroll, 1000); 
});
</script>
</body>
</html>