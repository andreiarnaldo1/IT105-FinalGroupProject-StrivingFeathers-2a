<?php include('db.php'); 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fn = $_POST['fname']; $ln = $_POST['lname']; $em = $_POST['email']; $dob = $_POST['dob'];

    // --- START TRANSACTION (Mandatory Requirement) ---
    $conn->begin_transaction();
    try {
        $sql = "INSERT INTO Students (first_name, last_name, email, date_of_birth) VALUES ('$fn', '$ln', '$em', '$dob')";
        $conn->query($sql);

        // Commit if successful
        $conn->commit();
        header("Location: dashboard.php?role=Admin");
    } catch (Exception $e) {
        // Rollback if anything fails
        $conn->rollback();
        echo "Transaction Error: " . $e->getMessage();
    }
}
?>
<body style="font-family: Arial; padding: 40px;">
    <h2>Register Student (SQL Transaction)</h2>
    <form method="POST">
        <input name="fname" placeholder="First Name" required><br><br>
        <input name="lname" placeholder="Last Name" required><br><br>
        <input name="email" placeholder="Email" type="email" required><br><br>
        <input type="date" name="dob" required><br><br>
        <button type="submit" style="background:green; color:white; padding:10px;">Save with Transaction</button>
    </form>
</body>