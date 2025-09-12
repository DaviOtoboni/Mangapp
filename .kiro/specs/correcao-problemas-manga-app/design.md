# Design Document

## Overview

Este documento descreve o design técnico para corrigir os problemas identificados no MangApp. A solução foca em três áreas principais: correção da renderização da visualização em blocos, implementação de persistência do estado da visualização, e correção das validações dos modais.

## Architecture

### Componentes Principais

1. **View State Manager**: Gerencia o estado da visualização (lista/blocos) e sua persistência
2. **Modal Validation System**: Sistema de validação dinâmica para os modais de adicionar/editar
3. **Rendering Engine**: Mecanismo corrigido para renderização da visualização em blocos
4. **Local Storage Handler**: Gerenciamento da persistência de preferências do usuário

### Fluxo de Dados

```
User Interaction → State Manager → Local Storage → UI Update
                ↓
            Validation System → Form Processing → Database Update
```

## Components and Interfaces

### 1. View State Manager

**Responsabilidades:**
- Gerenciar o estado atual da visualização (lista/blocos)
- Persistir preferências no localStorage
- Sincronizar estado entre sessões
- Aplicar estado correto no carregamento da página

**Interface:**
```javascript
class ViewStateManager {
    getCurrentView(): string
    setCurrentView(view: string): void
    persistView(view: string): void
    loadPersistedView(): string
    initializeView(): void
}
```

### 2. Modal Validation System

**Responsabilidades:**
- Validar campos dinamicamente baseado em condições
- Gerenciar estados de campos (habilitado/desabilitado)
- Aplicar placeholders informativos
- Sincronizar validações entre modais de adicionar e editar

**Interface:**
```javascript
class ModalValidator {
    toggleRequiredFields(modalType: string): void
    validateEmLancamento(isChecked: boolean): void
    validateStatusCompletado(status: string): void
    applyFieldStates(field: HTMLElement, state: FieldState): void
}
```

### 3. Rendering Engine

**Responsabilidades:**
- Renderizar corretamente a visualização em blocos
- Garantir sincronização entre dados PHP e JavaScript
- Gerenciar transições entre modos de visualização
- Aplicar estilos CSS apropriados

**Interface:**
```javascript
class RenderingEngine {
    switchView(viewMode: string): void
    renderBlocksView(): void
    renderListView(): void
    updateActiveButton(viewMode: string): void
}
```

## Data Models

### ViewState
```javascript
interface ViewState {
    currentView: 'list' | 'blocks'
    timestamp: number
    userId?: string
}
```

### FieldState
```javascript
interface FieldState {
    required: boolean
    disabled: boolean
    placeholder: string
    value?: string
}
```

### MangaData
```javascript
interface MangaData {
    id: string
    nome: string
    status: 'lendo' | 'pretendo' | 'abandonado' | 'completado'
    capitulos_total: number
    capitulo_atual: number
    finalizado: boolean
    em_lancamento: boolean
    comentario: string
    data_criacao: string
}
```

## Error Handling

### 1. View Switching Errors
- **Problema**: Elementos não encontrados durante switch de view
- **Solução**: Verificação de existência de elementos antes de manipulação
- **Fallback**: Recarregar página se elementos críticos não existirem

### 2. Local Storage Errors
- **Problema**: localStorage não disponível ou com erro
- **Solução**: Graceful degradation para funcionamento sem persistência
- **Fallback**: Usar estado padrão (lista) se persistência falhar

### 3. Modal Validation Errors
- **Problema**: Campos não encontrados ou estados inconsistentes
- **Solução**: Validação de existência de elementos antes de manipulação
- **Fallback**: Manter comportamento padrão se validação falhar

### 4. CSS/Rendering Errors
- **Problema**: Estilos não aplicados ou conflitos CSS
- **Solução**: Verificação de classes CSS e aplicação forçada quando necessário
- **Fallback**: Estilos inline como backup

## Testing Strategy

### 1. Unit Tests
- Testar funções JavaScript individualmente
- Validar lógica de persistência de estado
- Testar validações de modal isoladamente

### 2. Integration Tests
- Testar fluxo completo de mudança de visualização
- Validar persistência entre recarregamentos de página
- Testar interação entre modais e validações

### 3. UI Tests
- Verificar renderização correta em ambos os modos
- Testar responsividade em diferentes dispositivos
- Validar comportamento de campos desabilitados

### 4. Cross-browser Tests
- Testar compatibilidade com localStorage
- Verificar comportamento de CSS em diferentes navegadores
- Validar funcionalidade JavaScript cross-browser

## Implementation Details

### 1. Correção da Visualização em Blocos

**Problema Identificado:**
- CSS conflitante ou JavaScript não aplicando classes corretamente
- Possível problema na estrutura HTML ou dados não sendo passados

**Solução:**
1. Verificar e corrigir estrutura HTML da blocks-view
2. Garantir que dados PHP sejam corretamente passados para JavaScript
3. Adicionar debug logging para identificar pontos de falha
4. Implementar verificações de sanidade antes de renderização

### 2. Persistência de Estado de Visualização

**Implementação:**
1. Usar localStorage para armazenar preferência do usuário
2. Aplicar estado persistido no carregamento da página
3. Atualizar estado a cada mudança de visualização
4. Implementar fallback para navegadores sem localStorage

### 3. Correção de Validações de Modal

**Implementação:**
1. Refatorar funções de validação para serem mais robustas
2. Adicionar verificações de existência de elementos
3. Implementar estados visuais claros para campos desabilitados
4. Sincronizar validações entre modais de adicionar e editar

### 4. Melhorias de CSS e Responsividade

**Implementação:**
1. Revisar e corrigir conflitos de CSS
2. Garantir que estilos de campos desabilitados sejam aplicados
3. Melhorar responsividade da visualização em blocos
4. Adicionar transições suaves entre estados

## Performance Considerations

### 1. Rendering Performance
- Minimizar reflows durante mudanças de visualização
- Usar CSS transforms para transições quando possível
- Implementar lazy loading se necessário para muitos mangás

### 2. Storage Performance
- Debounce de escritas no localStorage
- Compressão de dados se necessário
- Limpeza periódica de dados antigos

### 3. JavaScript Performance
- Evitar queries DOM desnecessárias
- Cache de elementos DOM frequentemente acessados
- Otimizar event listeners

## Security Considerations

### 1. Data Validation
- Validar dados antes de armazenar no localStorage
- Sanitizar dados vindos do localStorage
- Implementar validação tanto no frontend quanto backend

### 2. XSS Prevention
- Escapar dados do usuário em todas as renderizações
- Usar textContent ao invés de innerHTML quando possível
- Validar dados de entrada nos modais

## Browser Compatibility

### Supported Browsers
- Chrome 70+
- Firefox 65+
- Safari 12+
- Edge 79+

### Fallbacks
- localStorage: Usar sessionStorage ou cookies como fallback
- CSS Grid: Fallback para flexbox em navegadores antigos
- ES6 Features: Transpilação se necessário