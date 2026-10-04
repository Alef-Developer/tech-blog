<?php

// Obtém o caminho completo até o diretório raiz do projeto
$url_normalization = function(){
    $url = str_replace('\\', '/', __DIR__);
    return str_replace($_SERVER['DOCUMENT_ROOT'], '', $url);
};

// Nome do Site/Projeto
define('WEBSITE', 'Tech Blog');
// Constante responsável por armazenar o caminho padrão onde os visitantes/usuários em geral podem acessar.
define('SLUG', $url_normalization());
// Constante responsável por armazenar o caminho padrão onde apenas o Administrador pode acessar.
define('SLUGADM', SLUG . '/Adm');
// Constante responsável por verificar se o Administrador está logado. Se estiver logado, é concedido acesso a manipulação dos artigos nos templates. 
define('ADM', isset($_SESSION['ADM_LOGED']) && $_SESSION['ADM_LOGED'] === true);