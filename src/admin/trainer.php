<?php
$operation = $_POST['operation'] ?? null;
$name = $_POST['name'] ?? null;
$birthday = $_POST['birthday'] ?? null;
$biography = mysqli_real_escape_string($link, $_POST['biography']) ?? null;
$selected_programs = $_POST['training_programs'] ?? [];

switch ($operation) {
    case 'create':  
        $filename = uploadImage();    
          
        $create_query = "INSERT INTO trainers (name, photo, birthday, biography) 
            VALUES ('{$name}', '{$filename}', '{$birthday}', '{$biography}')";
        mysqli_query($link, $create_query);

        $trainer_id = mysqli_insert_id($link);
        foreach ($selected_programs as $training_name) {
            $create_trainer_program = "INSERT INTO trainer_programs(trainer_id, training_name)
                VALUES ($trainer_id, '$training_name')";
            mysqli_query($link, $create_trainer_program);
        }
        
        header('Location: index.php?page=admin/trainers-list');
        break;

    case 'update':
        $id = $_POST['trainer_id'];
        
        if ($_FILES['photo']['size'] > 0) {
            $old = mysqli_query($link, "SELECT photo FROM trainers WHERE id = {$id}");
            $old_photo = mysqli_fetch_row($old)[0];

            $full_path = 'D:\OSPanel\home\energyzone.local\public\assets\images\\' . $old_photo;
            if ($old_photo && file_exists($full_path)) {
                unlink($full_path);
            }

            $filename = uploadImage();
            $update_query = "UPDATE trainers SET
                name = '{$name}',
                photo =  '{$filename}',
                birthday = '{$birthday}',
                biography = '{$biography}'
                WHERE id = {$id}";
            mysqli_query($link, $update_query);
        } else {
            $update_query = "UPDATE trainers SET
                name = '{$name}',   
                birthday = '{$birthday}',
                biography = '{$biography}'
                WHERE id = {$id}";
            mysqli_query($link, $update_query);
        }

        mysqli_query($link, "DELETE FROM trainer_programs WHERE trainer_id = $id");
        foreach ($selected_programs as $training_name) {
            $create_trainer_program = "INSERT INTO trainer_programs(trainer_id, training_name)
                VALUES ($id, '$training_name')";
            mysqli_query($link, $create_trainer_program);
        }

        header('Location: index.php?page=admin/trainers-list');
        break;

    case 'delete':
        $id = $_POST['trainer_id'];
        $old = mysqli_query($link, "SELECT photo FROM trainers WHERE id = {$id}");
        $old_photo = mysqli_fetch_row($old)[0];

            $full_path = 'D:\OSPanel\home\energyzone.local\public\assets\images\\' . $old_photo;
            if ($old_photo && file_exists($full_path)) {
                unlink($full_path);
            }
        
        mysqli_query($link, "DELETE FROM trainers WHERE id = {$id}");

        header('Location: index.php?page=admin/trainers-list');
        break;

    default:
        header('Location: index.php?page=admin/trainers-list');
        break;
}

function uploadImage() {
    $file_tmp = $_FILES['photo']['tmp_name'];
    $file_name = $_FILES['photo']['name'];
    $extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_filename = time() . '_' . rand(1000, 9999) . '.' . $extension;
    $upload_path = __DIR__ . '/../../public/assets/images/' . $new_filename;
    move_uploaded_file($file_tmp, $upload_path);
    return $new_filename;
}
?>