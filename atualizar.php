<?php
session_start();
$conn = require_once('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loggedInUserId = $_SESSION['user_id'];

    $newNomeCompleto = $_POST['nome_completo_user'] ?? '';
    $newBio = $_POST['bio_user'] ?? '';
    $newTelefone = $_POST['telefone_user'] ?? '';

    $newImagePath = null;

    // --- 1. Validação e separação do Nome Completo ---
    if (empty($newNomeCompleto) || empty($newTelefone)) {
        header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=validation_error'); // Inclui ID na URL
        exit();
    }

    $nameParts = explode(' ', trim($newNomeCompleto));
    $newName = '';
    $newSobrenome = '';

    if (count($nameParts) > 1) {
        $newSobrenome = array_pop($nameParts);
        $newName = implode(' ', $nameParts);
    } else {
        $newName = $newNomeCompleto;
        $newSobrenome = '';
    }
    
    if (empty($newName) && empty($newSobrenome) && !empty($newNomeCompleto)) {
        $newName = $newNomeCompleto;
        $newSobrenome = '';
    } elseif (empty($newSobrenome) && count($nameParts) > 0 && !empty($newNomeCompleto)) {
        $newSobrenome = array_pop($nameParts);
        $newName = implode(' ', $nameParts);
    }
    if (empty($newSobrenome) && strlen($newNomeCompleto) > 0 && strpos($newNomeCompleto, ' ') === false) {
        $newSobrenome = '';
    }

    // --- o upload da imagem de perfil ---
    if (isset($_FILES['img_user']) && $_FILES['img_user']['error'] === UPLOAD_ERR_OK) {
        $maxFileSize = 5 * 1024 * 1024;
        $uploadDir = '../assets/images/users/';
        
        if ($_FILES['img_user']['size'] > $maxFileSize) {
            header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=error&message=Tamanho%20da%20imagem%20excede%20o%20limite%20de%205MB.'); // Inclui ID
            exit();
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
        $fileMimeType = mime_content_type($_FILES['img_user']['tmp_name']);

        if (!in_array($fileMimeType, $allowedMimeTypes)) {
            header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=error&message=Tipo%20de%20arquivo%20não%20permitido.%20Apenas%20JPG%2C%20PNG%2C%20GIF.'); // Inclui ID
            exit();
        }

        $extension = pathinfo($_FILES['img_user']['name'], PATHINFO_EXTENSION);
        $newImagePath = 'user_' . uniqid() . '.' . $extension;
        $destination = $uploadDir . $newImagePath;

        if (!move_uploaded_file($_FILES['img_user']['tmp_name'], $destination)) {
            header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=error&message=Erro%20ao%20fazer%20upload%20da%20imagem.'); // Inclui ID
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

    // ---  Preparar e executar a consulta UPDATE ---
    try {
        $updateFields = ['nome_user = ?', 'sobrenome_user = ?', 'bio_user = ?', 'telefone_user = ?'];
        $updateValues = [$newName, $newSobrenome, $newBio, $newTelefone];

        if ($newImagePath !== null) {
            $updateFields[] = 'img_user = ?';
            $updateValues[] = $newImagePath;
        }

        $query = "UPDATE tb_user SET " . implode(', ', $updateFields) . " WHERE id_user = ?";
        $updateValues[] = $loggedInUserId;

        $stmt = $conn->prepare($query);
        $stmt->execute($updateValues);

        if ($stmt->rowCount() > 0 || $newImagePath !== null) { 
            header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=success'); // Inclui ID
        } else {
            header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=no_changes'); // Inclui ID
        }
        exit();

    } catch(PDOException $e) {
        error_log("Erro ao atualizar perfil do usuário: " . $e->getMessage());
        if ($newImagePath && file_exists($destination)) {
            unlink($destination);
        }
        header('Location: ../sobre_user.php?id=' . $loggedInUserId . '&status=error&message=Erro%20ao%20atualizar%20o%20perfil.'); // Inclui ID
        exit();
    }
} else {
    header('Location: ../sobre_user.php');
    exit();
}
