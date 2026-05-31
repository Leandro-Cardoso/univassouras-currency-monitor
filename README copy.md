# 📚 Biblioteca Digital de Livros

Uma aplicação web desenvolvida em Laravel que permite pesquisar livros em tempo real utilizando a API pública da **Open Library** e gerenciar um acervo pessoal de obras favoritas diretamente no banco de dados.

## 🚀 Guia de Instalação do Ambiente de Desenvolvimento

Escolha a seção correspondente ao seu sistema operacional para configurar o ambiente.

---

### 🪟 Configuração no Windows (Via Laragon + DBeaver)

O **Laragon** é a ferramenta mais recomendada para Windows, pois já traz o PHP, MySQL e o Apache configurados e isolados de forma limpa.

#### 1. Instalação dos Softwares
* **Laragon:** Acesse [laragon.org/download](https://laragon.org/download), baixe a versão **Laragon Full** (com PHP 8.x e MySQL) e siga o instalador padrão.
* **DBeaver:** Acesse [dbeaver.io/download](https://dbeaver.io/download), baixe o instalador para Windows (`.exe`) e instale-o.

#### 2. Configuração do Banco de Dados no DBeaver
1. Abra o Laragon e clique em **Start All** para iniciar os serviços do PHP e MySQL.
2. Abra o **DBeaver**.
3. Clique no ícone de tomada no canto superior esquerdo para criar uma nova conexão e escolha **MySQL** ou **MariaDB**.
4. Defina as configurações padrão do Laragon:
   * **Host:** `localhost` ou `127.0.0.1`
   * **Porta:** `3306`
   * **Database:** *Deixe em branco por enquanto*
   * **Username:** `root`
   * **Password:** *Deixe totalmente em branco (padrão do Laragon)*
5. Clique em **Test Connection**. Se o DBeaver pedir para baixar os drivers, clique em **Download**.
6. Clique em **Finish**.

---

### 🐧 Configuração no Linux (Foco em Arch Linux e derivados)

No Linux, faremos a instalação nativa dos pacotes do MariaDB, PHP e DBeaver.

#### 1. Instalação dos Pacotes (Arch Linux)
Abra o seu terminal e execute os seguintes comandos:

```bash
# Atualizar o sistema e instalar MariaDB, PHP e DBeaver
sudo pacman -Syu mariadb php php-sqlite dbeaver
```

#### 2. Configuração e Inicialização do MariaDB

1. Antes de iniciar o serviço pela primeira vez, instale o diretório de dados do MariaDB:

```Bash
sudo mariadb-install-db --user=mysql --basedir=/usr --datadir=/var/lib/mysql
```

2. Inicie e habilite o serviço para rodar junto com o sistema:

```Bash
sudo systemctl enable --now mariadb
```

3. Defina a senha do usuário root do banco:

```Bash
sudo mysql_secure_installation
```

4. Siga os passos na tela definindo uma senha segura para o usuário root.

#### 3. Ativação do Driver do Banco no PHP (php.ini)

Por padrão, o Arch desativa os drivers de banco de dados. Para ativar:

1. Abra o arquivo de configuração como root:

```Bash
sudo nano /etc/php/php.ini
```

Procure pela linha ;extension=pdo_mysql (use Ctrl + W para buscar no nano).

2. Remova o ponto e vírgula (;) do início da linha para que ela fique assim:

```Ini, TOML
extension=pdo_mysql
```

3. Salve e feche o arquivo (Ctrl + O, Enter, Ctrl + X).

#### 4. Configuração no DBeaver (Linux)

1. Abra o DBeaver.

2. Crie uma nova conexão escolhendo MariaDB.

3. Configure os campos:

    - Host: 127.0.0.1
    - Porta: 3306
    - Username: root
    - Password: A senha que você definiu no passo mysql_secure_installation

4. Teste a conexão e salve.

## 🛠️ Criação do Banco de Dados (DBeaver)

Com o DBeaver conectado ao seu servidor de banco de dados (seja no Windows ou Linux), siga as etapas abaixo para criar a estrutura sem o uso de Migrations do Laravel:

1. Na barra lateral esquerda do DBeaver, clique com o botão direito em Databases e selecione Create New Database.

2. Dê o nome de library_db e clique em OK.

3. Dê um duplo clique sobre o library_db recém-criado para torná-lo o banco ativo.

4. Abra uma nova aba de scripts SQL (SQL Editor -> New SQL Script) e execute o seguinte código completo (Ctrl + A para selecionar tudo e Ctrl + Enter ou Alt + X para rodar):

```SQL
USE library_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE favorite_books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    open_library_id VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NULL,
    cover_url VARCHAR(500) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## ⚙️ Configuração da Aplicação Laravel

1. No terminal do seu projeto, copie o arquivo de configuração de exemplo:

```Bash
cp .env.example .env
```

2. Abra o arquivo .env e ajuste as credenciais do banco de dados e o driver de sessão:

```Snippet de código
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_db
DB_USERNAME=root
DB_PASSWORD=sua_senha_aqui  # Deixe em branco se estiver no Laragon padrão

# Importante para evitar erros de tabelas nativas ausentes:
SESSION_DRIVER=file
```

3. Instale as dependências do projeto (caso esteja baixando o repositório do zero):

```Bash
composer install
```

4. Gere a chave única da aplicação:

```Bash
php artisan key:generate
```

5. Limpe os caches de configuração para garantir a leitura do novo .env:

```Bash
php artisan config:clear
```

## 🖥️ Como Utilizar a Aplicação

1. Inicie o servidor embutido do Laravel através do terminal:

```Bash
php artisan serve
```

2. Abra o seu navegador e acesse o endereço gerado: http://127.0.0.1:8000

### Fluxo do Sistema:

Redirecionamento: Se você tentar acessar a raiz / sem estar logado, o sistema te enviará automaticamente para a tela de /login.

**Registro/Login**: Use o formulário da direita para criar uma conta ou o da esquerda para logar.

**Busca**: Na barra de pesquisa principal, digite o nome de uma obra (Ex: Harry Potter ou The Witcher) e clique em Buscar. O sistema consultará a API da Open Library e trará os 5 primeiros resultados com capas e autores.

**Favoritar**: Clique no botão amarelo ⭐ Favoritar em qualquer livro. Ele será guardado no seu banco de dados atrelado à sua conta.

**Gerenciar**: Clique em Meus Favoritos no topo da tela para ver sua coleção pessoal. Se quiser remover algum item, basta clicar no botão vermelho 🗑 Remover.
