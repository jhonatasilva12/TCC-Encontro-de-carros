<?php
session_start();
$conn = require_once('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loggedInUserId = $_SESSION['user_id'];

    // Recebendo os dados diretamente separados
    $newName = $_POST['nome_user'] ?? '';
    $newSobrenome = $_POST['sobrenome_user'] ?? '';
    $newBio = $_POST['bio_user'] ?? '';
    $newTelefone = $_POST['telefone_user'] ?? '';
    $newDataNasc = $_POST['data_nasc_user'] ?? null;
    $newCpf = $_POST['cpf_user'] ?? null;

    $newImagePath = null;

    // Validação dos campos obrigatórios
    if (empty($newName) || empty($newTelefone)) {
        header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=validation_error');
        exit();
    }

    // Validação de CPF (se fornecido)
    if (!empty($newCpf)) {
        $newCpf = preg_replace('/[^0-9]/', '', $newCpf);
        if (strlen($newCpf) != 11) {
            header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=error&message=CPF%20inválido.');
            exit();
        }
    }

    // Processamento do upload da imagem (mantido igual)
    if (isset($_FILES['img_user']) && $_FILES['img_user']['error'] === UPLOAD_ERR_OK) {
        $maxFileSize = 5 * 1024 * 1024;
        $uploadDir = '../assets/images/users/';
        

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $fileMimeType = mime_content_type($_FILES['img_user']['tmp_name']);

        if (!in_array($fileMimeType, $allowedMimeTypes)) {
            header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=error&message=Tipo%20de%20arquivo%20não%20permitido.%20Apenas%20JPG%2C%20PNG%2C%20GIF.');
            exit();
        }

        $extension = pathinfo($_FILES['img_user']['name'], PATHINFO_EXTENSION);
        $newImagePath = 'user_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $newImagePath;

        if (!move_uploaded_file($_FILES['img_user']['tmp_name'], $destination)) {
            header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=error&message=Erro%20ao%20fazer%20upload%20da%20imagem.');
            exit();
        }

        $stmtOldImage = $conn->prepare("SELECT img_user FROM tb_user WHERE id_user = ?");
        $stmtOldImage->execute([$loggedInUserId]);
        $oldImageData = $stmtOldImage->fetch(PDO::FETCH_ASSOC);

        if ($oldImageData && !empty($oldImageData['img_user'])) {
            $oldFilePath = $uploadDir . $oldImageData['img_user'];
            if (file_exists($oldFilePath) && $oldImageData['img_user'] !== 'user_padrao.jpg') {
                unlink($oldFilePath);
            }
        }
    }

    // Preparar e executar a consulta UPDATE
    try {
        $updateFields = [
            'nome_user = ?', 
            'sobrenome_user = ?', 
            'bio_user = ?', 
            'telefone_user = ?',
            'data_nasc_user = ?',
            'cpf_user = ?'
        ];
        
        $updateValues = [
            $newName, 
            $newSobrenome, 
            $newBio, 
            $newTelefone,
            $newDataNasc ? $newDataNasc : null,
            $newCpf ? $newCpf : null
        ];

        if ($newImagePath !== null) {
            $updateFields[] = 'img_user = ?';
            $updateValues[] = $newImagePath;
        }

        $query = "UPDATE tb_user SET " . implode(', ', $updateFields) . " WHERE id_user = ?";
        $updateValues[] = $loggedInUserId;

        $stmt = $conn->prepare($query);
        $stmt->execute($updateValues);

        if ($stmt->rowCount() > 0 || $newImagePath !== null) { 
            header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=success');
        } else {
            header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=no_changes');
        }
        exit();

    } catch(PDOException $e) {
        error_log("Erro ao atualizar perfil do usuário: " . $e->getMessage());
        if ($newImagePath && file_exists($destination)) {
            unlink($destination);
        }
        header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=error&message=Erro%20ao%20atualizar%20o%20perfil.');
        exit();
    }
} else {
    header('Location: ../sobre_user.php');
    exit();
}