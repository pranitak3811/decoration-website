<?php
header('Content-Type: application/json');
require_once 'config.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_bookings':
        $conn = getDB();
        $result = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC");
        $items = [];
        while ($row = $result->fetch_assoc()) $items[] = $row;
        echo json_encode(['success' => true, 'data' => $items]);
        $conn->close();
        break;

    case 'get_contacts':
        $conn = getDB();
        $result = $conn->query("SELECT * FROM contacts ORDER BY created_at DESC");
        $items = [];
        while ($row = $result->fetch_assoc()) $items[] = $row;
        echo json_encode(['success' => true, 'data' => $items]);
        $conn->close();
        break;

    case 'update_booking':
        $conn = getDB();
        $id = intval($_POST['id'] ?? 0);
        $status = $conn->real_escape_string($_POST['status'] ?? '');
        $allowed = ['pending', 'confirmed', 'completed', 'cancelled'];
        if (!in_array($status, $allowed)) {
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            break;
        }
        if ($conn->query("UPDATE bookings SET status='$status' WHERE id=$id")) {
            echo json_encode(['success' => true, 'message' => "Booking #$id marked as $status."]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Update failed.']);
        }
        $conn->close();
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>
