<div align="center">

# 📚 Acervo Acadêmico

**Sistema de Armazenamento de Artigos e Projetos Acadêmicos**

Cadastre, pesquise e baixe artigos em PDF em uma plataforma simples, segura e com visual azul e branco.

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-4479A1?logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?logo=javascript&logoColor=black)

</div>

---

## 📖 Sobre o projeto

O **Acervo Acadêmico** é uma aplicação web criada como atividade da disciplina de **Análise e Gerenciamento de Projetos** (curso Técnico em Informática). O objetivo é organizar e compartilhar conhecimento acadêmico: usuários cadastrados enviam artigos e projetos em PDF, e todos os usuários logados podem consultar, pesquisar e baixar o material.

## ✨ Funcionalidades

| Recurso | Descrição |
|---|---|
| 👤 **Registro de usuários** | Cadastro com nome de usuário, e-mail e senha |
| 🔐 **Login e logout** | Acesso protegido por sessão; o login aceita usuário ou e-mail |
| 📝 **Inserção de artigos** | Título, descrição, ano, quantidade de páginas, autores e upload do PDF |
| 🗂️ **Visualização** | Todos os artigos em cartões, com título, autores, descrição, ano, páginas e quem enviou |
| 🔎 **Pesquisa** | Busca por palavra-chave no título, na descrição e nos autores |
| ⬇️ **Download** | Baixe o PDF de qualquer artigo direto do cartão |
| 🎨 **Interface responsiva** | Visual azul e branco, adaptado para computador e celular |

## 🛠️ Tecnologias

- **HTML5** estrutura das páginas
- **CSS3** estilo e responsividade
- **JavaScript** validação dos formulários no navegador
- **PHP** lógica do servidor, sessões, upload e download de arquivos
- **MySQL** armazenamento de usuários e artigos
- **InfinityFree** hospedagem gratuita (PHP + MySQL)

## ⚙️ Como funciona

```
Visitante ──► register.php ──► cria conta (senha criptografada)
          └─► login.php ────► abre sessão
                                   │
                                   ▼
                    index.php  (acervo + busca)
                       │              │
                       ▼              ▼
                  novo.php      download.php
              (envia o PDF)    (entrega o PDF)
```

1. O usuário **se cadastra** e **faz login**.
2. Logado, ele acessa o **Acervo** (`index.php`), onde vê todos os artigos e pode pesquisar.
3. Em **+ Novo Artigo** (`novo.php`), preenche os dados e envia o PDF. O arquivo é validado (extensão, cabeçalho `%PDF-` e limite de 10 MB), recebe um nome aleatório e é salvo em `uploads/`. Os dados vão para o MySQL.
4. O **download** passa por `download.php`, que só atende usuários logados.

### 🔒 Segurança

- Senhas salvas com `password_hash` (bcrypt) e conferidas com `password_verify`
- Consultas ao banco com **PDO e prepared statements** (proteção contra SQL Injection)
- Saídas escapadas com `htmlspecialchars` (proteção contra XSS)
- **Token CSRF** em todos os formulários
- Pasta `uploads/` bloqueada para acesso direto (`.htaccess`); arquivos só saem por `download.php`
- Validação dupla: no navegador (JavaScript) e no servidor (PHP)

## 🗃️ Estrutura de pastas

```
acervo/
├── css/
│   └── style.css          # Estilos (azul e branco)
├── js/
│   └── app.js             # Validação dos formulários
├── includes/
│   ├── config.php         # Conexão com o banco e funções auxiliares
│   ├── header.php         # Topo das páginas
│   └── footer.php         # Rodapé das páginas
├── uploads/
│   └── .htaccess          # Bloqueia acesso direto aos PDFs
├── database.sql           # Criação das tabelas
├── index.php              # Acervo e pesquisa
├── register.php           # Cadastro de usuário
├── login.php              # Login
├── logout.php             # Logout
├── novo.php               # Cadastro de artigo + upload do PDF
├── download.php           # Download do PDF
└── README.md
```

## 🗄️ Banco de dados

**usuarios**: `id`, `username`, `email`, `senha_hash`, `criado_em`

**artigos**: `id`, `usuario_id`, `titulo`, `descricao`, `ano`, `paginas`, `autores`, `arquivo`, `nome_original`, `criado_em`

Cada artigo pertence a um usuário (`artigos.usuario_id` → `usuarios.id`).

## 🚀 Como executar

### Opção 1: no seu computador (XAMPP)

1. Instale o [XAMPP](https://www.apachefriends.org) e, no painel, clique em **Start** em **Apache** e **MySQL**.
2. Copie a pasta `acervo` para `C:\xampp\htdocs\`.
3. Abra `http://localhost/phpmyadmin` e crie um banco chamado `acervo`.
4. Selecione o banco, vá na aba **Importar** e importe o arquivo `database.sql`.
5. Edite `includes/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'acervo');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```
6. Acesse **http://localhost/acervo/register.php**, crie sua conta e comece a usar.

### Opção 2: hospedagem na InfinityFree

1. Crie a conta e um site em [infinityfree.com](https://www.infinityfree.com).
2. No painel, em **MySQL Databases**, crie um banco e anote **Hostname**, **DB Name**, **User Name** e a senha.
3. Abra o **phpMyAdmin** do banco e importe o `database.sql`.
4. Edite `includes/config.php` com os dados do passo 2.
5. Envie todos os arquivos para a pasta `htdocs` (File Manager ou FTP), mantendo a estrutura de pastas.
6. Acesse `seu-dominio/register.php` e teste.

> 💡 **Dica:** a InfinityFree pode limitar o tamanho dos uploads. Se o envio falhar, teste com um PDF menor.

## 🧪 Como testar

1. Crie uma conta em **Cadastrar** e faça login.
2. Clique em **+ Novo Artigo**, preencha os campos e envie um PDF.
3. Volte ao **Acervo** e confira se o artigo apareceu.
4. Pesquise por uma palavra do título ou da descrição.
5. Clique em **Baixar PDF**.

## 👥 Equipe

| | Integrante |
|---|---|
| 👩‍💻 | Anna Carolina dos Santos Rodrigues |
| 👩‍💻 | Aghata dos Santos Machado |
| 👨‍💻 | Arthur Felis Silva Ferreira |
| 👨‍💻 | Bruno Ribeiro da Silva |
| 👨‍💻 | Miguel Andrade |
| 👨‍💻 | Lucas Andrade da Silva |
| 👩‍💻 | Isabelly Matias da Cruz |

---

<div align="center">

Feito com 💙 pela equipe · Análise e Gerenciamento de Projetos · 2026

</div>