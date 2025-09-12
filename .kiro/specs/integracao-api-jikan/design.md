# Design Document

## Overview

Este documento descreve o design técnico para integrar a API Jikan (MyAnimeList) ao MangApp. A solução implementará um sistema completo de busca, importação e cache de dados de mangás, mantendo compatibilidade com o sistema existente.

## Architecture

### Componentes Principais

1. **Jikan API Service**: Serviço para comunicação com a API Jikan
2. **Manga Search System**: Sistema de busca e seleção de mangás
3. **Cover Image Manager**: Gerenciador de download e cache de capas
4. **Data Processor**: Processador e validador de dados da API
5. **Cache Manager**: Sistema de cache para otimização de performance

### Fluxo de Dados

```
User Input → Search Modal → Jikan API → Data Processing → Cache Storage
                ↓
Form Population ← Image Download ← Data Validation ← API Response
```

## Components and Interfaces

### 1. Jikan API Service (PHP)

**Responsabilidades:**
- Comunicação com a API Jikan
- Rate limiting e throttling
- Tratamento de erros de rede
- Cache de requisições

**Interface:**
```php
class JikanAPIService {
    private $baseUrl = 'https://api.jikan.moe/v4';
    private $rateLimit = 60; // requests per minute
    
    public function searchManga(string $query): array
    public function getMangaById(int $id): ?array
    public function isRateLimited(): bool
    private function makeRequest(string $endpoint): ?array
    private function handleRateLimit(): void
}
```

### 2. Manga Search System (JavaScript)

**Responsabilidades:**
- Interface de busca no frontend
- Exibição de resultados
- Seleção e importação de dados
- Comunicação com backend

**Interface:**
```javascript
class MangaSearchSystem {
    constructor(modalId, formId)
    async searchManga(query): Promise<Array>
    displayResults(results): void
    selectManga(mangaData): void
    populateForm(mangaData): void
    showError(message): void
}
```

### 3. Cover Image Manager (PHP)

**Responsabilidades:**
- Download de capas da API
- Armazenamento local de imagens
- Geração de thumbnails
- Limpeza de cache de imagens

**Interface:**
```php
class CoverImageManager {
    private $coversDir = 'covers/';
    
    public function downloadCover(string $url, string $mangaId): ?string
    public function getCoverPath(string $mangaId): ?string
    public function generateThumbnail(string $imagePath): string
    public function cleanupOldCovers(): void
    private function validateImage(string $imagePath): bool
}
```

### 4. Data Processor (PHP)

**Responsabilidades:**
- Validação de dados da API
- Transformação para formato interno
- Mesclagem com dados existentes
- Sanitização de conteúdo

**Interface:**
```php
class MangaDataProcessor {
    public function processAPIData(array $apiData): array
    public function validateMangaData(array $data): bool
    public function mergeMangaData(array $existing, array $apiData): array
    public function sanitizeContent(string $content): string
    private function extractGenres(array $genres): array
}
```

## Data Models

### Expanded Manga Structure
```php
interface ExpandedMangaData {
    // Dados existentes
    id: string
    nome: string
    status: string
    capitulos_total: int
    capitulo_atual: int
    finalizado: bool
    em_lancamento: bool
    comentario: string
    data_criacao: string
    
    // Novos dados da API Jikan
    api_id?: int                    // MyAnimeList ID
    titulo_original?: string       // Título japonês
    titulo_ingles?: string         // Título em inglês
    sinopse?: string              // Descrição completa
    capa_local?: string           // Caminho da capa local
    capa_url?: string             // URL original da capa
    generos?: array               // Lista de gêneros
    autores?: array               // Lista de autores
    score?: float                 // Pontuação no MAL
    popularidade?: int            // Ranking de popularidade
    status_publicacao?: string    // Status oficial (Publishing, Finished, etc.)
    data_inicio?: string          // Data de início da publicação
    data_fim?: string             // Data de fim da publicação
    url_mal?: string              // Link para MyAnimeList
    capitulos_publicados?: int    // Capítulos oficialmente publicados
    volumes?: int                 // Número de volumes
    tipo?: string                 // Manga, Manhwa, Manhua, etc.
    fonte_dados?: string          // 'jikan' ou 'manual'
    data_atualizacao?: string     // Última atualização dos dados da API
}
```

### API Response Structure
```php
interface JikanMangaResponse {
    mal_id: int
    title: string
    title_english?: string
    title_japanese?: string
    synopsis?: string
    images: {
        jpg: {
            image_url: string
            small_image_url: string
            large_image_url: string
        }
    }
    genres: Array<{mal_id: int, name: string}>
    authors: Array<{mal_id: int, name: string}>
    score?: float
    popularity?: int
    status: string
    published: {
        from?: string
        to?: string
    }
    chapters?: int
    volumes?: int
    url: string
}
```

## Error Handling

### 1. API Errors
- **Rate Limiting**: Implementar queue de requisições
- **Network Errors**: Retry automático com backoff exponencial
- **Invalid Responses**: Validação e fallback para entrada manual
- **Timeout**: Cancelamento de requisições longas

