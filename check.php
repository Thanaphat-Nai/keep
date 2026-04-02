<?php
$file = "zK82pLx9_hidden480.json";
$data = json_decode(file_get_contents($file), true);

$locked = isset($_COOKIE['reserve_lock']);
$rank = 0;
$withdraw_count = 0;
$result = "";
$need = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$locked) {
    $rank = intval($_POST["rank"]);
    if ($rank >= 1 && $rank <= 240) {
        setcookie("reserve_lock", $rank, time() + (10 * 365 * 24 * 60 * 60));
        header("Location: check.php");
        exit;
    }
}

if ($locked) {
    $rank = intval($_COOKIE['reserve_lock']);

    for ($i = 1; $i < 240 + $rank; $i++) {
        if ($data[$i] === 1) $withdraw_count++;
    }

    if ($withdraw_count >= $rank) {
        $result = "ยินดีด้วย 🎉 สำรองอาจเรียกถึงคุณ";
    } else {
        $need = $rank - $withdraw_count;
        $result = "ต้องการผู้สละสิทธิ์ก่อนคุณอีกอย่างน้อย $need คน";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ตรวจสอบสำรอง</title>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">
<style>
body { font-family:'Kanit'; background:#f4f7fb; margin:0; }
.top-warning {
    background:#dc2626; color:white;
    text-align:center; padding:10px;
}
.container {
    display:flex; justify-content:center;
    align-items:center; min-height:100vh;
}
.card {
    background:white; padding:40px;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    width:450px; text-align:center;
}
input[type=number] {
    padding:8px; border-radius:6px;
    border:1px solid #d1d5db;
    width:120px;
}
button {
    font-family:'Kanit';
    padding:8px 15px;
    border:none;
    background:#2563eb;
    color:white;
    border-radius:8px;
    cursor:pointer;
}
button:hover { background:#1d4ed8; }

.modal {
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.4);
    justify-content:center;
    align-items:center;
}
.modal-content {
    background:white;
    padding:30px;
    border-radius:12px;
    width:350px;
    text-align:center;
}
.close-btn {
    margin-top:15px;
    background:#64748b;
}
a.button {
    display:block;
    margin-top:15px;
    padding:10px;
    background:#64748b;
    color:white;
    text-decoration:none;
    border-radius:8px;
}
</style>
</head>
<body>

<div class="top-warning">
⚠ เว็บไซต์นี้ไม่ใช่เว็บไซต์ทางการของโรงเรียน
</div>

<div class="container">
<div class="card">

<h2>ตรวจสอบสำรอง</h2>

<?php if (!$locked): ?>

<form method="POST">
กรอกลำดับสำรองของคุณ<br><br>
<input type="number" name="rank" min="1" max="240" required>
<br><br>
<button type="submit">บันทึกลำดับ</button>
</form>

<?php else: ?>

<p>คุณอยู่ลำดับสำรองที่ <strong><?php echo $rank; ?></strong></p>

<button onclick="openModal()">ดูว่าคุณจะติดไหม</button>

<?php endif; ?>

<a href="index.php" class="button">กลับหน้าสรุป</a>

</div>
</div>

<?php if ($locked): ?>
<div class="modal" id="resultModal">
<div class="modal-content">
<h3>ผลการประเมิน</h3>
<p>คุณอยู่ลำดับสำรองที่ <strong><?php echo $rank; ?></strong></p>
<p>มีผู้สละสิทธิ์ก่อนหน้าคุณแล้ว <strong><?php echo $withdraw_count; ?></strong> คน</p>
<hr>
<p><strong><?php echo $result; ?></strong></p>
<button class="close-btn" onclick="closeModal()">ปิด</button>
</div>
</div>

<script>
function openModal(){
    document.getElementById("resultModal").style.display="flex";
}
function closeModal(){
    document.getElementById("resultModal").style.display="none";
}
</script>
<?php endif; ?>

</body>
</html>