<?php

declare(strict_types=1);

namespace TechBlog\Controller;

use TechBlog\Controller\TemplateController;
use TechBlog\Helpers;

/**
 * Classe responsável pelo controle dos end-points/rotas responsáveis pelo login/acesso do administrador por meio da biblioteca/dependência do SimpleRouter(router.php).
 */
class LoginAdmController extends TemplateController
{
    /**
     * Login
     * 
     * Verifica as credenciais de acesso e-mail e senha passados pelo usuário. Se forem iguais aos que aqui estão, é criado um dado de sessão que libera o acesso aos recursos disponíveis pelo administrador e em seguida é redirecionado para a página principal. Caso contrário, é redirecionado novamente para a página de login.
     * 
     * @return void
     */
    public function login(): void
    {

        if ($_POST) {
            if ($_POST["email"] == "adm@gmail.com" && $_POST["password"] == 'adm123') {

                $_SESSION['ADM_LOGED'] = true;

                Helpers::redirect();
            }else{
                Helpers::redirect('/Adm/Login');
            }
        } else {
            echo $this->template->renderTemplate('login.html', []);
        }
    }
    /**
     * Logout
     * 
     * Destroi o dado de sessão que libera acesso aos recursos de adm e a própria sessão, e em seguida redireciona para a página principal.
     * 
     * @return void
     */
    public function logout(): void
    {
        session_unset();
        session_destroy();
        Helpers::redirect();
    }
}
