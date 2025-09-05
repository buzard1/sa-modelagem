<?php 
session_start(); // Inicia a sessão para acessar as variáveis de sessão
require_once 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// VERIFICA SE O USUARIO TEM PERMISSAO
// Se não existir um cargo na sessão OU se o cargo não for Gerente, Atendente ou Técnico
if (!isset($_SESSION['cargo']) || ($_SESSION['cargo'] != "Gerente" && $_SESSION['cargo'] != "Atendente" && $_SESSION['cargo'] != "Tecnico")) {
    echo "Acesso Negado!"; // Mostra mensagem de acesso negado
    header("Location: index.php"); // Redireciona para o login
    exit(); // Encerra o script
}

// Definição dos menus para cada cargo
$menus = [
    'Gerente' => [ // Opções de menu para o cargo Gerente
        ['href' => 'dashboard.php', 'icon' => '👤', 'text' => 'Perfil'],
        ['href' => 'cadastro-cliente.php', 'icon' => '📋', 'text' => 'Cadastro Cliente'],
        ['href' => 'cadastro-ordem_serv.php', 'icon' => '🛠️', 'text' => 'Cadastro Ordem de Serviço'],
        ['href' => 'ordem_serv.php', 'icon' => '💼', 'text' => 'Ordem de serviço'],
        ['href' => 'relatorio.php', 'icon' => '📊', 'text' => 'Relatórios'],
        ['href' => 'estoque.php', 'icon' => '📦', 'text' => 'Estoque'],
        ['href' => 'usuarios.php', 'icon' => '👥', 'text' => 'Usuários'],
        ['href' => 'fornecedor.php', 'icon' => '🔗', 'text' => 'Fornecedores'],
        ['href' => 'suporte.php', 'icon' => '🆘', 'text' => 'Suporte'],
        ['href' => 'logout.php', 'icon' => '🚪', 'text' => 'Sair']
    ],
    'Atendente' => [ // Opções de menu para o cargo Atendente
        ['href' => 'dashboard.php', 'icon' => '👤', 'text' => 'Perfil'],
        ['href' => 'cadastro-cliente.php', 'icon' => '📋', 'text' => 'Cadastro Cliente'],
        ['href' => 'cadastro-ordem_serv.php', 'icon' => '🛠️', 'text' => 'Cadastro Ordem de Serviço'],
        ['href' => 'ordem_serv.php', 'icon' => '💼', 'text' => 'Ordem de serviço'],
        ['href' => 'estoque.php', 'icon' => '📦', 'text' => 'Estoque'],
        ['href' => 'fornecedor.php', 'icon' => '🔗', 'text' => 'Fornecedores'],
        ['href' => 'suporte.php', 'icon' => '🆘', 'text' => 'Suporte'],
        ['href' => 'logout.php', 'icon' => '🚪', 'text' => 'Sair']
    ],
    'Tecnico' => [ // Opções de menu para o cargo Técnico
        ['href' => 'dashboard.php', 'icon' => '👤', 'text' => 'Perfil'],
        ['href' => 'ordem_serv.php', 'icon' => '💼', 'text' => 'Ordem de serviço'],
        ['href' => 'suporte.php', 'icon' => '🆘', 'text' => 'Suporte'],
        ['href' => 'logout.php', 'icon' => '🚪', 'text' => 'Sair']
    ],
];

// Obter o menu correspondente ao cargo do usuário
// Verifica se o cargo está setado e existe no array $menus
$menuItems = isset($_SESSION['cargo']) && isset($menus[$_SESSION['cargo']]) ? $menus[$_SESSION['cargo']] : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Perfil do Usuário</title>
    <link rel="stylesheet" href="css/sidebar.css" /> <!-- CSS da sidebar -->
    <link rel="stylesheet" href="css/dashboard.css" /> <!-- CSS do dashboard -->
    <link rel="icon" href="img/logo.png" type="image/png"> <!-- Ícone da aba -->

    <!-- Biblioteca Cropper.js para recorte/redimensionamento de imagem -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css"/>
