<?php
$host = "127.0.0.1";
$user = "root";
$password = "12345678";
$dbname = "agenDb";

$con = mysqli_connect($host, $user, $password, $dbname);

$method = $_SERVER['REQUEST_METHOD'];

if (!$con) {
  die("Connection failed: " . mysqli_connect_error());
}

switch ($method) {
  case 'GET':
    $due = $_GET['due'];
    $sql = "select * from agen_dbs" . ($due ? " where due=\"$due\"" : "");
    break;
  case 'POST':
    $temp = json_decode(file_get_contents('php://input'), true);
    print_r($temp);
    $due = $temp["due"];
    $time = $temp["time"];
    $who = $temp["who"];
    $content = $temp["content"];
    $team = $temp["team"];
    $sql = "INSERT INTO agen_dbs (name, content, due, team, time) VALUES ('$who', '$content', '$due', '$team', '$time')";
    break;
}

$result = mysqli_query($con, $sql);
$myArray = array();

if (!$result) {
  http_response_code(404);
  die(mysqli_error($con));
}

// MYSQLI_ASSOC 返回关联数组
if ($method == 'GET') {
  while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    $myArray[] = $row;
  }
  echo json_encode($myArray);

} 
elseif ($method == 'POST') {
  echo json_encode($result);
} 
else {
  echo mysqli_affected_rows($con);
}

$con->close();
