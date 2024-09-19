<?php
include '../db_connect.php';
include 'header.php';
include 'nav.php';

// Check if the user is an admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Handle status update
if (isAdmin() && isset($_POST['update_status'])) {
    $reservation_id = $_POST['reservation_id'];
    $new_status = $_POST['new_status'];
    
    $update_query = "UPDATE reservations SET status = ? WHERE reservation_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $new_status, $reservation_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Reservation status updated successfully.";
    } else {
        $_SESSION['message'] = "Error updating reservation status: " . $conn->error;
    }
    
    $stmt->close();
    header("Location: reservation.php");
    exit();
}

// Fetch reservations
$sql = "SELECT r.reservation_id, r.user_id, r.book_id, r.reserve_fromdate, r.reserve_todate, r.status, r.created_at,
               u.name AS user_name, b.title AS book_title
        FROM reservations r
        JOIN users u ON r.user_id = u.user_id
        JOIN books b ON r.book_id = b.book_id
        ORDER BY CASE WHEN r.status = 'pending' THEN 0 ELSE 1 END, r.created_at DESC";
$result = $conn->query($sql);

// Fetch status options
$status_options = ['pending', 'approved', 'rejected'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>LMS - Reservation List</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="../assets/css/ready.css">
    <link rel="stylesheet" href="../assets/css/demo.css">
</head>
<body>
    <div class="wrapper">
        <div class="main-panel">
            <div class="content">
                <div class="container-fluid">
                    <h4 class="page-title">Reservation List</h4>

                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <?php 
                            echo $_SESSION['message']; 
                            unset($_SESSION['message']);
                            ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">
                            <?php if ($result->num_rows > 0) : ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Book</th>
                                                <th>From Date</th>
                                                <th>To Date</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <?php if (isAdmin()): ?>
                                                    <th>Action</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()) : ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['reservation_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['book_title']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['reserve_fromdate']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['reserve_todate']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                                    <?php if (isAdmin()): ?>
                                                        <td>
                                                            <form method="POST" action="reservation.php">
                                                                <input type="hidden" name="reservation_id" value="<?php echo $row['reservation_id']; ?>">
                                                                <select name="new_status" class="form-control form-control-sm d-inline-block w-auto mr-2">
                                                                    <?php foreach ($status_options as $option): ?>
                                                                        <option value="<?php echo $option; ?>" <?php echo ($row['status'] == $option) ? 'selected' : ''; ?>>
                                                                            <?php echo ucfirst($option); ?>
                                                                        </option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <button type="submit" name="update_status" class="btn btn-primary btn-sm">Update</button>
                                                            </form>
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else : ?>
                                <p>No reservations found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="../assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
    <script src="../assets/js/plugin/chartist/chartist.min.js"></script>
    <script src="../assets/js/plugin/chartist/plugin/chartist-plugin-tooltip.min.js"></script>
    <script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="../assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
    <script src="../assets/js/plugin/jquery-mapael/jquery.mapael.min.js"></script>
    <script src="../assets/js/plugin/jquery-mapael/maps/world_countries.min.js"></script>
    <script src="../assets/js/plugin/chart-circle/circles.min.js"></script>
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="../assets/js/ready.min.js"></script>
</body>
</html>