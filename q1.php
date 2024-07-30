<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nhập liệu học sinh</title>
</head>
<body>
     <!-- Tạo form để nhập tên và điểm của 5 học sinh -->
    <form action="" method="post">
        <h2>Nhập tên và điểm của 5 học sinh:</h2>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <div>
                <!-- Trường nhập liệu cho tên học sinh -->
                <label for="name<?= $i ?>">Tên học sinh <?= $i ?>:</label>
                <input type="text" id="name<?= $i ?>" name="students[<?= $i ?>][name]" required>
                <!-- Ex: id="name1" name="students[1][name] -->
                 <!-- Trường nhập liệu cho điểm học sinh -->
                <label for="score<?= $i ?>">Điểm:</label>
                <input type="number" id="score<?= $i ?>" name="students[<?= $i ?>][score]" min="0" max="10" step="0.1" required>
            </div>          
        <?php endfor; ?>
        <!-- Kết thúc vòng lặp -->
        <button type="submit">Gửi</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $students = $_POST['students'];
        $valid = true;

        // Validate dữ liệu và tạo mảng học sinh
        foreach ($students as &$student) { // lặp từng phần tử có trong mảng students
            $student['name'] = htmlspecialchars($student['name']); //để bảo mật, ngăn chặn các tấn công XSS.
            $student['score'] = (float)$student['score']; // chuyển điểm thành số thực.
            if ($student['score'] < 0 || $student['score'] > 10) {
                $valid = false;
                echo "<p>Điểm của học sinh {$student['name']} không hợp lệ. Vui lòng nhập lại.</p>";
                break;
            }
        }
        unset($student);//giải phóng bộ nhớ

        if ($valid) {
            // Sắp xếp mảng theo điểm giảm dần
            usort($students, function ($a, $b) {
                return $b['score'] <=> $a['score'];
            });

            // Hiển thị kết quả xếp loại
            echo "<h2>Kết quả xếp loại:</h2>";
            echo "<table border='1'>
                    <tr>
                        <th>Tên</th>
                        <th>Điểm</th>
                        <th>Xếp loại</th>
                    </tr>";
            $totalScore = 0;
            foreach ($students as $student) {
                $totalScore += $student['score'];
                if ($student['score'] >= 8) {
                    $rating = "Xuất sắc";
                } elseif ($student['score'] >= 6.5) {
                    $rating = "Khá";
                } elseif ($student['score'] >= 5) {
                    $rating = "Trung bình";
                } else {
                    $rating = "Yếu";
                }
                echo "<tr>
                        <td>{$student['name']}</td>
                        <td>{$student['score']}</td>
                        <td>{$rating}</td>
                    </tr>";
            }
            echo "</table>";

            // Tính và hiển thị điểm trung bình của cả lớp
            $averageScore = $totalScore / count($students);
            echo "<p>Điểm trung bình của cả lớp: {$averageScore}</p>";
        }
    }
    ?>
</body>
</html>
