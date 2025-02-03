<?php
// Ensure $conn is available from db.php
$query = "SELECT news.id, news.title, categories.name AS category, news.date 
          FROM news 
          LEFT JOIN categories ON news.category_id = categories.id
          ORDER BY news.date DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "خطا در دریافت اخبار: " . mysqli_error($conn);
    exit;
}
?>

<h2>لیست اخبار</h2>
<table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th>عنوان</th>
            <th>دسته‌بندی</th>
            <th>تاریخ</th>
            <th>عملیات</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($news = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?php echo htmlspecialchars($news['title']); ?></td>
                <td><?php echo htmlspecialchars($news['category']); ?></td>
                <td><?php echo htmlspecialchars($news['date']); ?></td>
                <td>
                    <!-- The edit button links to manager.php with the action edit-news and passes the news ID -->
                    <a href="manager.php?action=edit-news&id=<?php echo $news['id']; ?>">ویرایش</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
