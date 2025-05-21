<?php
include 'db.php';

$date = $_POST['date'];
$description = $_POST['description'];
$income = isset($_POST['income']) ? $_POST['income'] : 0.00;
$expense = isset($_POST['expense']) ? $_POST['expense'] : 0.00;

// เพิ่มข้อมูลลงในฐานข้อมูล
$sql = "INSERT INTO transactions (date, description, income, expense, id_budget) VALUES ('$date', '$description', '$income', '$expense''$id_budget', LAST_INSERT_ID())";

if ($conn->query($sql) === TRUE) {
    // ปรับปรุงงบประมาณ
    $sql_budget = "SELECT balance FROM budget ORDER BY id_budget DESC LIMIT 1";
    $result_budget = $conn->query($sql_budget);
    $current_budget = $result_budget->fetch_assoc()['balance'];

    $new_budget = $current_budget + $income - $expense;
    $sql_update_budget = "INSERT INTO budget (id_budget, balance) VALUES (LAST_INSERT_ID(), '$id_budget')";
    
    if ($conn->query($sql_update_budget) === TRUE) {
        echo "เพิ่มรายการและปรับปรุงงบประมาณสำเร็จ";
    } else {
        echo "เกิดข้อผิดพลาดในการปรับปรุงงบประมาณ: " . $conn->error;
    }
} else {
    echo "เกิดข้อผิดพลาดในการเพิ่มรายการ: " . $conn->error;
}

$conn->close();

header("Location: index.php");
exit();
?>