### 2. Image Download Errors
- **404 Not Found**: Usar placeholder padrão
- **Invalid Format**: Validar formato antes de salvar
- **Storage Errors**: Verificar permissões e espaço em disco
- **Corrupted Images**: Validar integridade após download

### 3. Data Processing Errors
- **Invalid JSON**: Tratar respostas malformadas
- **Missing Fields**: Usar valores padrão apropriados
- **Encoding Issues**: Garantir UTF-8 correto
- **XSS Prevention**: Sanitizar todo conteúdo HTML

## Testing Strategy

### 1. Unit Tests
- Testar cada método da JikanAPIService
- Validar processamento de dados
- Testar download e cache de imagens
- Verificar sanitização de dados

### 2. Integration Tests
- Testar fluxo completo de busca e importação
- Verificar integração com formulário existente
- Testar comportamento com API indisponível
- Validar cache e rate limiting

### 3. UI Tests
- Testar modal de busca
- Verificar preenchimento automático do formulário
- Testar exibição de capas
- Validar responsividade

## Implementation Details

### 1. Database Schema Updates

```sql
-- Adicionar colunas à tabela de mangás (se usando banco de dados)
ALTER TABLE mangas ADD COLUMN api_id INT NULL;
ALTER TABLE mangas ADD COLUMN titulo_original VARCHAR(255) NULL;
ALTER TABLE mangas ADD COLUMN titulo_ingles VARCHAR(255) NULL;
ALTER TABLE mangas ADD COLUMN sinopse TEXT NULL;
ALTER TABLE mangas ADD COLUMN capa_local VARCHAR(255) NULL;
ALTER TABLE mangas ADD COLUMN generos JSON NULL;
ALTER TABLE mangas ADD COLUMN autores JSON NULL;
ALTER TABLE mangas ADD COLUMN score DECIMAL(3,2) NULL;
ALTER TABLE mangas ADD COLUMN popularidade INT NULL;
ALTER TABLE mangas ADD COLUMN status_publicacao VARCHAR(50) NULL;
ALTER TABLE mangas ADD COLUMN data_inicio DATE NULL;
ALTER TABLE mangas ADD COLUMN data_fim DATE NULL;
ALTER TABLE mangas ADD COLUMN url_mal VARCHAR(255) NULL;
ALTER TABLE mangas ADD COLUMN fonte_dados VARCHAR(20) DEFAULT 'manual';
ALTER TABLE mangas ADD COLUMN data_atualizacao TIMESTAMP NULL;
```

### 2. Directory Structure

```
project/
├── covers/                 # Diretório para capas baixadas
│   ├── thumbnails/        # Miniaturas das capas
│   └── originals/         # Capas em tamanho original
├── cache/                 # Cache de requisições da API
│   └── jikan/            # Cache específico da Jikan
├── classes/              # Classes PHP
│   ├── JikanAPIService.php
│   ├── CoverImageManager.php
│   ├── MangaDataProcessor.php
│   └── CacheManager.php
└── js/                   # JavaScript
    └── manga-search.js   # Sistema de busca frontend
```

### 3. Configuration

```php
// config/jikan.php
return [
    'api_url' => 'https://api.jikan.moe/v4',
    'rate_limit' => 60, // requests per minute
    'cache_duration' => 3600, // 1 hour
    'covers_dir' => 'covers/',
    'max_image_size' => 5 * 1024 * 1024, // 5MB
    'allowed_image_types' => ['jpg', 'jpeg', 'png', 'webp'],
    'timeout' => 30, // seconds
    'retry_attempts' => 3,
    'retry_delay' => 1000 // milliseconds
];
```

## Performance Considerations

### 1. API Rate Limiting
- Implementar queue de requisições
- Cache inteligente de resultados
- Throttling automático
- Priorização de requisições

### 2. Image Optimization
- Compressão automática de imagens
- Geração de thumbnails
- Lazy loading de capas
- CDN para imagens (futuro)

### 3. Caching Strategy
- Cache de resultados de busca (15 minutos)
- Cache de dados de mangá (1 hora)
- Cache de imagens (permanente até limpeza)
- Invalidação inteligente de cache

## Security Considerations

### 1. Input Validation
- Sanitizar queries de busca
- Validar IDs de mangá
- Verificar URLs de imagem
- Limitar tamanho de uploads

### 2. XSS Prevention
- Escapar todo conteúdo da API
- Sanitizar HTML em sinopses
- Validar nomes de arquivos
- CSP headers apropriados

### 3. File Security
- Validar tipos de arquivo
- Verificar magic numbers
- Limitar tamanho de imagens
- Sandbox para downloads

## Browser Compatibility

### Supported Features
- Fetch API para requisições
- Promise/async-await
- FormData para uploads
- FileReader para preview
- LocalStorage para cache

### Fallbacks
- XMLHttpRequest como fallback
- Polyfills para browsers antigos
- Graceful degradation
- Progressive enhancement