<?php

declare(strict_types=1);

namespace TechBlog;

/**
 * Classe com métodos de apoio essenciais para o projeto.
 */
class Helpers
{
    /**
     * Construtor de URL
     *
     * Monta a URL a partir da verificação do protocolo do servidor (HTTP/HTTPS) e do Host.
     * Se for ambiente local (localhost), adiciona o caminho relativo (SLUG) ao diretório raiz.
     *
     * @param string $url Caminho relativo a ser anexado à URL base.
     * @return string URL completa gerada.
     */
    public static function url(string $url = ''): string
    {

        $protocolo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";

        $host = $_SERVER['HTTP_HOST'];

        $urlBase = $protocolo . "://" . $host;

        if ($host != 'localhost') {
            return $urlBase . $url;
        } else {
            return $urlBase . SLUG . $url;
        }
    }
    /**
     * Redirecionador
     * 
     * Pega a URL montada através do método url, e utiliza a mesma para redirecionar.
     * 
     * @param string|null $url URL completa para redirecionar.
     * @return void
     */
    public static function redirect(?string $url = null): void
    {
        header('HTTP/1.1 302 Found');

        $local = $url ? self::url($url) : self::url();

        header("Location: $local");

        exit();
    }
}
