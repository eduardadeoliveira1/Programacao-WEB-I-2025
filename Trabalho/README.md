
##  TRABALHO DE PROGRAÇÃO WEB II - AVALIAÇÃO DE SATISFAÇÃO 

Este sistema permite que clientes realizem **avaliações de atendimento e serviços** da Pousada do Sol, com um **painel administrativo** para gerenciar perguntas e feedbacks.

##  Funcionalidades

### Painel Administrativo
- Gerenciamento de perguntas da avaliação
- Visualização de estatísticas (média geral, total de avaliações)
- Exibição dos feedbacks mais recentes
- Logout com expiração automática de sessão

###  Área de Avaliação
- Acesso por dispositivos registrados (tablets)
- Avaliações anônimas e seguras
- Perguntas dinâmicas configuráveis pelo painel admin


## Tecnologias Utilizadas

| Camada | Tecnologia |
|--------|-------------|
| **Back-end** | PHP 8+ |
| **Banco de Dados** | PostgreSQL |
| **Front-end** | HTML5, CSS, JavaScript |
| **Layout** | Tema personalizado “Pousada do Sol” |


##  Estrutura de Pastas

Trabalho
├── public/ # Interface pública (avaliar)
│ ├── index.php
│ ├── obter_perguntas.php
│ ├── submeter_avaliacao.php
│ ├── js/avaliacao.js
│ └── img/logo_pousada.png
│ └── css/style.css
│
├── admin/ # Painel administrativo
│ ├── index.php
│ ├── login.php
│ ├── logout.php
│ ├── perguntas.php
│ ├── usuarios.php
│ ├── buscar_senha.php
│ ├── alterar_pergunta.php
│ ├── excluir_pergubta.php
│ ├── salvar_pergunta.php
│ └── css/
│ ├── admin.css
│ ├── perguntas.css
│ └── login.css
│ └── js/
│ ├── perguntas.js
│ 
├── config/ # Configurações do sistema
│ ├── config.php
│ └── database.php
│
├── includes
│ ├── auth.php
│ ├── db_functions.php
│ ├── functions.php
│ ├── sanitize.php
│
├── logs
├── sql
└── README.md

---

## Banco de Dados

### Principais Tabelas

- `usuarios_admin` – administradores do sistema  
- `perguntas` – perguntas da avaliação  
- `avaliacoes` – campo de texto de respostas enviadas pelos clientes  
- `setores` – setores da pousada (ex: recepção, limpeza, cozinha)  
- `dispositivo` – tablets cadastrados  
- `respostas` – respostas das perguntas


### 1. Clonar ou Baixar o Repositório
```bash
git clone 
cd Trabalho
```

### 2. Configurar o Banco de Dados

#### 2.1. Criar o Banco de Dados

Acesse o PostgreSQL e crie database sistema_avaliacao;
Execute o script SQL localizado na pasta `sql/` dentro do databse  **sistema_avalicao**.

#### 2.3 Configurar a Conexão com o Banco

Edite o arquivo `config/database.php` com suas credenciais do PostgreSQL:
```php
<?php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'pousada_avaliacao');
define('DB_USER', 'postgres');
define('DB_PASS', 'sua_senha_aqui');
```

### 3. Acessar o Sistema

#### Área Pública (Avaliação de Clientes)
```
http://localhost/Trabalho/public/?dispositivo=variavel_dispositivo

Opções de dispositivos: 
- tablet-piscina-001
- tablet-limpeza-001
- tablet-manutencao-001
- tablet-restaurante-001
- tablet-recepcao-001
```
#### Painel Administrativo
```
http://localhost:8000/admin/login.php
```

**Credenciais padrão** :
- **Usuário:** `admin`
- **Senha:** `password`

## Contato

Para dúvidas sobre a execução do projeto, entre em contato:
- **Email:** [eduarda.oliveira@unidavi.edu.com]

---

**Desenvolvido como trabalho acadêmico de Programação Web II**  
**Instituição:** Unidavi  
**Curso:** Sistemas de Informação
**Semestre/Ano:** 04/2025