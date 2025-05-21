<?php
include 'db.php';

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT * FROM transactions";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>รายการรายรับรายจ่าย</title>
</head>
<body>
    <h1>รายการรายรับรายจ่าย</h1>
    <table border="1">
        <tr>
            <th>ว/ด/ป</th>
            <th>รายการ</th>
            <th>รายรับ</th>
            <th>รายจ่าย</th>
            <th>งบประมาณ</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // ตรวจสอบว่ามี id_budget หรือไม่
                if (isset($row['id_budget']) && !empty($row['id_budget'])) {
                    // คำนวณ balance จากรายรับและรายจ่าย
                    $balance = $row['income'] - $row['expense'];
                    // ดึงงบประมาณ
                    $sql_budget = "SELECT balance FROM budget WHERE id_budget = " . $row['id_budget'];
                    $result_budget = $conn->query($sql_budget);
                    if ($result_budget->num_rows > 0) {
                        $budget_data = $result_budget->fetch_assoc();
                        $budget = $budget_data['balance'];
                    } else {
                        $budget = "ไม่พบ id_budget";
                    }
                } else {
                    $budget = "ไม่พบ id_budget";
                }

                echo "<tr>";
                echo "<td>" . $row['date'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "<td>" . $row['income'] . "</td>";
                echo "<td>" . $row['expense'] . "</td>";
                echo "<td>" . $budget . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>ไม่มีข้อมูล</td></tr>";
        }
        ?>
    </table>

    <h2>เพิ่มรายการ</h2>
    <form action="add_transaction.php" method="post">
        <label for="date">ว/ด/ป:</label>
        <input type="date" id="date" name="date" required><br><br>
        <label for="description">รายการ:</label>
        <input type="text" id="description" name="description" required><br><br>
        <label for="income">รายรับ:</label>
        <input type="number" id="income" name="income" step="0.01"><br><br>
        <label for="expense">รายจ่าย:</label>
        <input type="number" id="expense" name="expense" step="0.01"><br><br>
        <input type="submit" value="เพิ่มรายการ">
    </form>
</body>
</html>

<?php
$conn->close();
?>
