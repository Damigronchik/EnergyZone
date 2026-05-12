<?php
$operation = $_POST['operation'] ?? null;
$name = $_POST['title'] ?? null;
$price = $_POST['price'] ?? null;
$description = $_POST['description'] ?? null;
$selected_programs = $_POST['training_programs'] ?? [];

switch ($operation) {
    case 'create':            
        $create_query = "INSERT INTO subscriptions (name, price, description) 
            VALUES ('{$name}', '{$price}', '{$description}')";
        mysqli_query($link, $create_query);

        $subscription_id = mysqli_insert_id($link);
        foreach ($selected_programs as $training_name) {
            $create_sub_program = "INSERT INTO subscription_programs(subscription_id, training_name)
                VALUES ($subscription_id, '$training_name')";
            mysqli_query($link, $create_sub_program);
        }
        
        header('Location: index.php?page=admin/subscriptions');
        break;

    case 'update':
        $id = $_POST['subscription_id'];
        
        $update_query = "UPDATE subscriptions SET
            name = '{$name}',   
            price = '{$price}',
            description = '{$description}'
            WHERE id = $id";
        mysqli_query($link, $update_query);

        mysqli_query($link, "DELETE FROM subscription_programs WHERE subscription_id = $id");
        foreach ($selected_programs as $training_name) {
            $create_sub_program = "INSERT INTO subscription_programs(subscription_id, training_name)
                VALUES ($id, '$training_name')";
            mysqli_query($link, $create_sub_program);
        }

        header('Location: index.php?page=admin/subscriptions');
        break;

    case 'delete':
        $id = $_POST['subscription_id'];
        mysqli_query($link, "DELETE FROM subscriptions WHERE id = {$id}");

        header('Location: index.php?page=admin/subscriptions');
        break;

    default:
        header('Location: index.php?page=admin/subscriptions');
        break;
}
?>