<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'config.php';
require_once 'mailer.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'submit_booking': submitBooking(); break;
    case 'get_gallery':    getGallery();    break;
    case 'get_services':   getServices();   break;
    case 'submit_contact': submitContact(); break;
    default: echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function submitBooking() {
    $conn = getDB();
    $name    = $conn->real_escape_string(trim($_POST['client_name'] ?? ''));
    $email   = $conn->real_escape_string(trim($_POST['email'] ?? ''));
    $phone   = $conn->real_escape_string(trim($_POST['phone'] ?? ''));
    $event   = $conn->real_escape_string(trim($_POST['event_type'] ?? ''));
    $date    = $conn->real_escape_string(trim($_POST['event_date'] ?? ''));
    $venue   = $conn->real_escape_string(trim($_POST['venue'] ?? ''));
    $guests  = intval($_POST['guest_count'] ?? 0);
    $budget  = $conn->real_escape_string(trim($_POST['budget'] ?? ''));
    $special = $conn->real_escape_string(trim($_POST['special_requests'] ?? ''));

    if (!$name || !$email || !$phone || !$event || !$date || !$venue || !$guests) {
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']); return;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']); return;
    }

    $sql = "INSERT INTO bookings (client_name, email, phone, event_type, event_date, venue, guest_count, budget, special_requests)
            VALUES ('$name', '$email', '$phone', '$event', '$date', '$venue', $guests, '$budget', '$special')";

    if ($conn->query($sql)) {
        $booking_id = $conn->insert_id;

        // Email to CLIENT
        $clientSubject = "Booking Confirmed! #$booking_id — Aura Décor";
        $clientBody = "
<html><body style='font-family:Arial,sans-serif;background:#f9f3e3;padding:20px;'>
<div style='max-width:600px;margin:0 auto;background:white;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.1);'>
  <div style='background:#0D0B08;padding:32px;text-align:center;'>
    <h1 style='color:#C9A84C;margin:0;font-size:28px;'>✦ Aura Décor</h1>
    <p style='color:rgba(255,255,255,0.6);margin:8px 0 0;font-size:14px;'>Premium Event Decoration</p>
  </div>
  <div style='padding:40px 32px;'>
    <h2 style='color:#0D0B08;'>Booking Received! 🎉</h2>
    <p style='color:#666;'>Dear <strong>$name</strong>, thank you for choosing Aura Décor! We'll contact you within <strong>24 hours</strong>.</p>
    <div style='background:#F9F3E3;border-radius:10px;padding:24px;margin:24px 0;border-left:4px solid #C9A84C;'>
      <h3 style='color:#0D0B08;margin:0 0 16px;'>📋 Booking Details</h3>
      <table style='width:100%;font-size:14px;'>
        <tr><td style='padding:6px 0;color:#999;width:40%;'>Booking ID</td><td style='font-weight:700;'>#$booking_id</td></tr>
        <tr><td style='padding:6px 0;color:#999;'>Event Type</td><td>$event</td></tr>
        <tr><td style='padding:6px 0;color:#999;'>Event Date</td><td>$date</td></tr>
        <tr><td style='padding:6px 0;color:#999;'>Venue</td><td>$venue</td></tr>
        <tr><td style='padding:6px 0;color:#999;'>Guests</td><td>$guests</td></tr>
        <tr><td style='padding:6px 0;color:#999;'>Budget</td><td>$budget</td></tr>
        <tr><td style='padding:6px 0;color:#999;'>Phone</td><td>$phone</td></tr>
      </table>
    </div>
    <p style='color:#666;'>Our team will call you on <strong>$phone</strong> to discuss your requirements.</p>
  </div>
  <div style='background:#0D0B08;padding:24px;text-align:center;'>
    <p style='color:rgba(255,255,255,0.4);font-size:12px;margin:0;'>📞 +91 98765 43210 &nbsp;|&nbsp; ✉️ hello@auradecor.in</p>
  </div>
</div>
</body></html>";
        sendEmail($email, $name, $clientSubject, $clientBody);

        // Email to OWNER
        $ownerSubject = "🔔 New Booking #$booking_id — $event by $name";
        $ownerBody = "
<html><body style='font-family:Arial;padding:20px;'>
<div style='max-width:600px;margin:0 auto;background:white;border-radius:12px;padding:32px;'>
  <h2 style='color:#C9A84C;'>New Booking Received!</h2>
  <table style='width:100%;font-size:15px;border-collapse:collapse;'>
    <tr style='background:#f5f5f5;'><td style='padding:10px;font-weight:700;'>Booking ID</td><td style='padding:10px;'>#$booking_id</td></tr>
    <tr><td style='padding:10px;font-weight:700;'>Client</td><td style='padding:10px;'>$name</td></tr>
    <tr style='background:#f5f5f5;'><td style='padding:10px;font-weight:700;'>Email</td><td style='padding:10px;'>$email</td></tr>
    <tr><td style='padding:10px;font-weight:700;'>Phone</td><td style='padding:10px;'>$phone</td></tr>
    <tr style='background:#f5f5f5;'><td style='padding:10px;font-weight:700;'>Event</td><td style='padding:10px;'>$event</td></tr>
    <tr><td style='padding:10px;font-weight:700;'>Date</td><td style='padding:10px;'>$date</td></tr>
    <tr style='background:#f5f5f5;'><td style='padding:10px;font-weight:700;'>Venue</td><td style='padding:10px;'>$venue</td></tr>
    <tr><td style='padding:10px;font-weight:700;'>Guests</td><td style='padding:10px;'>$guests</td></tr>
    <tr style='background:#f5f5f5;'><td style='padding:10px;font-weight:700;'>Budget</td><td style='padding:10px;'>$budget</td></tr>
    <tr><td style='padding:10px;font-weight:700;'>Special Requests</td><td style='padding:10px;'>$special</td></tr>
  </table>
</div>
</body></html>";
        sendEmail(OWNER_EMAIL, 'Aura Decor Admin', $ownerSubject, $ownerBody);

        echo json_encode(['success' => true, 'message' => "Booking confirmed! Your Booking ID is #$booking_id. Confirmation email sent to $email.", 'booking_id' => $booking_id]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Booking failed. Please try again.']);
    }
    $conn->close();
}

