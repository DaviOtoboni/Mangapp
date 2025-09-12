# Requirements Document

## Introduction

Este documento define os requisitos para integrar a API Jikan (MyAnimeList) ao MangApp, permitindo que os usuários busquem e importem automaticamente informações detalhadas sobre mangás, incluindo capas, sinopses, gêneros e metadados oficiais.

## Requirements

### Requirement 1

**User Story:** Como usuário, quero buscar mangás na base de dados do MyAnimeList, para que eu possa importar automaticamente informações precisas e completas.

#### Acceptance Criteria

1. WHEN o usuário clica no botão "Buscar Mangá" THEN o sistema SHALL abrir um modal de busca
2. WHEN o usuário digita o nome de um mangá THEN o sistema SHALL fazer uma busca na API Jikan
3. WHEN a API retorna resultados THEN o sistema SHALL exibir uma lista com capas e informações básicas
4. WHEN não há resultados THEN o sistema SHALL mostrar uma mensagem informativa
5. WHEN há erro na API THEN o sistema SHALL permitir entrada manual como fallback

### Requirement 2

**User Story:** Como usuário, quero selecionar um mangá dos resultados da busca, para que as informações sejam automaticamente preenchidas no formulário.

#### Acceptance Criteria

1. WHEN o usuário clica em um resultado da busca THEN o sistema SHALL importar os dados do mangá
2. WHEN os dados são importados THEN o sistema SHALL preencher automaticamente o formulário
3. WHEN há capa disponível THEN o sistema SHALL baixar e exibir a imagem
4. WHEN os dados são importados THEN o sistema SHALL fechar o modal de busca
5. WHEN o usuário pode editar THEN o sistema SHALL permitir modificação dos dados importados

### Requirement 3

**User Story:** Como usuário, quero que as capas dos mangás sejam baixadas e armazenadas localmente, para que eu tenha uma experiência visual rica mesmo offline.

#### Acceptance Criteria

1. WHEN um mangá é importado com capa THEN o sistema SHALL baixar a imagem automaticamente
2. WHEN a imagem é baixada THEN o sistema SHALL armazená-la no diretório local /covers/
3. WHEN a capa é salva THEN o sistema SHALL usar o caminho local ao invés da URL externa
4. WHEN há erro no download THEN o sistema SHALL usar um placeholder padrão
5. WHEN o mangá é exibido THEN o sistema SHALL mostrar a capa local se disponível

### Requirement 4

**User Story:** Como usuário, quero que o sistema mantenha informações detalhadas dos mangás, para que eu tenha acesso a sinopses, gêneros e metadados oficiais.

#### Acceptance Criteria

1. WHEN um mangá é importado THEN o sistema SHALL salvar título original e em inglês
2. WHEN há sinopse disponível THEN o sistema SHALL importar e exibir a descrição
3. WHEN há gêneros THEN o sistema SHALL importar e exibir como tags
4. WHEN há informações de publicação THEN o sistema SHALL salvar datas e status oficial
5. WHEN há score e popularidade THEN o sistema SHALL importar essas métricas

### Requirement 5

**User Story:** Como usuário, quero que o sistema funcione de forma eficiente com cache, para que as buscas sejam rápidas e não sobrecarreguem a API.

#### Acceptance Criteria

1. WHEN uma busca é feita THEN o sistema SHALL respeitar o rate limit da API (60 req/min)
2. WHEN um mangá é buscado novamente THEN o sistema SHALL usar cache se disponível
3. WHEN há dados em cache THEN o sistema SHALL verificar se estão atualizados
4. WHEN o cache expira THEN o sistema SHALL fazer nova requisição à API
5. WHEN há muitas requisições THEN o sistema SHALL implementar throttling

### Requirement 6

**User Story:** Como desenvolvedor, quero que a integração seja robusta e não quebre funcionalidades existentes, para que o sistema continue funcionando mesmo com problemas na API.

#### Acceptance Criteria

1. WHEN a API está indisponível THEN o sistema SHALL permitir entrada manual normal
2. WHEN há erro de rede THEN o sistema SHALL mostrar mensagem apropriada
3. WHEN dados da API são inválidos THEN o sistema SHALL validar antes de usar
4. WHEN não há internet THEN o sistema SHALL funcionar com dados locais
5. WHEN há timeout THEN o sistema SHALL cancelar requisição e permitir retry