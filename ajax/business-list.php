<?php
include '../config/db.php';

$query = "
SELECT 
    b.id,
    b.name,
    b.address,
    b.phone,
    b.email,
    COALESCE(AVG(r.rating), 0) AS avg_rating
FROM businesses b
LEFT JOIN ratings r 
    ON r.business_id = b.id 
    AND r.delete_flag = 0
WHERE b.delete_flag = 0
GROUP BY b.id, b.name, b.address, b.phone, b.email
ORDER BY b.id DESC
";

$result = mysqli_query($conn, $query);

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode([
    "status" => true,
    "data" => $data
]);