function getGallery() {
    $conn = getDB();
    $category = $conn->real_escape_string($_GET['category'] ?? 'all');
    $sql = $category === 'all' ? "SELECT * FROM gallery ORDER BY created_at DESC" : "SELECT * FROM gallery WHERE category='$category' ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $items = [];
    while ($row = $result->fetch_assoc()) $items[] = $row;
    echo json_encode(['success' => true, 'data' => $items]);
    $conn->close();
}

function getServices() {
    $conn = getDB();
    $result = $conn->query("SELECT * FROM services ORDER BY id ASC");
    $items = [];
    while ($row = $result->fetch_assoc()) $items[] = $row;
    echo json_encode(['success' => true, 'data' => $items]);
    $conn->close();
}

function submitContact() {
    $conn = getDB();
    $name    = $conn->real_escape_string(trim($_POST['name'] ?? ''));
    $email   = $conn->real_escape_string(trim($_POST['email'] ?? ''));
    $message = $conn->real_escape_string(trim($_POST['message'] ?? ''));
    if (!$name || !$email || !$message) { echo json_encode(['success' => false, 'message' => 'All fields are required.']); return; }
    if ($conn->query("INSERT INTO contacts (name, email, message) VALUES ('$name', '$email', '$message')")) {
        $ownerBody = "<html><body style='font-family:Arial;padding:20px;'><h3>New Contact Message</h3><p><strong>Name:</strong> $name</p><p><strong>Email:</strong> $email</p><p><strong>Message:</strong> $message</p></body></html>";
        sendEmail(OWNER_EMAIL, 'Aura Decor Admin', "New Message from $name", $ownerBody);
        echo json_encode(['success' => true, 'message' => "Message sent! We'll get back to you soon."]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send message.']);
    }
    $conn->close();
}
?>
