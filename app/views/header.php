<?php
$BASE_URL = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']. '?') . '/';
?>
<header class="menu-superior">
    <div class="container-menu">
        <h1 class="logo">Catálogo de Livros</h1>
        <div class="menu-usuario">
            <img src="<?php echo $BASE_URL ?>assets/icons/usuario.png" alt="Ícone de Usuário" id="icone-usuario" class="icone-usuario">
            <div id="menu-suspenso" class="menu-suspenso oculto">
                <button onclick="listarLivros()">Lista de Livros</button>
                <button onclick="cadastrarLivros()">Cadastrar Novo Livro</button>
                <button onclick="usuario()">Usuário</button>
                <button onclick="sair()">Sair</button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const iconeUsuario = document.getElementById("icone-usuario");
            const menuSuspenso = document.getElementById("menu-suspenso");

            iconeUsuario.addEventListener("click", function () {
                menuSuspenso.classList.toggle("visivel");
            });

            // Fechar o menu se clicar fora dele
            document.addEventListener("click", function (event) {
                if (!iconeUsuario.contains(event.target) && !menuSuspenso.contains(event.target)) {
                    menuSuspenso.classList.remove("visivel");
                }
            });
        });

        function usuario() {
            // Redirecione para a página de edição
            window.location.href = 'index.php?action=usuario';
        }

        function sair() {
            // Finalizar a sessão e redirecionar para o login
            window.location.href = 'index.php?action=sair';
        }

        function listarLivros() {
            // Finalizar a sessão e redirecionar para o login
            window.location.href = 'index.php?action=listar';
        }

        function cadastrarLivros() {
            // Finalizar a sessão e redirecionar para o login
            window.location.href = 'index.php?action=form-cadastrar';
        }
    </script>
</header>
