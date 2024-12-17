<?php
$data = json_decode(file_get_contents('php://input'), true);
$selectedPattern = $data['pattern'];

// Define the correct color pattern
$correctPattern = ["Red", "Green", "Blue"];

// Check if the selected pattern matches the correct one
if ($selectedPattern === $correctPattern) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>