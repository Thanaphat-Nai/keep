<?php
$file = "zK82pLx9_hidden480.json";
$data = json_decode(file_get_contents($file), true);

$withdraw_real = 0;
$confirm_real = 0;
$numdata = 0;
$nailnwza = 0;
$samrong = 102;

for ($i = 1; $i <= 480; $i++) {
    if ($data[$i] === 1) $withdraw_real++;
    if ($data[$i] === 0) $confirm_real++;
}
for ($i = 1; $i <= 480; $i++) {
    if ($data[$i] === 1) $numdata++;
    if ($data[$i] === 0) $numdata++;
}

for ($i = 1; $i <= $samrong+240 ; $i++) {
    if ($data[$i] === 1) $nailnwza++;
    if ($data[$i] === 0) $nailnwza++;
}

/* ----- สถิติย้อนหลัง ----- */
$history = [
    27 => 190, // 19x ปัดเป็น 190 โดยประมาณ
    28 => 169,
    29 => 166,
    30 => 137,
    31 => 118,
    32 => 104,
    33 => 154,
    34 => 140,
    35 => 146
];

$average = round(array_sum($history) / count($history));
$max = max($history);
$min = min($history);
$max_year = array_search($max, $history);
$min_year = array_search($min, $history);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>สรุปสถิติ</title>
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body { font-family:'Kanit'; background:#f4f7fb; margin:0; }

.top-warning {
    background:#dc2626; color:white; text-align:center;
    padding:10px; font-size:14px;
}

.container {
    display:flex; justify-content:center;
    padding:40px 20px;
}

.card {
    background:white;
    padding:40px;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    width:800px;
}

h2 { text-align:center; margin-bottom:25px; }

.stat {
    margin:10px 0;
    font-size:18px;
}

.green { color:#16a34a; }
.red { color:#dc2626; }
.blue { color:#0A1172; }

table {
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th, td {
    padding:10px;
    border-bottom:1px solid #e5e7eb;
    text-align:center;
}

th {
    background:#f1f5f9;
}

.analysis {
    margin-top:30px;
    padding:20px;
    background:#f8fafc;
    border-radius:12px;
    font-size:15px;
}

a.button {
    display:inline-block;
    margin-top:20px;
    padding:10px 15px;
    background:#2563eb;
    color:white;
    text-decoration:none;
    border-radius:8px;
}

.footer-buttons {
    text-align:center;
    margin-top:30px;
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
</head>

<body>

<div class="top-warning">
⚠ เว็บไซต์นี้ไม่ใช่เว็บไซต์ทางการของโรงเรียน ข้อมูลจัดทำโดยบุคคลภายนอก
</div>

<div class="container">
<div class="card">

<h2>สถิติการแสดงความประสงค์ ปีปัจจุบัน</h2>

<div class="notice">
ข้อมูลบางส่วนอาจมาจากการรวบรวมข้อมูลของAdminเพิ่มเติม
</div>
<div class="stat blue">
มีผู้กรอก(รวมข้อมูลทุกลำดับ)แล้ว <strong><?php echo $numdata; ?></strong> / 480 คน
</div>

<div class="stat red">
มีคนที่ยืนยันว่าจะสละสิทธิ์แล้ว <strong><?php echo $withdraw_real; ?></strong> คน
</div>

<div class="stat green">
มีคนที่ยืนยันว่าจะยืนยันสิทธิ์แล้ว <strong><?php echo $confirm_real; ?></strong> คน
</div>

<div class="stat blue">
มีข้อมูลของผู้มีสิทธ์ยืนยันสิทธ์(ตัวจริงและตัวสำรองถึงลำดับที่<?php echo $samrong; ?>)แล้ว <strong><?php echo $nailnwza; ?></strong> / <?php echo $samrong+240; ?> คน 
</div>

<hr style="margin:30px 0;">

<h2>สถิติการเรียกสำรองย้อนหลัง (MWIT)</h2>

<table>
<tr>
<th>รุ่น</th>
<th>ปีการศึกษา</th>
<th>เรียกถึงลำดับ</th>
</tr>

<?php
$year_start = 2560;
foreach ($history as $gen => $rank) {
    echo "<tr>
            <td>$gen</td>
            <td>$year_start</td>
            <td>$rank</td>
          </tr>";
    $year_start++;
}
?>
</table>

<div class="analysis">
<strong>วิเคราะห์แนวโน้ม:</strong><br><br>

• ค่าเฉลี่ยการเรียกสำรองย้อนหลังอยู่ที่ประมาณ <strong><?php echo $average; ?></strong> ลำดับ<br>
• ปีที่เรียกสูงสุดคือ รุ่น <?php echo $max_year; ?> (เรียกถึง <?php echo $max; ?>)<br>
• ปีที่เรียกต่ำสุดคือ รุ่น <?php echo $min_year; ?> (เรียกถึง <?php echo $min; ?>)<br><br>

จากข้อมูลย้อนหลัง จะเห็นว่าแนวโน้มช่วงหลังมีการเรียกสำรองลดลงจากช่วงรุ่น 27–29 
ก่อนจะกลับมาสูงขึ้นเล็กน้อยในรุ่น 33–35<br><br>

ทั้งนี้ จำนวนเรียกสำรองในแต่ละปีขึ้นอยู่กับอัตราการสละสิทธิ์ของตัวจริง 
ซึ่งอาจแตกต่างกันในแต่ละปี
</div>

<div class="footer-buttons">
<a href="submit.php" class="button">ไปหน้าแสดงความประสงค์</a>
<a href="check.php" class="button" style="background:#64748b;">ตรวจสอบสำรอง</a>
</div>

</div>
</div>

</body>
</html>