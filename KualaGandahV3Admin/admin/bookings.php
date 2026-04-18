<?php
include "../conn.php";
include "header_admin.php";

$limit = 8;
$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$status = trim($_GET['status'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$start = ($page - 1) * $limit;

function refValues($arr) {
    $refs = [];
    foreach ($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}

function buildBookingQuery($base, $search, $category, $status) {
    $where = [];
    if ($search !== '') {
        $where[] = "(users.fullname LIKE ? OR bookings.booking_date LIKE ? OR bookings.ticket_category LIKE ? OR bookings.status LIKE ? )";
    }
    if ($category !== '') {
        $where[] = "bookings.ticket_category = ?";
    }
    if ($status !== '') {
        $where[] = "bookings.status = ?";
    }
    return $base . (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');
}

function bindBookingParams($stmt, $search, $category, $status, $includeLimit = false) {
    $types = '';
    $values = [];

    if ($search !== '') {
        $like = "%{$search}%";
        $types .= 'ssss';
        $values[] = $like;
        $values[] = $like;
        $values[] = $like;
        $values[] = $like;
    }
    if ($category !== '') {
        $types .= 's';
        $values[] = $category;
    }
    if ($status !== '') {
        $types .= 's';
        $values[] = $status;
    }
    if ($includeLimit) {
        $types .= 'ii';
        $values[] = $GLOBALS['start'];
        $values[] = $GLOBALS['limit'];
    }
    if ($types !== '') {
        array_unshift($values, $types);
        call_user_func_array([$stmt, 'bind_param'], refValues($values));
    }
}

$countSql = buildBookingQuery(
    "SELECT COUNT(*) AS total FROM bookings JOIN users ON bookings.user_id = users.id",
    $search,
    $category,
    $status
);
$countStmt = $conn->prepare($countSql);
bindBookingParams($countStmt, $search, $category, $status, false);
$countStmt->execute();
$countStmt->bind_result($totalData);
$countStmt->fetch();
$countStmt->close();

$totalPage = max(1, ceil($totalData / $limit));

$resultsSql = buildBookingQuery(
    "SELECT bookings.*, users.fullname FROM bookings JOIN users ON bookings.user_id = users.id",
    $search,
    $category,
    $status
) . " ORDER BY bookings.created_at DESC LIMIT ?, ?";

$stmt = $conn->prepare($resultsSql);
bindBookingParams($stmt, $search, $category, $status, true);
$stmt->execute();
$result = $stmt->get_result();

if (isset($_GET['ajax'])) {
    $table = '<table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Receipt</th>
            <th>Action</th>
        </tr>';

    while ($row = $result->fetch_assoc()) {
        $receiptLink = $row['receipt_file'] ? '<a href="../uploads/'.htmlspecialchars($row['receipt_file']).'" target="_blank" class="btn btn-info btn-sm">View</a>' : '<span class="badge bg-secondary">No receipt</span>';
        $action = '';
        if ($row['status'] === 'Paid') {
            $action = '<a class="btn btn-success btn-sm" href="confirm_payment.php?id='.urlencode($row['booking_id']).'">Confirm</a>';
        }
        $table .= '<tr>
            <td>'.htmlspecialchars($row['booking_id']).'</td>
            <td>'.htmlspecialchars($row['fullname']).'</td>
            <td>'.htmlspecialchars($row['booking_date']).'</td>
            <td>'.htmlspecialchars($row['ticket_category']).'</td>
            <td>'.htmlspecialchars($row['adult_qty'] + $row['child_qty']).'</td>
            <td>RM '.htmlspecialchars($row['total_price']).'</td>
            <td>'.htmlspecialchars($row['status']).'</td>
            <td>'.$receiptLink.'</td>
            <td>'.$action.'</td>
        </tr>';
    }

    $table .= '</table>';
    $pagination = '<nav><ul class="pagination">';
    for ($i = 1; $i <= $totalPage; $i++) {
        $active = ($i === $page) ? 'active' : '';
        $pagination .= '<li class="page-item '.$active.'"><a class="page-link" href="#" data-page="'.$i.'">'.$i.'</a></li>';
    }
    $pagination .= '</ul></nav>';

    echo json_encode(['table' => $table, 'pagination' => $pagination]);
    exit;
}
?>

<h2>Advanced Booking Management</h2>

<div class="row mb-3">
    <div class="col-md-4">
        <input type="text" id="search" class="form-control" placeholder="Search name, date, category, status" value="<?= htmlspecialchars($search) ?>">
    </div>
    <div class="col-md-3">
        <select id="category" class="form-select">
            <option value="">All Categories</option>
            <option value="Standard" <?= $category === 'Standard' ? 'selected' : '' ?>>Standard</option>
            <option value="Family" <?= $category === 'Family' ? 'selected' : '' ?>>Family</option>
            <option value="Child" <?= $category === 'Child' ? 'selected' : '' ?>>Child</option>
            <option value="Combo" <?= $category === 'Combo' ? 'selected' : '' ?>>Combo</option>
        </select>
    </div>
    <div class="col-md-3">
        <select id="status" class="form-select">
            <option value="">All Status</option>
            <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Paid" <?= $status === 'Paid' ? 'selected' : '' ?>>Paid</option>
            <option value="Confirmed" <?= $status === 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
        </select>
    </div>
</div>

<div id="bookingTable"></div>
<div id="pagination"></div>

<?php include "footer_admin.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function buildQueryParams(page) {
    const params = new URLSearchParams();
    params.set('ajax', 1);
    params.set('page', page);
    params.set('search', $('#search').val());
    params.set('category', $('#category').val());
    params.set('status', $('#status').val());
    return params.toString();
}

function loadData(page) {
    const query = buildQueryParams(page);
    $.get('bookings.php?' + query, function (res) {
        const data = JSON.parse(res);
        $('#bookingTable').html(data.table);
        $('#pagination').html(data.pagination);
    });
}

$(document).ready(function () {
    loadData(1);

    $('#search, #category, #status').on('change keyup', function () {
        loadData(1);
    });

    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        loadData($(this).data('page'));
    });
});
</script>