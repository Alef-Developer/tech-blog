# Tech Blog

Sistema para postagem de artigos sobre tecnologia feito em PHP, utilizando Programação Orientada a Objetos e JSON para armazenamento do conteúdo dos artigos.

Este projeto foi desenvolvido com foco prático em aprendizado e tem como inspiração o desafio [Personal Blog do roadmap.sh](https://roadmap.sh/projects/personal-blog).

## 🚀 Tecnologias e Ferramentas Utilizadas

* **PHP Puro:** Arquitetura baseada em Programação Orientada a Objetos.
* **Composer:** Para o gerenciamento de pacotes e dependências.
* **Twig Template:** Motor de templates rápido e seguro para renderizar as views do projeto (`twig/twig`).
* **SimpleRouter:** Biblioteca (`pecee/simple-router`) utilizada para criar URLs amigáveis e gerenciar rotas de forma simplificada.
* **HTML & CSS:** Estruturação e estilização da interface utilizando recursos do framework Bootstrap.
* **JSON:** Abordagem "file-based" simulando um banco de dados local para persistência e armazenamento das postagens.

## 🔐 Autenticação
Considerando o caráter didático e de aprendizado deste sistema, a autenticação de administrador (login) é feita de maneira direta. A validação de e-mail e senha ocorre diretamente no código fonte, sem a necessidade de um banco de dados externo para a gestão de credenciais.

## ⚙️ Como executar o projeto localmente

1. Clone este repositório para a sua máquina:
   ```bash
   git clone https://github.com/Alef-Developer/tech-blog.git
   ```
2. Acesse a pasta do projeto e instale as dependências do Composer:
   ```bash
   composer install
   ```
3. Configure o seu ambiente de desenvolvimento local (como o XAMPP). O projeto já conta com um arquivo .htaccess na raiz, responsável por remover a listagem de diretórios e ativar o RewriteEngine, direcionando as requisições de URL de forma limpa para o index.php.

4. Rode o Servidor

## Uso

 - Navegue pelo site seja visualizando ou buscando artigos por título(na barra de pesquisa) ou categoria.
 - Se quiser adicionar, atualizar, ou apagar algum artigo. Faça login acessando o link clicando no ícone de pessoa, e acesse utilizando as seguintes credenciais: e-mail: adm@gmail.com, senha: adm123.
 - Artigos são armazenados em src/Model e suas imagens respectivas imagens em src/View/WebSite/articleImages