</head>
<body>
    <!-- MENU LATERAL -->
    <nav class="sidebar">
        <div class="logo">
            <img src="img/logo.png" alt="Logo do sistema"> <!-- Logo do sistema -->
        </div>
        <ul class="menu">
            <?php foreach ($menuItems as $item): ?> <!-- Gera o menu de acordo com o cargo -->
                <li><a href="<?php echo $item['href']; ?>"><?php echo $item['icon']; ?> <span><?php echo $item['text']; ?></span></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="content">
        <div class="profile-card">
            <h2>Perfil do Usuário</h2>
            <div class="profile-container">
                <!-- Foto de perfil -->
                <div class="profile-photo-box">
                    <img id="profile-photo" class="profile-photo" src="img/default-user.png" alt="Foto de perfil">
                    <br />
                    <input type="file" id="photo-input" accept="image/*" style="display: none;" /> <!-- Input escondido para upload -->
                    <button class="change-photo-btn" onclick="document.getElementById('photo-input').click()">Alterar Foto</button>
                    
                    <!-- Editor de redimensionamento manual -->
                    <div id="editor-container" style="display:none; margin-top:10px;">
                        <div style="position:relative; width:300px; height:300px; border:1px solid #ccc;">
                            <img id="cropper-image" style="max-width:100%; display:block;">
                        </div>
                        <br>
                        <button class="change-photo-btn" id="apply-crop-btn">Aplicar Foto</button>
                        <button class="change-photo-btn" id="cancel-crop-btn">Cancelar</button>
                    </div>
                </div>

                <!-- Informações do usuário -->
                <div class="profile-info">
                    <p><strong>Nome:</strong> <span><?php echo htmlspecialchars($_SESSION['nome']); ?></span></p>
                    <div class="email-container">
                        <p><strong>Email:</strong> 
                            <span id="email" class="email-display"><?php echo htmlspecialchars($_SESSION['email']); ?></span>
                            <input type="email" id="email-input" class="email-input" style="display: none;">
                            <button id="edit-email-btn" class="edit-btn">✏️</button>
                            <button id="save-email-btn" class="save-btn" style="display: none;">Salvar</button>
                            <button id="cancel-email-btn" class="cancel-btn" style="display: none;">Cancelar</button>
                        </p>
                    </div>
                    <p><strong>Cargo:</strong> <span><?php echo htmlspecialchars($_SESSION['cargo']); ?></span></p>
                    <p><strong>Último login:</strong> <span id="ultimo-login"></span></p>
                    <div class="change-password">
                        <button onclick="window.location.href='redefinir-senha.php'" class="change-password-btn">Alterar Senha</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Script da biblioteca Cropper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
    // ----------------------------
    // SEÇÃO DE FOTO DE PERFIL
    // ----------------------------
    const profilePhoto = document.getElementById('profile-photo');
    const photoInput = document.getElementById('photo-input');
    const editorContainer = document.getElementById('editor-container');
    const cropperImage = document.getElementById('cropper-image');
    const applyCropBtn = document.getElementById('apply-crop-btn');
    const cancelCropBtn = document.getElementById('cancel-crop-btn');
    let cropper;

    // Carrega foto do localStorage se existir
    const storedPhoto = localStorage.getItem('fotoPerfil');
    if (storedPhoto) { profilePhoto.src = storedPhoto; }

    // Quando o usuário seleciona uma imagem
    photoInput.addEventListener('change', function() {
        const file = this.files[0]; // Pega o arquivo selecionado
        if (file && file.type.startsWith('image/')) { // Se for imagem
            const reader = new FileReader();
            reader.onload = function(e) {
                cropperImage.src = e.target.result; // Mostra imagem no editor
                editorContainer.style.display = 'block'; // Exibe editor

                if(cropper) cropper.destroy(); // Se já tiver cropper, destroi
                cropper = new Cropper(cropperImage, {
                    aspectRatio: 1, // Mantém proporção quadrada
                    viewMode: 1,
                    movable: true,
                    zoomable: true,
                    rotatable: false,
                    scalable: false,
                });
            };
            reader.readAsDataURL(file); // Lê imagem como base64
        }
    });

    // Botão aplicar corte
    applyCropBtn.addEventListener('click', function() {
        const canvas = cropper.getCroppedCanvas({ width: 200, height: 200, imageSmoothingQuality: 'high' });
        const croppedData = canvas.toDataURL('image/png');
        profilePhoto.src = croppedData; // Mostra foto cortada
        localStorage.setItem('fotoPerfil', croppedData); // Salva no localStorage
        editorContainer.style.display = 'none'; // Fecha editor
    });

    // Botão cancelar corte
    cancelCropBtn.addEventListener('click', function() {
        editorContainer.style.display = 'none';
        if(cropper) cropper.destroy(); // Destroi instância do cropper
    });

    // ----------------------------
    // SEÇÃO DE EMAIL EDITÁVEL
    // ----------------------------
    const emailDisplay = document.getElementById('email');
    const emailInput = document.getElementById('email-input');
    emailInput.value = emailDisplay.textContent;

    const editEmailBtn = document.getElementById('edit-email-btn');
    const saveEmailBtn = document.getElementById('save-email-btn');
    const cancelEmailBtn = document.getElementById('cancel-email-btn');

    // Editar email
    editEmailBtn.addEventListener('click', function() {
        emailDisplay.style.display = 'none';
        emailInput.style.display = 'inline-block';
        editEmailBtn.style.display = 'none';
        saveEmailBtn.style.display = 'inline-block';
        cancelEmailBtn.style.display = 'inline-block';
        emailInput.focus();
    });

    // Cancelar edição
    cancelEmailBtn.addEventListener('click', function() {
        emailInput.value = emailDisplay.textContent;
        emailDisplay.style.display = 'inline';
        emailInput.style.display = 'none';
        editEmailBtn.style.display = 'inline-block';
        saveEmailBtn.style.display = 'none';
        cancelEmailBtn.style.display = 'none';
    });

    // Salvar email
    saveEmailBtn.addEventListener('click', function() {
        const newEmail = emailInput.value.trim();
        if (newEmail && newEmail.includes('@')) { // Validação simples
            emailDisplay.textContent = newEmail;
            localStorage.setItem('emailFuncionario', newEmail); // Salva localmente
            emailDisplay.style.display = 'inline';
            emailInput.style.display = 'none';
            editEmailBtn.style.display = 'inline-block';
            saveEmailBtn.style.display = 'none';
            cancelEmailBtn.style.display = 'none';
        } else {
            alert('Por favor, insira um email válido');
        }
    });

    // ----------------------------
    // SEÇÃO DE ÚLTIMO LOGIN
    // ----------------------------
    document.getElementById('ultimo-login').textContent = localStorage.getItem('ultimoLogin') || new Date().toLocaleString();

    // ----------------------------
    // MARCA MENU ATUAL NA SIDEBAR
    // ----------------------------
    const links = document.querySelectorAll('.sidebar .menu li a');
    const currentPage = window.location.pathname.split('/').pop(); // Pega o nome da página atual
    links.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.classList.add('active'); // Adiciona destaque
        }
    });
    </script>
</body>
</html>
