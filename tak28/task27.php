<?php
// Database connection
$host = 'localhost'; 
$user = 'root'; 
$password = ''; 
$dbname = 'task27'; 

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set variables for pagination
$limit = 10; // Number of entries per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Sorting
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sort_order = isset($_GET['order']) && $_GET['order'] == 'asc' ? 'asc' : 'desc';
$new_order = $sort_order === 'asc' ? 'desc' : 'asc';

// Fetch data from the database with sorting
$sql = "SELECT * FROM orders ORDER BY $sort_column $sort_order LIMIT $start, $limit";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching data: " . $conn->error);
}

// Total records for pagination
$total_sql = "SELECT COUNT(*) FROM orders";
$total_result = $conn->query($total_sql);

if ($total_result) {
    $total_rows = $total_result->fetch_row()[0];
    $total_pages = ceil($total_rows / $limit);
} else {
    die("Error fetching total rows: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Table with Sorting and Pagination</title>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th><a href="?sort=id&order=<?= $new_order ?>">ID</a></th>
                <th><a href="?sort=order_date&order=<?= $new_order ?>">Order Date</a></th>
                <th><a href="?sort=order_id&order=<?= $new_order ?>">Order ID</a></th>
                <th><a href="?sort=name&order=<?= $new_order ?>">Name</a></th>
                <th><a href="?sort=price&order=<?= $new_order ?>">Price</a></th>
                <th><a href="?sort=quantity&order=<?= $new_order ?>">Quantity</a></th>
                <th><a href="?sort=total&order=<?= $new_order ?>">Total</a></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['order_date'] ?></td>
                <td><?= $row['order_id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['price'] ?></td>
                <td><?= $row['quantity'] ?></td>
                <td><?= $row['total'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>&sort=<?= $sort_column ?>&order=<?= $sort_order ?>">Prev</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i == $page): ?>
                <span class="active"><?= $i ?></span>
            <?php else: ?>
                <a href="?page=<?= $i ?>&sort=<?= $sort_column ?>&order=<?= $sort_order ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?= $page + 1 ?>&sort=<?= $sort_column ?>&order=<?= $sort_order ?>">Next</a>
        <?php endif; ?>
    </div>

</body>
</html>