<?php
// โหลดข้อมูลจากไฟล์ JSON
$file = 'zK82pLx9_hidden480.json';

// อ่านข้อมูลจากไฟล์
$data = json_decode(file_get_contents($file), true);
if ($data === null) {
    $data = array_fill(1, 480, null);  // หากข้อมูลไม่ได้รับการโหลด ให้กำหนดค่าเริ่มต้นเป็น null
}

// ฟังก์ชันการอัปเดตสถานะ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบการส่งค่ามาจากฟอร์ม
    if (isset($_POST['index']) && isset($_POST['status'])) {
        $index = (int)$_POST['index'];  // รับหมายเลขที่ต้องการอัปเดต
        $status = $_POST['status'];  // รับสถานะ (0, 1, null)

        // แปลงสถานะให้เป็นตัวเลข (0 หรือ 1) หรือ null
        if ($status === '0') {
            $status = 0;  // แปลงเป็นตัวเลข 0
        } elseif ($status === '1') {
            $status = 1;  // แปลงเป็นตัวเลข 1
        } else {
            $status = null;  // หากเลือก 'null' ให้เป็น null
        }

        // ตรวจสอบว่าหมายเลขที่ได้รับอยู่ในช่วงที่ถูกต้อง
        if ($index >= 1 && $index <= 480) {
            $data[$index] = $status;  // อัปเดตข้อมูลในอาร์เรย์
            // บันทึกข้อมูลกลับไปที่ไฟล์
            file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));  
        }
    }
}

// ฟังก์ชันค้นหาหมายเลข
$search_term = isset($_GET['search']) ? (int)$_GET['search'] : '';  // ค้นหาหมายเลข

// สร้างตัวแปร version หรือ timestamp เพื่อใช้ในการล้างแคช
$timestamp = time();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสถานะ</title>
    <!-- เพิ่ม query string เพื่อบังคับให้เบราว์เซอร์โหลดไฟล์ใหม่ทุกครั้ง -->
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap&<?php echo $timestamp; ?>" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background: #f4f7fb;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            padding: 40px;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            width: 1000px;
            text-align: center;
        }

        h2 {
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
        }

        td {
            cursor: pointer;
        }

        .green {
            background-color: #16a34a;
            color: white;
        }

        .red {
            background-color: #dc2626;
            color: white;
        }

        .gray {
            background-color: #b0b0b0;
        }

        .search-box {
            margin-bottom: 20px;
        }

        .footer-buttons {
            margin-top: 30px;
        }

        .button {
            padding: 10px 15px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.3s ease-in-out;
        }

        .button:hover {
            background-color: #1d4ed8;
            transform: scale(1.05);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-button {
            padding: 10px 20px;
            background-color: #2563eb;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h2>แก้ไขสถานะ</h2>

        <!-- ฟอร์มค้นหาหมายเลข -->
        <div class="search-box">
            <form method="get">
                <input type="text" name="search" placeholder="ค้นหาหมายเลข (1-480)" value="<?php echo $search_term; ?>" />
                <button type="submit" class="button">ค้นหา</button>
            </form>
        </div>

        <!-- ตารางสถานะ -->
        <table>
            <thead>
                <tr>
                    <th>หมายเลข</th>
                    <th>ตัวจริง (1-240)</th>
                    <th>ตัวสำรอง (241-480)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // วนลูปเพื่อแสดงข้อมูลในตาราง
                for ($i = 1; $i <= 240; $i++) {
                    if ($search_term && $i != $search_term && $i + 240 != $search_term) continue;  // ข้ามแถวที่ไม่ตรงกับการค้นหา
                    echo "<tr>";
                    echo "<td>$i</td>";  // แสดงหมายเลขแถว
                    echo "<td class='" . ($data[$i] === null ? 'gray' : ($data[$i] === 1 ? 'red' : 'green')) . "'>";
                    // แทนที่ prompt ด้วย select dropdown
                    echo "<button onclick='openModal($i, \"" . ($data[$i] === null ? 'null' : ($data[$i] === 1 ? 'สละสิทธิ์' : 'ยืนยันสิทธิ์')) . "\")' class='button'>เลือกสถานะ</button>";
                    echo "</td>";

                    // สำหรับตัวสำรอง (241-480)
                    echo "<td class='" . ($data[$i + 240] === null ? 'gray' : ($data[$i + 240] === 1 ? 'red' : 'green')) . "'>";
                    echo "<button onclick='openModal(" . ($i + 240) . ", \"" . ($data[$i + 240] === null ? 'null' : ($data[$i + 240] === 1 ? 'สละสิทธิ์' : 'ยืนยันสิทธิ์')) . "\")' class='button'>เลือกสถานะ</button>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="footer-buttons">
            <a href="save.php" class="button">บันทึกการเปลี่ยนแปลง</a>
        </div>
    </div>
</div>

<!-- Modal Popup -->
<div id="statusModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>เลือกสถานะ</h2>
        <form id="statusForm" method="POST">
            <input type="hidden" name="index" id="modalIndex" />
            <select name="status" id="statusSelect">
                <option value="null">ไม่ระบุ</option>
                <option value="0">ยืนยันสิทธิ์</option>
                <option value="1">สละสิทธิ์</option>
            </select>
            <button type="submit" class="modal-button">ยืนยัน</button>
        </form>
    </div>
</div>

<script>
    // เปิด Modal
    function openModal(index, currentStatus) {
        document.getElementById("modalIndex").value = index;
        document.getElementById("statusSelect").value = (currentStatus === 'null' ? 'null' : (currentStatus === 'ยืนยันสิทธิ์' ? '0' : '1'));

        var modal = document.getElementById("statusModal");
        modal.style.display = "block";
    }

    // ปิด Modal
    var span = document.getElementsByClassName("close")[0];
    span.onclick = function() {
        var modal = document.getElementById("statusModal");
        modal.style.display = "none";
    }

    // เมื่อคลิกที่พื้นที่นอก modal
    window.onclick = function(event) {
        var modal = document.getElementById("statusModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>