<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div>
        <h3>Main Menu</h3>
        <button onclick="navigateTo('dashboard.php')">Board</button>
        <button onclick="navigateTo('members.php')">Members</button>
        <button onclick="navigateTo('calendar.php')">Calendar</button>
    </div>
    <br>
    <form action="create_board.php" method="post">
        <button type="submit">Create Board</button>
    </form>


</body>

</html>
<script>
    // Function to navigate to specific pages
    function navigateTo(page) {
        window.location.href = page;
    }
</script>