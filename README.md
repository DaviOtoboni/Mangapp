# MagApp - Gerenciador de Mangás

Uma aplicação web moderna e responsiva desenvolvida em PHP para gerenciar o controle de mangás que o usuário está lendo, pretende ler ou já abandonou.

## 🚀 Funcionalidades

### ✨ Funcionalidades Principais

- **Busca Local**: Sistema de busca e gerenciamento local
- **Suporte Multi-Tipo**: Mangás (japoneses), Manhwas (coreanos), Manhuas (chineses)
- **Adicionar à Biblioteca**: Importação direta de APIs externas
- **Editar Registros**: Modal para atualizar informações dos mangás
- **Excluir Mangás**: Confirmação antes da exclusão
- **Sistema de Status**: Lendo, Pretendo Ler, Abandonado
- **Controle de Progresso**: Capítulo atual vs. total de capítulos
- **Detecção Automática**: Identificação de tipo baseada no idioma original

### 📊 Métricas e Estatísticas

- Total de mangás na biblioteca
- Mangás em andamento (lendo)
- Mangás na lista de desejos (pretendo ler)
- Mangás abandonados
- Mangás finalizados

### 🔍 Sistema de Pesquisa

- **Pesquisa Global**: Barra de pesquisa na navbar
- **Pesquisa Local**: Busca específica na página de mangás
- Filtragem em tempo real dos resultados

### 🎨 Interface e Design

- **Design Moderno**: Interface limpa e intuitiva
- **Responsivo**: Adaptável a todos os dispositivos
- **Tema Escuro/Claro**: Alternância automática com persistência
- **Animações**: Transições suaves e feedback visual

### 🧭 Navegação

- **Navbar Funcional**: Logo, links de navegação e controles
- **Links de Navegação**: Animes e Jogos (preparados para futuras implementações)
- **Ícone de Usuário**: Redirecionamento para página de perfil
- **Sticky Navigation**: Barra de navegação fixa no topo

## 🛠️ Tecnologias Utilizadas

- **Backend**: PHP 7.4+
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Estilização**: CSS Grid, Flexbox, CSS Variables
- **Ícones**: Font Awesome 6.0
- **Fontes**: Google Fonts (Inter)
- **Banco de Dados**: MySQL 5.7+
- **Sistema Local**: Gerenciamento completo offline
- **Cache**: Sistema de cache baseado em arquivos

## 📱 Responsividade

A aplicação é totalmente responsiva e funciona perfeitamente em:

- **Desktop**: Layout em grid com múltiplas colunas
- **Tablet**: Adaptação automática para telas médias
- **Mobile**: Layout em coluna única otimizado para toque

## 🎯 Campos Obrigatórios

- ✅ Nome do mangá
- ✅ Status (lendo, pretendo ler, abandonado)
- ✅ Quantidade total de capítulos

## 📝 Campos Opcionais

- 🔸 Capítulo atual (padrão: 0)
- 🔸 Indicação de finalização (checkbox)

## 🚀 Como Executar

### Requisitos

- Servidor web com suporte a PHP 7.4+
- MySQL 5.7+ ou MariaDB equivalente
- Extensões PHP: curl, json, pdo_mysql, mbstring
- Conexão com internet para APIs externas
- Navegador web moderno

### Instalação

1. **Clone ou baixe** os arquivos para seu servidor web
2. **Configure** o banco de dados MySQL
3. **Execute** as migrações: `php migrate.php`
4. **Configure** as APIs no arquivo `config/apis.php`
5. **Verifique** a saúde do sistema: `php health-check.php`
6. **Acesse** o arquivo `index.php` no navegador
7. **Comece** a buscar e adicionar seus mangás!

## ⚙️ Configuração

### Configuração do Banco de Dados

Edite o arquivo `config/database.php`:

