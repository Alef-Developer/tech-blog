<?php

declare(strict_types=1);

namespace TechBlog\Controller;

use TechBlog\Helpers;
use TechBlog\Model\Article;

/**
 * Classe responsável pelo controle dos end-points/rotas acessíveis pelo usuário comum através da biblioteca/dependência do SimpleRouter(router.php).
 */
class VisitorController extends TemplateController
{
    /**
     * Index
     * 
     * Exibe o conteúdo da página inicial.
     * 
     * @return void
     */
    public function index(): void
    {
        $articles = (new Article())->searchArticle()?->getArticle();

        echo $this->template->renderTemplate('index.html', ['articles' => $articles]);
    }
    /**
     * Visualizador de Artigos
     * 
     * Exibe artigo selecionado.
     * 
     * @param int $id Índice do artigo a ser aberto.
     * @return void
     */
    public function viewArticle(int $id): void
    {
        $articles = (new Article())->searchArticle()->getArticle();

        echo $this->template->renderTemplate('viewArticle.html', ['articles' => $articles, 'article' => $articles[$id]]);
    }
    /**
     * Visualizador de Artigos por Título
     * 
     * Exibe artigo selecionado após a busca por título.
     * 
     * @return void
     */
    public function viewArticlesByTitle()
    {
        if ($_GET) {

            $categories = (new Article())->searchArticle()?->getArticle();

            $articles = (new Article())->searchArticle()?->getArticleByTitle($_GET['Title']);

            echo $this->template->renderTemplate('articlesByTitle.html', ['articles' => $categories, 'article' => $articles]);
        }else{
            Helpers::redirect();
        }
    }
    /**
     * Visualizador de Artigos por Categoria
     * 
     * Exibe artigo selecionado após a seleção da categoria.
     * 
     * @param string $category Categoria escolhida no dropdown.
     * @return void
     */
    public function viewArticlesByCategory(string $category)
    {
        $categories = (new Article())->searchArticle()->getArticle();

        $articles = (new Article())->searchArticle()->getArticleByCategory($category);

        echo $this->template->renderTemplate('articlesByCategory.html', ['articles' => $categories, 'article' => $articles]);
    }
}
