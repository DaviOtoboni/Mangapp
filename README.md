# MagApp - Gerenciador de Mangás

Uma aplicação web moderna e responsiva desenvolvida em PHP para gerenciar o controle de mangás que o usuário está lendo, pretende ler ou já abandonou.

## 🚀 Funcionalidades

### ✨ Funcionalidades Principais

- **Sistema de Login**: Autenticação com nome de usuário/email e senha
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
- **Menu do Usuário**: Dropdown com informações do usuário e opção de logout
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
- Extensões PHP: curl, json, mbstring (opcional para APIs)
- Conexão com internet para APIs externas (opcional)
- Navegador web moderno

### Instalação

1. **Clone ou baixe** os arquivos para seu servidor web
2. **Configure** as APIs no arquivo `config.php` (opcional)
3. **Acesse** o arquivo `index.php` no navegador
4. **Faça login** com as credenciais de demonstração
5. **Comece** a buscar e adicionar seus mangás!

> **Nota**: Este é um sistema local que funciona sem banco de dados, armazenando os dados na sessão do navegador.

### 🔐 Credenciais de Demonstração

- **Usuário**: admin | **Senha**: admin123
- **Usuário**: usuario | **Senha**: senha123  
- **Email**: teste@email.com | **Senha**: teste123

### 📧 Emails Válidos para Recuperação de Senha

- **teste@email.com**
- **admin@mangapp.com**
- **usuario@mangapp.com**

## ⚙️ Configuração

### Configuração das APIs (Opcional)

Para usar APIs externas, edite o arquivo `config.php`:

```php
// Configurações das APIs externas
$api_config = [
    'jikan' => [
        'base_url' => 'https://api.jikan.moe/v4',
        'enabled' => true
    ],
    'mangadx' => [
        'base_url' => 'https://api.mangadx.org',
        'enabled' => true
    ]
];
```

### Configuração do Sistema

O sistema funciona principalmente de forma local, armazenando os dados na sessão do navegador. As configurações principais estão no arquivo `config.php`.

### Estrutura de Arquivos

```
Mangapp/
├── assets/               # Recursos estáticos
│   └── images/          # Imagens do sistema
├── classes/             # Classes PHP do sistema
│   ├── autoloader.php   # Carregador automático de classes
│   └── MangaDataProcessorSimple.php  # Processador de dados
├── covers/              # Imagens de capa dos mangás
│   └── originals/       # Capas originais
├── config.php           # Configuração principal
├── config-simple.php    # Configuração simplificada
├── debug-drag-drop.html # Página de teste de drag & drop
├── index.php            # Página de entrada (redireciona para login/dashboard)
├── login.php            # Página de login
├── register.php         # Página de criação de conta
├── forgot-password.php  # Página de recuperação de senha
├── logout.php           # Página de logout
├── dashboard.php        # Dashboard principal
├── index-mangas.php     # Página de mangás
├── index-animes.php     # Página de animes
├── index-games.php      # Página de jogos
├── init-api.php         # Inicializador de APIs
├── script-mangas.js     # Scripts JavaScript
├── script-login.js      # Scripts da página de login
├── search-results.php   # Página de resultados de busca
├── setup-test.php       # Página de teste do sistema
├── styles-mangas.css    # Estilos CSS principais
├── styles-login.css     # Estilos da página de login
├── template-mangas.php  # Template HTML principal
├── test-sortable.html   # Página de teste de ordenação
└── README.md            # Documentação
```

## 🔧 Personalização

### Cores e Temas

As cores são definidas através de variáveis CSS e podem ser facilmente personalizadas editando o arquivo `styles-mangas.css` na seção `:root`.

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

- **Sistema Local**: O projeto funciona completamente offline, armazenando dados na sessão do navegador
- **APIs Externas**: Integração opcional com APIs como Jikan e MangaDx para busca de informações
- **Sistema de Upload**: Upload de capas de mangás com redimensionamento automático

## 🔮 Roadmap Futuro

- [x] Sistema local funcional
- [x] Interface responsiva
- [x] Upload de capas
- [x] Sistema de busca
- [x] Autenticação de usuários
- [ ] Sistema de banco de dados
- [ ] Sistema de tags e categorias
- [ ] API REST
- [ ] Sistema de backup
- [ ] Estatísticas avançadas
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
