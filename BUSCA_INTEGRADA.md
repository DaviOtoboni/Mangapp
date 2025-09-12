# 🔍 Sistema de Busca Integrada

## Visão Geral

O sistema de busca integrada combina a busca local (mangás já adicionados) com a busca na API do MyAnimeList, proporcionando uma experiência completa de descoberta de mangás.

## 🚀 Funcionalidades

### 1. Busca Híbrida Inteligente

- **Busca Local Primeiro**: Procura nos mangás já adicionados à lista
- **Expansão Automática**: Se poucos resultados locais (< 3), busca automaticamente na API
- **Fallback Robusto**: Sistema de fallback quando a API está indisponível

### 2. Interface Integrada

- **Barra de Busca Superior**: Busca em tempo real com delay inteligente
- **Barra de Busca Inline**: Busca tradicional com submit
- **Resultados da API**: Seção dedicada para mostrar mangás do MyAnimeList
- **Indicadores Visuais**: Mangás importados da API são marcados com ícone especial

### 3. Importação Simplificada

- **Um Clique**: Adicionar mangá da API diretamente à lista
- **Dados Completos**: Importa título, status, capítulos, sinopse
- **Metadados**: Preserva informações originais da API para referência

## 🎯 Como Usar

### Busca Básica

1. **Digite na barra superior**: Busca em tempo real após 1 segundo
2. **Use a barra inline**: Busca tradicional com botão
3. **Pressione Enter**: Busca imediata em qualquer campo

### Quando Não Há Resultados Locais

1. **Botão "Buscar no MyAnimeList"**: Aparece automaticamente
2. **Resultados da API**: Mostrados em seção especial
3. **Importação**: Clique em "Adicionar à Lista" em qualquer resultado

### Indicadores Visuais

- **🌐 API**: Mangás importados da API têm este indicador
- **Borda Azul**: Mangás da API têm borda colorida diferente
- **Seção Destacada**: Resultados da API aparecem em seção própria

## 🔧 Implementação Técnica

### Fluxo de Busca

```
1. Usuário digita termo
2. Busca local primeiro
3. Se < 3 resultados locais:
   a. Chama API Jikan
   b. Processa resultados
   c. Exibe em seção separada
4. Usuário pode importar da API
```

### Estrutura de Dados

```php
// Mangá local
$manga = [
    'id' => 'unique_id',
    'nome' => 'Nome do Mangá',
    'status' => 'lendo|pretendo|completado|abandonado',
    'capitulos_total' => 100,
    'capitulo_atual' => 50,
    'imported_from_api' => true, // Flag para mangás da API
    'api_data' => [...] // Dados originais da API
];

// Resultado da API
$api_result = [
    'mal_id' => 13,
    'title' => 'One Piece',
    'status' => 'Publishing',
    'chapters' => null,
    'score' => 9.21,
    'image_url' => 'https://...',
    'synopsis' => 'Descrição...',
    'genres' => [...]
];
```

### Endpoints Utilizados

- `api/search-manga.php`: Busca na API Jikan
- `api/import-manga.php`: Importação de dados completos
- `api/status.php`: Status do sistema

## 🎨 Personalização Visual

### CSS Classes Principais

```css
.api-results-section     /* Seção de resultados da API */
.api-manga-card         /* Card individual de mangá da API */
.api-import-btn         /* Botão de importação */
.imported-from-api      /* Mangás importados (lista/blocos) */
.api-indicator          /* Indicador visual de origem API */
```

### Temas

- **Modo Claro**: Cores azuis e cinzas suaves
- **Modo Escuro**: Adaptação automática das cores
- **Responsivo**: Layout adaptável para mobile

## 📱 Responsividade

### Desktop
- Grid de 2-3 colunas para resultados da API
- Barra de busca superior com placeholder animado
- Hover effects e animações suaves

### Mobile
- Grid de 1 coluna para resultados da API
- Botões em largura total
- Interface touch-friendly

## ⚡ Performance

### Otimizações Implementadas

1. **Debounce**: Busca em tempo real com delay de 1s
2. **Cache Local**: Resultados ficam em cache no navegador
3. **Lazy Loading**: Imagens carregam sob demanda
4. **Fallback**: Sistema funciona mesmo com API offline

### Métricas Esperadas

- **Busca Local**: < 100ms
- **Busca API**: 1-3 segundos
- **Importação**: 2-5 segundos
- **Cache Hit**: > 70%

## 🔒 Segurança

### Validações

- **Input Sanitization**: Todos os inputs são sanitizados
- **Rate Limiting**: Respeita limites da API Jikan
- **Error Handling**: Tratamento robusto de erros
- **XSS Protection**: Escape de dados em todas as saídas

### Dados Sensíveis

- **Não armazena**: Senhas ou dados pessoais
- **Cache Local**: Apenas dados públicos da API
- **Logs**: Não registra queries de busca

## 🐛 Troubleshooting

### Problemas Comuns

#### Busca Não Funciona
```
Verificar:
1. JavaScript habilitado
2. Conexão com internet
3. Console do navegador para erros
```

#### API Indisponível
```
Sintomas: Só mostra resultados locais
Solução: Sistema usa fallback automaticamente
Verificar: api/status.php para status
```

#### Importação Falha
```
Verificar:
1. Dados válidos da API
2. Permissões de escrita
3. Logs de erro em logs/
```

### Debug

```javascript
// Verificar status da API
fetch('/api/status.php')
  .then(r => r.json())
  .then(console.log);

// Testar busca manual
window.apiIntegration.searchManga('One Piece')
  .then(console.log)
  .catch(console.error);
```

## 📊 Monitoramento

### Métricas Importantes

- **Buscas por minuto**: Monitorar uso
- **Taxa de importação**: % de resultados importados
- **Tempo de resposta**: Performance da API
- **Erros**: Rate de falhas

### Logs

```bash
# Logs de busca
tail -f logs/api_requests.log

# Erros gerais
tail -f logs/error_log.txt

# Status do sistema
curl localhost/api/status.php
```

## 🔄 Atualizações Futuras

### Melhorias Planejadas

1. **Busca Avançada**: Filtros por gênero, status, etc.
2. **Recomendações**: Sugestões baseadas na lista
3. **Sincronização**: Sync com MyAnimeList account
4. **Offline Mode**: Funcionalidade completa offline

### Extensões Possíveis

- **Outras APIs**: AniList, Kitsu, etc.
- **Machine Learning**: Recomendações inteligentes
- **Social Features**: Compartilhamento de listas
- **Analytics**: Estatísticas de leitura

## 📚 Referências

- [API Jikan Documentation](https://docs.api.jikan.moe/)
- [MyAnimeList](https://myanimelist.net/)
- [Documentação Principal](README_INTEGRACAO_JIKAN.md)

---

**Versão**: 1.0.0  
**Compatibilidade**: Todos os navegadores modernos  
**Dependências**: Sistema de integração API Jikan