<?php
include '../db_connect.php';

// Fetch the count of users
$user_count_query = "SELECT COUNT(*) AS user_count FROM users";
$user_count_result = $conn->query($user_count_query);
$user_count = $user_count_result->fetch_assoc()['user_count'];

// Fetch the count of classes
$class_count_query = "SELECT COUNT(*) AS class_count FROM classes";
$class_count_result = $conn->query($class_count_query);
$class_count = $class_count_result->fetch_assoc()['class_count'];

$book_count_query = "SELECT COUNT(*) AS book_count FROM books";
$book_count_result = $conn->query($book_count_query);
$book_count = $book_count_result->fetch_assoc()['book_count'];

$reservation_count_query = "SELECT COUNT(*) AS reservation_count FROM reservations";
$reservation_count_result = $conn->query($reservation_count_query);
$reservation_count = $reservation_count_result->fetch_assoc()['reservation_count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
	<link rel="stylesheet" href="../assets/css/ready.css">
	<link rel="stylesheet" href="../assets/css/demo.css">
</head>
<body>
<div class="sidebar">
				<div class="scrollbar-inner sidebar-wrapper">
					
					<ul class="nav">
						<li class="nav-item active">
							<a href="admin_dashboard.php">
								<i class="la la-home"></i>
								<p>Home</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="registration.php">
								<i class="la la-user"></i>
								<p>Registration</p>
								<span class="badge badge-count"><?php echo $user_count; ?></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="class.php">
							<i class="la la-comment"></i>
								<p>Class</p>
								<span class="badge badge-count"><?php echo $class_count; ?></span>
							</a>
						</li>
						<li class="nav-item">
							<a href="book.php">
							<i class="la la-book"></i>
								<p>Books</p>
								<span class="badge badge-count"><?php echo $book_count; ?></span>
							</a>
						</li>

						<li class="nav-item">
							<a href="reservation.php">
							<i class="la la-book"></i>
								<p>Reservation</p>
								<span class="badge badge-count"><?php echo $reservation_count; ?></span>
							</a>
						</li>
						
					</ul>
				</div>
			</div>
</body>
</html>