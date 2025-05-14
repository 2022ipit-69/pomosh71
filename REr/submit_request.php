<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "help_requests";

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if (
    isset($_POST['first_name'], $_POST['last_name'], $_POST['phone'], $_POST['email'], $_POST['description'])
) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $description = $_POST['description'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Неверный формат email.");
    }

    if (!preg_match('/^\+?[0-9\s\-]{7,20}$/', $phone)) {
        die("Неверный формат номера телефона.");
    }

    $stmt = $conn->prepare("INSERT INTO requests (first_name, last_name, phone, email, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $phone, $email, $description);

    if ($stmt->execute()) {
        echo '<div style="text-align: center; font-family: Arial, sans-serif; padding: 40px;">
                <h2 style="color: black;">Заявка успешно отправлена!</h2>
                <button onclick="history.back()" style="
                    margin-top: 20px;
                    padding: 12px 24px;
                    background: linear-gradient(135deg,rgb(199, 51, 18),rgb(204, 140, 11));
                    color: white;
                    border: none;
                    border-radius: 12px;
                    font-size: 16px;
                    font-weight: 500;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
                " 
                onmouseover="this.style.transform=\'scale(1.05)\'"
                onmouseout="this.style.transform=\'scale(1)\'"
                >Вернуться назад</button>
              </div>';
    } else {
        echo "Ошибка при отправке: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Пожалуйста, заполните все поля.";
}

$conn->close();
?>
