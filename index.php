<?php

require_once 'application/db/class.Users.php';

$USERS = new Users;

$target_dir = "user_images/";

if (isset($_POST)) {
    if (array_key_exists('act', $_POST)) {

        if ($_POST['act'] == 'addNew') {
            $USERS->add_new_user($db, $_POST);

            if (array_key_exists('user_photo', $_FILES) && ($_FILES['user_photo']['name'] != "")) {
                $path = pathinfo($_FILES['user_photo']['name']);
                $filename = $path['filename'];
                $path_filename_ext = $target_dir . $_POST['new_id'] . "." . $path['extension'];

                move_uploaded_file($_FILES['user_photo']['tmp_name'], $path_filename_ext);
                $USERS->set_user_image_path_by_id($db, $_POST['new_id'], $_POST['new_id'] . "." . $path['extension']);
            }
        }

        if ($_POST['act'] == 'edit') {
            if (array_key_exists('user_photo_edit', $_FILES) && ($_FILES['user_photo_edit']['name'] != "")) {
                $path = pathinfo($_FILES['user_photo_edit']['name']);
                $filename = $path['filename'];
                $path_filename_ext = $target_dir . $_POST['id'] . "." . $path['extension'];

                move_uploaded_file($_FILES['user_photo_edit']['tmp_name'], $path_filename_ext);
                $USERS->set_user_image_path_by_id($db, $_POST['id'], $_POST['id'] . "." . $path['extension']);
            }

            $USERS->edit_user_by_id($db, $_POST['id'], $_POST);
        }

        if ($_POST['act'] == 'delete') {
            print_r($USERS->delete_user_by_id($db, $_POST['id']));
        }
    }
}

$users = $USERS->get_all_users($db);

?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Dashboard 3303</title>
    <!-- Bootstrap core CSS -->
    <link href="../../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles -->
    <link href="../../assets/css/dashboard.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script type="text/javascript" charset="utf8" src="../../assets/js/jquery.dataTables.js"></script>

    <style>
        .user-image { 
            width: 64px;
            height: 64px;
            object-fit: cover;
            border-radius: 50%; }
    </style>
</head>

<body>
    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Редактирование #-1</h5>
                </div>
                <form method="POST" id="deleteForm">
                    <input type="hidden" name="act" value="delete">
                    <input type="hidden" name="id" id="id_delete" value="-1">
                </form>
                <form method="POST" id="editForm" enctype="multipart/form-data">
                    <input type="hidden" name="act" value="edit">
                    <input type="hidden" name="id" id="id_edit" value="-1">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Имя</label>
                            <input type="text" class="form-control" id="editInputName" name='name'>
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Фамилия</label>
                            <input type="text" class="form-control" id="editInputSurname" name='surname'>
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Телефон</label>
                            <input type="text" class="form-control" id="editInputPhone" placeholder="+38 (___) ___ __ __" name='phone'>
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Город</label>
                            <input type="text" class="form-control" id="editInputCountry" name='country'>
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Фото</label>
                            <input name="user_photo_edit" type="file" accept="image/*" />
                        </div>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="editModal_Delete();">Удалить</button>
                        <button type="button" class="btn btn-primary" onclick="editModal_Save()">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Create New User -->
    <div class="modal fade" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="addNewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewModalLabel">Добавление пользователя</h5>
                </div>
                <form method="POST" id="addNewForm" enctype="multipart/form-data">
                    <input type="hidden" name="act" value="addNew">
                    <input type="hidden" name="new_id" value="<?php if (empty($users)) {
                                                                    echo 1;
                                                                } else {
                                                                    echo end($users)['id'] + 1;
                                                                } ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Имя</label>
                            <input type="text" class="form-control" id="addNewInputName" name='name'>
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Фамилия</label>
                            <input type="text" class="form-control" id="addNewInputSurname" name='surname'>
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Телефон</label>
                            <input type="text" class="form-control" id="addNewInputPhone" placeholder="+38 (___) ___ __ __" name='phone' value="+38 (0">
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Город</label>
                            <input type="text" class="form-control" id="addNewInputCountry" name='country'>
                        </div>
                        <br>
                        <div class="form-group">
                            <label>Фото</label>
                            <input name="user_photo" type="file" accept="image/*" />
                        </div>
                        <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('addNewForm').submit();">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MAIN -->
    <div class="container-fluid">
        <div class="row">
            <main class="col-md-12 ">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Пользователи</h1>
                    <button data-toggle="modal" data-target="#addNewModal" style="position: relative;margin-left: auto;margin-right: auto; left: 40%;display: block;padding: 10px;" type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#editModal">Добавить пользователя</button>
                </div>

                <table id="table_id" class="display">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Имя</th>
                            <th>Фамилия</th>
                            <th>Телефон</th>
                            <th>Город</th>
                            <th>Картинка</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $i = 0;

                        foreach ($users as $user) {
                            $img = "";

                            if ($user['image_url'] != null && $user['image_url'] != "") {
                                if (file_exists($target_dir . $user['image_url'])) {
                                    $img = '<a href= "' . $target_dir . $user['image_url'] . '" target="_blank"><img class="user-image" src="' . $target_dir . $user['image_url'] . '"></a>';
                                }
                            }

                            echo '<tr>
                                <td>' . $user['id'] . '</td>
                                <td>' . $user['name'] . '</td>
                                <td>' . $user['surname'] . '</td>
                                <td>' . $user['phone'] . '</td>
                                <td>' . $user['country'] . '</td>
                                <td>' . $img  . '</td>
                                <td><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" onclick="editModal_Open(' . $user['id'] . ','.$i.')" data-target="#editModal">Редактировать</button></td>
                            </tr>';
                            
                            $i++;
                        }

                        ?>

                    </tbody>
                </table>
            </main>
        </div>
    </div>

    <script>
        var usersArray = <?= json_encode($users); ?>;

        var temp_edit_id = -1;

        $("#editInputPhone").mask("+38 (099) 999-99-99");
        $("#addNewInputPhone").mask("+38 (099) 999-99-99");

        $(document).ready(function() {
            $('#table_id').DataTable();
        });

        function editModal_Open(a, i) {
            document.getElementById('id_edit').value = a;
            document.getElementById('id_delete').value = a;
            document.getElementById('editModalLabel').innerText = 'Редактирование #' + a;
            temp_edit_id = a;

            document.getElementById('editInputName').value = usersArray[i].name;
            document.getElementById('editInputSurname').value = usersArray[i].surname;
            document.getElementById('editInputPhone').value = usersArray[i].phone;
            document.getElementById('editInputCountry').value = usersArray[i].country;

            $("#exampleModal").modal('show');
        }

        function editModal_Save() {
            document.getElementById("editForm").submit();
        }

        function editModal_Delete() {
            document.getElementById("deleteForm").submit();
        }
    </script>

    <script src="../../assets/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js" integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha" crossorigin="anonymous"></script>
</body>

</html>