<?php
$file = "zK82pLx9_hidden480.json";

if (!file_exists($file)) {
    $init = array_fill(0, 481, null);
    file_put_contents($file, json_encode($init, JSON_PRETTY_PRINT));
}

$data = json_decode(file_get_contents($file), true);

$done = false;
$message = "";
/* 🔒 ล็อกทันทีถ้าเคยกรอกแล้ว */
if (isset($_COOKIE['decision_lock'])) {
    $done = true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST["accept"])) {
        $message = "กรุณายืนยันว่าเข้าใจว่าเว็บไซต์นี้ไม่ใช่เว็บไซต์ทางการ ❌";
    }
    elseif (isset($_COOKIE['decision_lock'])) {
        $done = true;
    }
    else {

        $type = $_POST["type"] ?? "";
        $decision = $_POST["decision"] ?? "";
        $rank = 0;
        $index = 0;

        if ($type === "real") {
            $rank = intval($_POST["real_rank"] ?? 0);
            $index = $rank;
        } 
        elseif ($type === "reserve") {
            $rank = intval($_POST["reserve_rank"] ?? 0);
            $index = 240 + $rank;
        }

        if ($rank < 1 || $rank > 240) {
            $message = "ลำดับไม่ถูกต้อง ❌";
        }
        elseif ($data[$index] !== null) {
            
            setcookie("decision_lock", "locked", time() + (10 * 365 * 24 * 60 * 60));
            $message = "ลำดับนี้ถูกดำเนินการแล้ว ❌";
            $done = true;
        }
        else {
            $data[$index] = ($decision === "withdraw") ? 1 : 0;
            file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
            setcookie("decision_lock", "locked", time() + (10 * 365 * 24 * 60 * 60));
            $done = true;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>แบบแสดงความประสงค์</title>

<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Kanit', sans-serif;
    background:#f4f7fb;
    margin:0;
}
.top-warning {
    background:#dc2626;
    color:white;
    text-align:center;
    padding:10px;
    font-size:14px;
}
.container {
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}
.card {
    background:white;
    padding:35px;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    width:480px;
}
.box {
    border:1px solid #e5e7eb;
    border-radius:12px;
    padding:14px 18px;
    margin-bottom:12px;
    transition:0.2s;
}
.box.disabled {
    background:#f3f4f6;
    color:#9ca3af;
}
.row {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:10px;
}
.rank-input {
    width:90px;
    padding:6px 8px;
    border-radius:6px;
    border:1px solid #d1d5db;
}
.rank-input:disabled {
    background:#e5e7eb;
}
button {
    width:100%;
    padding:10px;
    background:#2563eb;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-family:'Kanit';
}
button:hover { background:#1d4ed8; }

.footer {
    margin-top:20px;
    font-size:12px;
    color:#6b7280;
    text-align:center;
}
.success-box {
    text-align:center;
}
.success-box h3 {
    color:#16a34a;
}
.nav-buttons a {
    display:block;
    margin-top:10px;
    padding:10px;
    background:#3b82f6;
    color:white;
    text-decoration:none;
    border-radius:8px;
}
.nav-buttons a.secondary {
    background:#64748b;
}
.notice {
    background:#fef2f2;
    border:1px solid #fecaca;
    padding:10px;
    border-radius:8px;
    font-size:13px;
    margin-bottom:15px;
    color:#991b1b;
}
</style>

<script>
function toggleType(type) {

    const realBox = document.getElementById("realBox");
    const reserveBox = document.getElementById("reserveBox");

    const realInput = document.getElementById("real_rank");
    const reserveInput = document.getElementById("reserve_rank");

    if (type === "real") {
        realBox.classList.remove("disabled");
        reserveBox.classList.add("disabled");

        realInput.disabled = false;
        reserveInput.disabled = true;
        reserveInput.value = "";
    } else {
        reserveBox.classList.remove("disabled");
        realBox.classList.add("disabled");

        reserveInput.disabled = false;
        realInput.disabled = true;
        realInput.value = "";
    }
}
</script>

</head>
<body>

<div class="top-warning">
⚠ เว็บไซต์นี้ไม่ใช่เว็บไซต์ทางการของโรงเรียน เป็นการจัดทำโดยบุคคลภายนอก
</div>

<div class="container">
<div class="card">

<?php if ($done): ?>

<div class="success-box">
<h3>ดำเนินการเรียบร้อยแล้ว ✅</h3>
<p>คุณไม่สามารถทำรายการซ้ำได้</p>

<div class="nav-buttons">
<a href="index.php">ไปหน้าสรุปผล</a>
<a href="check.php" class="secondary">ดูว่าสำรองของคุณจะติดไหม</a>
</div>
</div>

<?php else: ?>

<h2 style="text-align:center;">แบบแสดงความประสงค์</h2>

<div class="notice">
เว็บไซต์นี้จัดทำโดยบุคคลภายนอก ไม่มีความเกี่ยวข้องกับโรงเรียน และไม่มีผลใดๆต่อสิทธิ์จริง **โปรดอย่าปั่นเพราะมันคือความหวังของอีกหลายคน
</div>
<div class="notice">
คุณสามารถดูสถิติได้หลังจากกรอกข้อมูลนี้
</div>

<?php if ($message != "") echo "<div style='color:red;text-align:center;margin-bottom:10px;'>$message</div>"; ?>

<form method="POST">

<!-- ตัวจริง -->
<div id="realBox" class="box">
    <div class="row">
        <label>
            <input type="radio" name="type" value="real"
                   onclick="toggleType('real')" required>
            <strong>คุณคือตัวจริงลำดับที่</strong>
        </label>
        <input type="number"
               id="real_rank"
               name="real_rank"
               min="1"
               max="240"
               class="rank-input"
               disabled>
    </div>
</div>

<!-- สำรอง -->
<div id="reserveBox" class="box">
    <div class="row">
        <label>
            <input type="radio" name="type" value="reserve"
                   onclick="toggleType('reserve')" required>
            <strong>คุณคือสำรองลำดับที่</strong>
        </label>
        <input type="number"
               id="reserve_rank"
               name="reserve_rank"
               min="1"
               max="240"
               class="rank-input"
               disabled>
    </div>
</div>

<br>

<strong>คุณมีความประสงค์อย่างแน่นอนที่จะ</strong><br><br>
<label><input type="radio" name="decision" value="confirm" required> ยืนยันสิทธิ์</label><br>
<label><input type="radio" name="decision" value="withdraw" required> สละสิทธิ์</label>

<br><br>

<label>
<input type="checkbox" name="accept" required>
ข้าพเจ้าเข้าใจว่าเว็บไซต์นี้ไม่ใช่เว็บไซต์ทางการ และข้อมูลลำดับที่คุณกรอกจะไม่ถูกเปิดเผย
</label>

<br><br>

<button type="submit">ยืนยันการทำรายการ</button>

</form>

<div class="footer">
กรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนกดยืนยันเพราะจะไม่สามารถแก้ไขได้อีก
</div>

<?php endif; ?>

</div>
</div>

</body>
</html>