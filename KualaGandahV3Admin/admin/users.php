<?php
include "../conn.php";
include "header_admin.php";

$limit = 8;

function fetchUsers($conn, $search, $start, $limit) {
    if ($search !== '') {
        $like = "%{$search}%";
        $stmt = $conn->prepare(
            "SELECT id, fullname, username, phone, role, regdate
             FROM users
             WHERE fullname LIKE ? OR username LIKE ? OR phone LIKE ? OR regdate LIKE ?
             ORDER BY id DESC
             LIMIT ?, ?"
        );
        $stmt->bind_param("ssssii", $like, $like, $like, $like, $start, $limit);
    } else {
        $stmt = $conn->prepare(
            "SELECT id, fullname, username, phone, role, regdate
             FROM users
             ORDER BY id DESC
             LIMIT ?, ?"
        );
        $stmt->bind_param("ii", $start, $limit);
    }
    $stmt->execute();
    return $stmt->get_result();
}

function countUsers($conn, $search) {
    if ($search !== '') {
        $like = "%{$search}%";
        $stmt = $conn->prepare(
            "SELECT COUNT(*) FROM users
             WHERE fullname LIKE ? OR username LIKE ? OR phone LIKE ? OR regdate LIKE ?"
        );
        $stmt->bind_param("ssss", $like, $like, $like, $like);
    } else {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users");
    }
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    return $count;
}

if (isset($_POST['ajax'])) {
    $page = max(1, intval($_POST['page'] ?? 1));
    $search = trim($_POST['search'] ?? '');
    $start = ($page - 1) * $limit;

    $totalData = countUsers($conn, $search);
    $totalPage = max(1, ceil($totalData / $limit));
    $result = fetchUsers($conn, $search, $start, $limit);

    $table = '<table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Fullname</th>
            <th>Username</th>
            <th>Phone</th>
            <th>Registration Date</th>
            <th>Role</th>
            <th>Action</th>
        </tr>';

    while ($row = $result->fetch_assoc()) {
        $table .= '<tr>
            <td>'.htmlspecialchars($row['id']).'</td>
            <td>'.htmlspecialchars($row['fullname']).'</td>
            <td>'.htmlspecialchars($row['username']).'</td>
            <td>'.htmlspecialchars($row['phone']).'</td>
            <td>'.htmlspecialchars($row['regdate']).'</td>
            <td>'.htmlspecialchars($row['role']).'</td>
            <td>
                <a href="delete_user.php?id='.urlencode($row['id']).'" class="btn btn-danger btn-sm" onclick="return confirm(\'Delete this user?\')">Delete</a>
            </td>
        </tr>';
    }

    $table .= '</table>';

    $pagination = '<nav><ul class="pagination">';
    for ($i = 1; $i <= $totalPage; $i++) {
        $active = ($i === $page) ? 'active' : '';
        $pagination .= '<li class="page-item '.$active.'">
            <a class="page-link" href="#" data-page="'.$i.'">'.$i.'</a>
        </li>';
    }
    $pagination .= '</ul></nav>';

    echo json_encode([
        'table' => $table,
        'pagination' => $pagination
    ]);
    exit;
}

$page = 1;
$start = 0;
$result = fetchUsers($conn, '', $start, $limit);
?>

<h2>Manage Users</h2>

<input type="text" id="search" class="form-control mb-3" placeholder="Search by name, username, phone, date...">

<div id="userTable">
    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Fullname</th>
            <th>Username</th>
            <th>Phone</th>
            <th>Registration Date</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['regdate']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                    <a href="delete_user.php?id=<?= urlencode($row['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<div id="pagination"></div>

<?php include "footer_admin.php"; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    loadData(1);

    $('#search').on('keyup', function () {
        loadData(1);
    });

    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        loadData($(this).data('page'));
    });

    function loadData(page) {
        $.ajax({
            url: 'users.php',
            type: 'POST',
            data: {
                ajax: 1,
                page: page,
                search: $('#search').val()
            },
            success: function (res) {
                var data = JSON.parse(res);
                $('#userTable').html(data.table);
                $('#pagination').html(data.pagination);
            }
        });
    }
});
</script>