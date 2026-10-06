<?php

namespace TechBlog\Model;

use DateTime;
use DateTimeZone;

use TechBlog\Helpers;

/**
 * Classe responsável pela manipulação dos artigos.
 */
class Article
{

    public string $title, $content, $publication_date, $category;

    public string|null $image;

    /**
     * @var array $data Nomes dos arquivos json encontrados neste diretório
     */
    private array $data;

    /**
     * Criador de Artigos
     * 
     * Obtém informações do artigo a ser criado atribuindo seus valores para o objeto que posteriormente é convertido em JSON. Se tiver upload de imagem, lhe é gerada um nome com indicador baseado em horário para que seja movida para o diretório articleImages, e, após esse processo, é adicionada a url responsável por apontar a imagem do respectivo artigo para exibição na página.
     * 
     * @return int|false
     */
    public function createArticle(string $title, string $content, string $category, ?array $image = null): int|false
    {
        $this->title = $title;
        $this->content = $content;
        $this->category = $category ?: "Sem Categoria";
        $this->publication_date = (new DateTime(timezone: new DateTimeZone('America/Recife')))->format('d/m/Y');

        if (isset($image)) {

            $image_extension = '.' . pathinfo($image['name'], PATHINFO_EXTENSION);

            $new_name = 'image_' . uniqid() . $image_extension;

            $image_origin = $image['tmp_name'];

            $image_destination = dirname(__DIR__) . '/View/WebSite/articleImages/' . $new_name;

            if (move_uploaded_file($image_origin, $image_destination)) {

                $url_image = str_replace(dirname(__DIR__, 2), '', $image_destination);

                $this->image = Helpers::url($url_image);
            } else {
                $this->image = null;
            }
        } else {
            $this->image = $image;
        }

        $article_json = json_encode($this, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        if ($article_json) {
            if ($this->searchArticle()) {
                $json_position = count($this->data) + 1;
                return file_put_contents(__DIR__ . "/article" . $json_position . ".json", $article_json);
            } else {
                return file_put_contents(__DIR__ . "/article1.json", $article_json);
            }
        } else {
            return false;
        }
    }
    /**
     * Buscador de Artigos
     * 
     * Faz uma varredura no diretório atual em busca dos artigos criados.
     * 
     * @return Article|null 
     */
    public function searchArticle(): Article|null
    {
        $directory = dir(__DIR__);
        $files_json = [];

        while ($row = $directory->read()) {
            if (str_contains($row, 'article') && str_ends_with($row, '.json')) $files_json[] = $row;
        }

        $directory->close();

        if ($files_json) {
            $this->data = $files_json;

            return $this;
        } else {
            return null;
        }
    }

    /**
     * Pegador de Artigos
     * 
     * Recebe o conteúdo dos arquivos json e os convertendo-os para objeto.
     * 
     * @return array|null
     */
    public function getArticle(): array|null
    {

        if ($this->data) {
            $articles = [];

            foreach ($this->data as $article) {
                $content_json = file_get_contents(__DIR__ . "/{$article}");

                $articles[] = json_decode($content_json);
            }

            return $articles;
        } else {
            return null;
        }
    }
    /**
     * Pegador de Artigos por Título
     * 
     * Chama o método getArticle para filtrar os artigos com base no título de forma insensível, são atribuídos num array com seus índices reais(ordem em que são encontrados no diretório) para que os artigos possam ser abertos corretamente após a busca.
     * 
     * @return array|null
     */
    public function getArticleByTitle(string $title): array|null
    {

        if ($this->data) {
            $articles = $this->getArticle();
            $selected_articles = [];

            foreach ($articles as $realindex => $article) {
                stripos($article->title, $title) !== false ? $selected_articles[$realindex] = $article : null;
            }

            return $selected_articles;
        } else {
            return null;
        }
    }
    /**
     * Pegador de Artigos por Categoria
     * 
     * Semelhante ao getArticleByTitle, porém com categorias.
     * 
     * @return array|null
     */
    public function getArticleByCategory(string $category): array|null
    {

        if ($this->data) {
            $articles = $this->getArticle();
            $selected_articles = [];

            foreach ($articles as $realindex => $article) {
                str_contains($article->category, $category) ? $selected_articles[$realindex] = $article : null;
            }

            return $selected_articles;
        } else {
            return null;
        }
    }
    /**
     * Atualizador de Artigos
     * 
     * Atualiza informações do Artigo. Nele também é verificado o que será feito com a nova imagem a depender se o artigo já tinha imagem ou não.
     * 
     * @param int $id Índice do Artigo a ser atualizado.
     * @param object $new_article Objeto com informações do Artigo atualizadas.
     * @param array $new_image Upload da nova imagem.
     * 
     * @return bool
     * 
     */
    public function updateArticle(int $id, object $new_article, ?array $new_image = null): bool
    {

        $file = $this->searchArticle()->data[$id];

        if ($new_article->image || $new_article->image && $new_image) {
            $image_path = str_replace(Helpers::url(), dirname(__DIR__, 2), $new_article->image);
        } else if($new_image){
            $image_extension = '.' . pathinfo($new_image['name'], PATHINFO_EXTENSION);

            $new_name = 'image_' . uniqid() . $image_extension;

            $image_path = dirname(__DIR__) . '/View/WebSite/articleImages/' . $new_name;
        }

        if ($new_article->image && $new_image) {

            $updated_image = move_uploaded_file($new_image['tmp_name'], $image_path);

        } else if (empty($new_article->image) && $new_image) {
            if ($updated_image = move_uploaded_file($new_image['tmp_name'], $image_path)) {

                $url_image = str_replace(dirname(__DIR__, 2), '', $image_path);

                $new_article->image = Helpers::url($url_image);
            } else {
                $new_article->image = null;
            }
        }else{
            $updated_image = true;
        }

        $article_json = json_encode($new_article, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $new_article = file_put_contents(__DIR__ . "/$file", $article_json);

        $updated_image = $updated_image && $new_article ? true:false;

        return $updated_image;
    }
    /**
     * Deletador de Artigos
     * 
     * Apaga os arquivos dos artigos. Após a exclusão do artigo, os arquivos são reordenados através do método reorderArticles.
     * 
     * @param int $id Índice do artigo a ser apagado.
     * 
     * @return bool
     */
    public function deleteArticle(int $id): bool
    {
        $file = $this->searchArticle()->data[$id];
        $url_image = $this->searchArticle()->getArticle()[$id]->image;

        if (isset($url_image)) {
            $file_image = str_replace(Helpers::url(), dirname(__DIR__, 2), $url_image);
            $image_deleted = unlink($file_image);
            $article_deleted = unlink(__DIR__ . "/$file");

            $deleted = $article_deleted && $image_deleted ? true : false;
        } else {
            $deleted = unlink(__DIR__ . "/$file");
        }

        $this->reorderArticles();

        return $deleted;
    }
    /**
     * Reordenador de Artigos
     * 
     * Renomeia os arquivos dos artigos para a ordem correta, para que não ocorra erro durante a tentativa de exclusão de artigos.
     * 
     * @return void
     */
    private function reorderArticles(): void
    {
        $directory = dir(__DIR__);
        $files_json = [];

        while ($row = $directory->read()) {
            if (str_contains($row, 'article') && str_ends_with($row, '.json')) {
                $files_json[] = $row;
            }
        }
        $directory->close();

        if (empty($files_json)) {
            return;
        }

        natsort($files_json);
        $files_json = array_values($files_json);

        foreach ($files_json as $index => $oldFileName) {
            $newNumber = $index + 1;
            $newFileName = "article{$newNumber}.json";

            if ($oldFileName !== $newFileName) {
                rename(__DIR__ . "/{$oldFileName}", __DIR__ . "/{$newFileName}");
            }
        }
    }
}
