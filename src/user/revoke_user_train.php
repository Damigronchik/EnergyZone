<?php
$id = $_POST['user_train_id'];
mysqli_query($link, "DELETE FROM user_trainings WHERE id = $id");
$_SESSION['is_train_delete'] = true;
header('Location: index.php?page=account');
?>