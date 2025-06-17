<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./assets/images/logo.png">
    <title>MeetCar</title>
    <link rel="stylesheet" href="./assets/css/cadastro.css">
</head>
<body>

    <?php
    if (isset($_GET['credenciais']) && $_GET['credenciais'] == 'invalidas') {
        echo "<div class='alert-error'>credenciais invalidas</div>";
    }
    ?>


    <div class="container">

        <div class="form-image">
            <img src="./assets/images/logo_att.png" alt="">
        </div>

        <div class="form">

            <div class="form-header">
                <div class="title">
                    <h1>Login</h1>
                </div>
                <div class="title">
                  <h4>Não possui uma conta?</h4>
                  <button class="login-button"><a href="cadastro.html">cadastrar</a></button>
                </div>
            </div>

            <form action="banco/login.php" method="post">

                <div class="input-group-login">


                    <div class="input-box">
                        <label for="email">Email do Usuário</label>
                        <input type="email" id="email" name="emailo" placeholder="Insira seu email..." required>
                    </div>
                            
                    <div class="input-box">
                        <label for="password">Senha</label>
                        <input type="password" id="password" name="password" placeholder="Insira sua senha..." required>
                    </div>

                </div>

                <button type="submit" class="continue-button">Entrar</button>

            </form>

        </div>

    </div>

</body>
</html>
