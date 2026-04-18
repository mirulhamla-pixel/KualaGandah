<?php
include "../conn.php";
include "header_admin.php";

$dailyReport = $conn->query(
    "SELECT booking_date, COUNT(*) AS booking_count, SUM(total_price) AS revenue
     FROM bookings
     GROUP BY booking_date
     ORDER BY booking_date DESC"
);

$monthlyReport = $conn->query(
    "SELECT DATE_FORMAT(booking_date, '%Y-%m') AS report_month, COUNT(*) AS booking_count, SUM(total_price) AS revenue
     FROM bookings
     GROUP BY report_month
     ORDER BY report_month DESC"
);

$categoryReport = $conn->query(
    "SELECT ticket_category, COUNT(*) AS booking_count, SUM(total_price) AS revenue
     FROM bookings
     GROUP BY ticket_category
     ORDER BY booking_count DESC"
);

$topCategoryRow = $categoryReport->fetch_assoc();
if ($topCategoryRow) {
    $categoryReport->data_seek(0);
}
?>

<h2>Sales Report</h2>

<h4>Daily Sales</h4>
<table class="table table-bordered">
    <tr>
        <th>Date</th>
        <th>Bookings</th>
        <th>Total Revenue</th>
    </tr>
    <?php while ($row = $dailyReport->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['booking_date']) ?></td>
            <td><?= htmlspecialchars($row['booking_count']) ?></td>
            <td>RM <?= htmlspecialchars($row['revenue']) ?></td>
        </tr>
    <?php } ?>
</table>

<h4>Monthly Sales</h4>
<table class="table table-bordered">
    <tr>
        <th>Month</th>
        <th>Bookings</th>
        <th>Total Revenue</th>
    </tr>
    <?php while ($row = $monthlyReport->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['report_month']) ?></td>
            <td><?= htmlspecialchars($row['booking_count']) ?></td>
            <td>RM <?= htmlspecialchars($row['revenue']) ?></td>
        </tr>
    <?php } ?>
</table>

<h4>Ticket Category Analysis</h4>
<?php if ($topCategoryRow) { ?>
    <div class="alert alert-success">
        Most popular category: <strong><?= htmlspecialchars($topCategoryRow['ticket_category']) ?></strong>
        with <strong><?= htmlspecialchars($topCategoryRow['booking_count']) ?></strong> bookings.
    </div>
<?php } ?>

<table class="table table-bordered">
    <tr>
        <th>Ticket Category</th>
        <th>Bookings</th>
        <th>Total Revenue</th>
    </tr>
    <?php while ($row = $categoryReport->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['ticket_category']) ?></td>
            <td><?= htmlspecialchars($row['booking_count']) ?></td>
            <td>RM <?= htmlspecialchars($row['revenue']) ?></td>
        </tr>
    <?php } ?>
</table>

<?php include "footer_admin.php"; ?>