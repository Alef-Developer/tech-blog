<?php

namespace TechBlog\View\Template;

use TechBlog\Helpers;
use Twig\Lexer;

/**
 * Classe responsável pela configuração e renderização dos templates através do Twig Template.
 */
class Template
{

    private \Twig\Environment $twig;

    /**
     * @param string $diretorio Caminho para o diretório onde se encontram os templates.
     */
    public function __construct(string $diretorio)
    {
        $loader = new \Twig\Loader\FilesystemLoader($diretorio);
        $this->twig = new \Twig\Environment($loader);

        $lexer =  new Lexer($this->twig, [$this->twigFunctions()]);

        $this->twig->setLexer($lexer);
        $this->twig->addGlobal('ADM', ADM);
    }
/**
     * Renderizador de Templates
     * 
     * Renderiza o template informado com o array de dados associativo.
     * 
     * @param string $view Nome do arquivo para renderizar.
     * @param array $data Array associativo com os valores usados no template.
     * @return string|void
     */
    public function renderTemplate(string $view, array $data = [])
    {
        try{
            return $this->twig->render($view, $data);
        }catch(\Exception $e){
            echo "Erro:" . $e->getMessage();
        }
    }
    /**
     * Funções Twig
     * 
     * Adiciona funções customizadas para uso nos arquivos de template.
     * 
     * @return void
     */
    public function twigFunctions(): void
    {
        [
            $this->twig->addFunction(new \Twig\TwigFunction('url', function(string $url = ''){
                return Helpers::url($url);
            }))
        ];
    }
}