```php
return [
    'host' => 'localhost',
    'dbname' => 'mangapp',
    'username' => 'seu_usuario',
    'password' => 'sua_senha',
    'charset' => 'utf8mb4'
];
```

### Configuração das APIs

Edite o arquivo `config/apis.php`:

```php
return [
    'jikan' => [
        'base_url' => 'https://api.jikan.moe/v4',
        'rate_limit' => 3,
        'timeout' => 10,
        'enabled' => true
    ],
    'mangadx' => [
        'base_url' => 'https://api.mangadx.org',
        'rate_limit' => 5,
        'timeout' => 10,
        'cache_ttl' => 3600,
        'user_agent' => 'MangApp/1.0 (https://yoursite.com)',
        'enabled' => true
    ]
];
```

### Configuração do Cache

Edite o arquivo `config/cache_config.php`:

```php
return [
    'enabled' => true,
    'default_ttl' => 3600,
    'local' => [
        'search_ttl' => 1800,
        'details_ttl' => 7200
        'chapters_ttl' => 3600,
        'covers_ttl' => 86400
    ]
];
```

### Estrutura de Arquivos

```
MagAppLocal/
├── api/                    # Endpoints da API
├── classes/               # Classes PHP do sistema
├── config/               # Arquivos de configuração
├── docs/                 # Documentação
├── cache/                # Cache de dados das APIs
├── covers/               # Imagens de capa
├── css/                  # Arquivos de estilo
├── js/                   # Scripts JavaScript
├── tests/                # Testes unitários
├── index.php             # Arquivo principal
├── migrate.php           # Script de migração
├── health-check.php      # Verificação de saúde
└── README.md             # Documentação
```

## 🔧 Personalização

### Cores e Temas

As cores são definidas através de variáveis CSS e podem ser facilmente personalizadas editando o arquivo `index.php` na seção `:root`.

### Funcionalidades Adicionais

A aplicação está estruturada de forma modular, permitindo fácil adição de:

- Sistema de usuários e autenticação
- Banco de dados para persistência
- API REST para integração
- Sistema de tags e categorias
- Estatísticas avançadas
- Exportação de dados

## 📊 Estrutura de Dados

Cada mangá é armazenado com as seguintes informações:

```php
[
    'id' => 'unique_id',
    'nome' => 'Nome do Mangá',
    'status' => 'lendo|pretendo|abandonado',
    'capitulos_total' => 100,
    'finalizado' => false,
    'capitulo_atual' => 25,
    'data_criacao' => '2024-01-01 12:00:00'
]
```

## 🔒 Segurança

- **Validação de Entrada**: Todos os campos são validados
- **Escape de Saída**: Proteção contra XSS
- **Confirmação de Exclusão**: Prevenção de exclusões acidentais
- **Sessões Seguras**: Gerenciamento seguro de estado

## 📚 Documentação Adicional

- **[Sistema Local](docs/local_system.md)**: Documentação do sistema local
- **[Guia de Deployment](docs/deployment_guide.md)**: Instruções detalhadas para produção
- **[Sistema de Cache](docs/cache_system.md)**: Documentação do sistema de cache

## 🔮 Roadmap Futuro

- [ ] Sistema de banco de dados
- [ ] Autenticação de usuários
- [ ] Sistema de tags e categorias
- [ ] API REST
- [ ] Sistema de backup
- [ ] Estatísticas avançadas
- [ ] Modo offline
- [ ] Aplicativo mobile

## 🤝 Contribuição

Este é um projeto de demonstração que pode ser expandido e melhorado. Sinta-se à vontade para:

- Reportar bugs
- Sugerir melhorias
- Contribuir com código
- Compartilhar feedback

## 📄 Licença

Este projeto é de código aberto e pode ser usado livremente para fins educacionais e comerciais.

## 📞 Suporte

Para dúvidas ou suporte, consulte a documentação ou abra uma issue no repositório.

---

**Desenvolvido com ❤️ para a comunidade de leitores de mangá**
