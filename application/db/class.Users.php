<?php

require_once 'application/db/settings.php';

class Users
{

    function get_all_users($db)
    {
        $query = 'SELECT * FROM users';

		$result = mysqli_query($db, $query);

		$massive = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$massive[] = $row;
		}

		return $massive;
    }

    function delete_user_by_id($db, $id)
	{
		$id = htmlspecialchars(mysqli_escape_string($db, $id));

		$query = "DELETE FROM `users` WHERE `id` = '{$id}'";

		mysqli_query($db, $query);
	}

    function edit_user_by_id($db, $id, $data)
	{
		$id = htmlspecialchars(mysqli_escape_string($db, $id));

        $name = htmlspecialchars(mysqli_escape_string($db, $data['name']));
        $surname = htmlspecialchars(mysqli_escape_string($db, $data['surname']));
        $phone = htmlspecialchars(mysqli_escape_string($db, $data['phone']));
        $country = htmlspecialchars(mysqli_escape_string($db, $data['country']));

		$query = "UPDATE `users` SET `name`='{$name}',`surname`='{$surname}',`phone`='{$phone}',`country`='{$country}' WHERE `id` = '{$id}' LIMIT 1";

		mysqli_query($db, $query);
	}

    function set_user_image_path_by_id($db, $id, $file_name){

        $id = htmlspecialchars(mysqli_escape_string($db, $id));
        $file_name = htmlspecialchars(mysqli_escape_string($db, $file_name));

        $query = "UPDATE `users` SET `image_url`='{$file_name}' WHERE `id` = '{$id}' LIMIT 1";

		mysqli_query($db, $query);
    }

    function add_new_user($db, $data)
	{
        $name = htmlspecialchars(mysqli_escape_string($db, $data['name']));
        $surname = htmlspecialchars(mysqli_escape_string($db, $data['surname']));
        $phone = htmlspecialchars(mysqli_escape_string($db, $data['phone']));
        $country = htmlspecialchars(mysqli_escape_string($db, $data['country']));

		$query = "INSERT INTO `users`(`name`, `surname`, `phone`, `country`) VALUES ('{$name}', '{$surname}', '{$phone}', '{$country}')";

		mysqli_query($db, $query);
	}
}
