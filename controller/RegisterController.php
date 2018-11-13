<?php

namespace controller;

class RegisterController extends Controller {
    public function actionIndex() {
        $errors = [];
        $this->title = "Register";
        $user_model = new \model\User();

        if ($this->isPost()) {
            $agree = isset($_POST['agree-term']) ? $_POST['agree-term'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $name = isset($_POST['name']) ? $_POST['name'] : null;
            $pass = isset($_POST['pass']) ? $_POST['pass'] : null;
            $re_pass = isset($_POST['re_pass']) ? $_POST['re_pass'] : null;

            if ($agree != 'on') {
                $errors['agree-term'] = 'Pleas Accept Term and Condition';
            }

            if (!$name) {
                $errors["name"] = "Name is required";
            }elseif (!preg_match("/^[a-zA-Z]{3,10}/", htmlspecialchars($name))) {
                $errors["name"] = "Only letters and white space allowed";
            }

            if (!$email) {
                $errors["email"] = "Email is required";
            }elseif (!preg_match("/^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", htmlspecialchars($email))) {
                $errors["email"] = "Invalid email format";
            }


            if (!$pass) {
                $errors["pass"] = "Password is required";
            }elseif(!preg_match("/^[A-Za-z\d]{6,}$/", $pass)) {
                $errors["pass"] = "Invalid password format";
            }

            if($re_pass !== $pass) {
                $errors['re_pass'] = "Passwords do not match";
            }



            if (empty($errors)) {
                $user_id = $this->model->createUser($email, $name, $pass);
                if ($user_id) {
                    $this->redirect('login');
                } else {
                    var_dump($this->model->getErrorMessage());
                }
            }
        }

        $this->render('main', [
            'errors' => $errors
        ]);
    }
}