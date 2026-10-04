<?php

declare(strict_types=1);

namespace TechBlog\Controller;

use TechBlog\View\Template\Template;

/**
 * Classe responsável pela instanciação da classe Template, e controlar o acesso das classes que a herdam.
 */
class TemplateController
{

    protected Template $template;

    public function __construct()
    {
        $this->template = new Template('src\View\WebSite');
    }
}
