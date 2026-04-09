<?php
$operation = $_POST['operation'] ?? null;
$new_name = $_POST['title'] ?? null;
$descr =  mysqli_real_escape_string($link, $_POST['description']) ?? null;

switch ($operation) {
    case 'create':  
        $card_image = uploadImage('card_image', 'tr_card');
        $header_image = uploadImage('header_image', 'tr_header');
          
        $create_query = "INSERT INTO training_programs (name, image, header_image, description) 
            VALUES ('{$new_name}', '{$card_image}', '{$header_image}', '{$descr}')";
        mysqli_query($link, $create_query);
        
        header('Location: index.php?page=admin/programs-list');
        break;

    case 'update':
        $old_name = $_POST['old_title'];
        $old_images = mysqli_query($link, "SELECT image, header_image FROM training_programs WHERE name = '$old_name'");
        $old_data = mysqli_fetch_assoc($old_images);
        $old_card_image = $old_data['image'];
        $old_header_image = $old_data['header_image'];

        if ($_FILES['card_image']['size'] > 0) {
            deleteImage($old_card_image);
            $card_image = uploadImage('card_image', 'tr_card');
        } else { $card_image = $old_card_image; }

        if ($_FILES['header_image']['size'] > 0) {
            deleteImage($old_header_image);
            $header_image = uploadImage('header_image', 'tr_header');
        }  else { $header_image = $old_header_image; }

        $update_query = "UPDATE training_programs SET
            name = '{$new_name}',
            image =  '{$card_image}',
            header_image = '{$header_image}',
            description = '{$descr}'
            WHERE name = '{$old_name}'";
        mysqli_query($link, $update_query);
        
        header('Location: index.php?page=admin/programs-list');
        break;

    case 'delete':
        $old_name = $_POST['old_title'];
        $old_images = mysqli_query($link, "SELECT image, header_image FROM training_programs WHERE name = '$old_name'");
        $old_data = mysqli_fetch_assoc($old_images);
        $old_card_image = $old_data['image'];
        $old_header_image = $old_data['header_image'];
        deleteImage($old_card_image);
        deleteImage($old_header_image);
        
        mysqli_query($link, "DELETE FROM training_programs WHERE name = '$old_name'");

        header('Location: index.php?page=admin/programs-list');
        break;

    default:
        header('Location: index.php?page=admin/programs-list');
        break;
}

function uploadImage($input_name, $prefix) {
    $file_tmp = $_FILES[$input_name]['tmp_name'];
    $file_name = $_FILES[$input_name]['name'];
    $extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $new_filename = $prefix . time() . '_' . rand(1000, 9999) . '.' . $extension;
    $upload_path = __DIR__ . '/../../public/assets/images/' . $new_filename;
    move_uploaded_file($file_tmp, $upload_path);
    return $new_filename;
}

function deleteImage($filename) {
    $delete_path = __DIR__ . '/../../public/assets/images/' . $filename;
    if ($filename && file_exists($delete_path)) {
        unlink($delete_path);
    }
}
?>