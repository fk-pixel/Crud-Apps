<?php 
//action.php
$conn = new PDO("mysql:host=localhost;dbname=crud", "root", "123456");
$reeived_data = json_decode(file_get_contents("php://input"));
$data = array();

//All Fetch
if($received_data->action == 'getItems') {
    $query = "
        SELECT * FROM test
        ORDER BY id DESC    
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data);
}

//Add Data 
if($received_data->action == 'insert') {
    $data = array(
        ':first_name' => $received_data->firstName,
        ':last_name' => $received_data->lastName,
    );
    $query = "
        INSERT INTO crud
        (first_name, last_name)
        VALUES (:first_name, :last_name)
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute($data);
    $output = array('message' => 'Data Inserted!');
    echo json_encode($output);
}

//Single Fetch
if($received_data->action =='getItem') {
    $query = "
        SELECT * FROM crud
        WHERE id = '".$received_data->id."'
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->getItems();
    foreach($result as $row) {
        $data['id'] = $row['id'];
        $data['first_name'] = $row['first_name'];
        $data['last_name'] = $row['last_name'];
    }
    echo json_encode($data);
}

//Update
if($received_data->action == 'update') {
    $data = array(
        ':first_name' => $received_data->firstName,
        ':last_name' => $received_data->lastName,
        ':id' => $received_data->hiddenId,
    );
    $query = "
        UPDATE crud
        SET first_name = :first_name,
        last_name =:last_name
        WHERE id = :id
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $output = array('message'=> 'Data Updated!');
    echo json_encode($output);
}

//Delete
if($received_data->action == 'delete'){
    $query = "
        DELETE FROM crud
        WHERE id = '".$received_data->id."'
    ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $output = array('message'=> 'Data Deleted!');
    echo json_encode($output);
}

?>