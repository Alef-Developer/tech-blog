<?php

declare(strict_types=1);

namespace TechBlog\Controller;

use TechBlog\Helpers;
use TechBlog\Model\Article;

/**
 * Classe responsável pelo controle dos end-points/rotas acessíveis pelo administrador por meio da biblioteca/dependência do SimpleRouter(router.php).
 */
class AdministratorController extends TemplateController
{
    /**
     * Caso o usuário tente acessar uma rota do administrador sem estar logado, é redirecionado para a página de login.
     */
    public function __construct()
    {
        parent::__construct();
        
        if (!ADM) {
            Helpers::redirect('/Adm/Login');
        }
    }
    /**
     * Editor de Artigos
     * 
     * Exibe um formulário com os campos pré-preenchidos com os valores originais do artigo a ser editado, e em caso de envio da alteração, o artigo selecionado é modificado.
     * 
     * @param int $id Índice do artigo selecionado.
     * @return void
     */
    public function editArticle(int $id): void
    {

        $categories = (new Article())->searchArticle()->getArticle();

        $article = (new Article())->searchArticle()->getArticle()[$id];

        if ($_POST) {

            $new_article = clone $article;

            $new_article->title = $_POST['title'];
            $new_article->content = $_POST['content'];
            $new_article->category = $_POST['category'];

            if ($_FILES && str_contains($_FILES['userimage']['type'], 'image')) {

                $updated_article = (new Article())->updateArticle($id, $new_article, $_FILES['userimage']);
            } else {
                $updated_article = (new Article())->updateArticle($id, $new_article);
            }

            if ($updated_article) Helpers::redirect();
        } else {
            echo $this->template->renderTemplate('articleForm.html', ['articles' => $categories, 'article' => $article, 'id_article' => $id]);
        }
    }
    /**
     * Criador de Artigos
     * 
     * Exibe um formulário com os campos vazios para que, após o preenchimento(ou não), o artigo seja criado. 
     * 
     * @return void
     */
    public function createArticle(): void
    {

        $categories = (new Article())->searchArticle()?->getArticle();

        if ($_POST) {

            if ($_FILES && str_contains($_FILES['userimage']['type'], 'image')) {

                $article = (new Article())->createArticle($_POST['title'], $_POST['content'], $_POST['category'], $_FILES['userimage']);
            } else {
                $article = (new Article())->createArticle($_POST['title'], $_POST['content'], $_POST['category']);
            }

            if ($article) Helpers::redirect();
        } else {
            echo $this->template->renderTemplate('articleForm.html', ['articles' => $categories]);
        }
    }
    /**
     * Deletador de Artigos
     * 
     * Exclui artigos selecionados.
     * 
     * @param int $id Índice do artigo a ser deletado.
     * @return void
     */
    public function deleteArticle(int $id): void
    {
        $delete = (new Article())->deleteArticle($id);

        if ($delete) {
            Helpers::redirect();
        }
    }
}
