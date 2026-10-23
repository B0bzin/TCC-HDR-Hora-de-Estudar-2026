# Hora de Estudar — Tema WordPress (`epa`)

Tema WordPress personalizado desenvolvido como Trabalho de Conclusão de Curso (TCC) do curso **Técnico em Informática** do **IFPR — Campus Pinhais**.

O **Hora de Estudar (HDE)** é uma plataforma educacional voltada a pais e responsáveis que acompanham os estudos das crianças. O site reúne vídeos e artigos organizados por área de conhecimento, perfis de filhos geridos pelo responsável, calendário de estudos integrado ao Google Agenda e recursos de acessibilidade.

> Este repositório contém **apenas o tema personalizado** (o código autoral do projeto). O núcleo do WordPress, os plugins de terceiros e os dados de usuários não fazem parte deste repositório.

---

## Tecnologias

- **WordPress** (tema clássico, baseado em *templates* PHP)
- **PHP** (requer 7.4 ou superior)
- **HTML5 e CSS3** (layout com **Bootstrap 5**)
- **JavaScript** (filtros de busca, votação e interações, sem recarregar a página)

---

## Estrutura do tema

```
epa/
├── front-page.php            Página inicial
├── template-sobre.php        Página "Sobre"
├── archive-epa_video.php     Lista de vídeos (busca + filtros)
├── single-epa_video.php      Página de assistir ao vídeo
├── archive-epa_artigo.php    Lista de artigos
├── single-epa_artigo.php     Página de leitura do artigo
├── template-registrar.php    Cadastro de usuário
├── template-perfil.php       Perfil do responsável / filho
├── template-filhos.php       Gestão dos perfis de filhos
├── template-quem-usando.php  Troca de perfil ativo
├── template-calendario.php   Calendário de estudos
├── template-admin-usuarios.php  Painel de gestão (apenas administradores)
├── functions.php             Configuração do tema e carregamento dos módulos
├── style.css                 Cabeçalho do tema + ajustes
├── assets/                   CSS, JavaScript, imagens e bibliotecas
└── inc/                      Módulos em PHP
    ├── tipos-conteudo.php    Tipos de conteúdo (Vídeos, Artigos, Eventos)
    ├── formularios.php       Contato e cadastro
    ├── interacoes.php        Curtir / não curtir + comentários
    ├── conta.php             Perfil, calendário e notificações
    ├── filhos.php            Perfis de filhos e histórico
    ├── admin-usuarios.php    Gestão de usuários e comentários
    ├── login.php             Personalização da tela de login
    └── ...
```

---

## Como instalar

1. Tenha um ambiente com **WordPress 6.0+** e **PHP 7.4+** (por exemplo, WAMP, XAMPP ou Local).
2. Copie a pasta `epa` para dentro de `wp-content/themes/` da sua instalação WordPress.
3. No painel do WordPress, acesse **Aparência → Temas** e ative **Hora de Estudar**.
4. Na ativação, o tema cria automaticamente as páginas, o menu e o conteúdo inicial do site.

### Plugins necessários

Dois recursos dependem de plugins gratuitos de terceiros, que devem ser instalados e ativados pelo painel (**Plugins → Adicionar novo**):

- **Login with Google** (rtCamp) — botão de acesso com a conta Google.
- **VLibras Widget** (LAVID/UFPB) — tradução do conteúdo para a Língua Brasileira de Sinais.

---

## Principais funcionalidades

- Catálogo de **vídeos** e **artigos** por área de conhecimento, com busca e filtros que funcionam sem recarregar a página.
- **Cadastro e login** de responsáveis, com personalização da tela de login.
- **Perfis de filhos** geridos pelo responsável (as crianças usam o site sem login próprio).
- **Histórico** do que cada criança assistiu.
- **Calendário** de estudos com integração ao **Google Agenda** e lembretes.
- **Avaliação** de vídeos (curtir / não curtir) e comentários.
- **Painel de gestão** de usuários e comentários, restrito a administradores.
- Recursos de **acessibilidade** (controle de tamanho de fonte e tradução em Libras).

---

## Autor

Desenvolvido como TCC do curso Técnico em Informática — IFPR Campus Pinhais.

## Licença

Distribuído sob a **GNU General Public License v2** ou posterior, a mesma licença do WordPress.
