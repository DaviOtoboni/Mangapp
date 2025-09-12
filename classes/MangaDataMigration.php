<?php
/**
 * Classe para migração de dados de mangás
 * Expande a estrutura existente para incluir dados da API Jikan
 */
class MangaDataMigration {
    
    /**
     * Migrar dados existentes para nova estrutura
     */
    public static function migrateExistingData(array $existingMangas): array {
        $migratedMangas = [];
        
        foreach ($existingMangas as $manga) {
            $migratedMangas[] = self::migrateSingleManga($manga);
        }
        
        return $migratedMangas;
    }
    
    /**
     * Migrar um único mangá
     */
    public static function migrateSingleManga(array $manga): array {
        // Estrutura base existente
        $migrated = [
            // Campos existentes (preservados)
            'id' => $manga['id'] ?? uniqid(),
            'nome' => $manga['nome'] ?? '',
            'status' => $manga['status'] ?? 'pretendo',
            'capitulos_total' => $manga['capitulos_total'] ?? 0,
            'capitulo_atual' => $manga['capitulo_atual'] ?? 0,
            'finalizado' => $manga['finalizado'] ?? false,
            'em_lancamento' => $manga['em_lancamento'] ?? false,
            'comentario' => $manga['comentario'] ?? '',
            'data_criacao' => $manga['data_criacao'] ?? date('Y-m-d H:i:s'),
            
            // Novos campos da API Jikan (opcionais)
            'api_id' => $manga['api_id'] ?? null,
            'titulo_original' => $manga['titulo_original'] ?? null,
            'titulo_ingles' => $manga['titulo_ingles'] ?? null,
            'titulo_principal' => $manga['titulo_principal'] ?? null,
            'sinopse' => $manga['sinopse'] ?? null,
            'capa_local' => $manga['capa_local'] ?? null,
            'capa_url' => $manga['capa_url'] ?? null,
            'thumbnail_local' => $manga['thumbnail_local'] ?? null,
            'generos' => $manga['generos'] ?? [],
            'autores' => $manga['autores'] ?? [],
            'score' => $manga['score'] ?? null,
            'popularidade' => $manga['popularidade'] ?? null,
            'status_publicacao' => $manga['status_publicacao'] ?? null,
            'data_inicio' => $manga['data_inicio'] ?? null,
            'data_fim' => $manga['data_fim'] ?? null,
            'url_mal' => $manga['url_mal'] ?? null,
            'capitulos_publicados' => $manga['capitulos_publicados'] ?? null,
            'volumes' => $manga['volumes'] ?? null,
            'tipo' => $manga['tipo'] ?? 'Manga',
            'fonte_dados' => $manga['fonte_dados'] ?? 'manual',
            'data_atualizacao' => $manga['data_atualizacao'] ?? null,
            
            // Campos calculados/derivados
            'nome_exibicao' => self::determineDisplayName($manga),
            'progresso_percentual' => self::calculateProgress($manga),
            'tem_dados_api' => !empty($manga['api_id']),
            'precisa_atualizacao' => self::needsUpdate($manga),
        ];
        
        return $migrated;
    }
    
    /**
     * Determinar nome para exibição
     */
    private static function determineDisplayName(array $manga): string {
        // Prioridade: nome local > título inglês > título principal > título original
        if (!empty($manga['nome'])) {
            return $manga['nome'];
        }
        
        if (!empty($manga['titulo_ingles'])) {
            return $manga['titulo_ingles'];
        }
        
        if (!empty($manga['titulo_principal'])) {
            return $manga['titulo_principal'];
        }
        
        if (!empty($manga['titulo_original'])) {
            return $manga['titulo_original'];
        }
        
        return 'Mangá sem título';
    }
    
    /**
     * Calcular progresso percentual
     */
    private static function calculateProgress(array $manga): float {
        $total = $manga['capitulos_total'] ?? 0;
        $atual = $manga['capitulo_atual'] ?? 0;
        
        // Se está em lançamento ou total é 0, usar capítulos publicados se disponível
        if ($total == 0 && !empty($manga['capitulos_publicados'])) {
            $total = $manga['capitulos_publicados'];
        }
        
        if ($total > 0 && $atual > 0) {
            return round(($atual / $total) * 100, 1);
        }
        
        return 0.0;
    }
    
    /**
     * Verificar se precisa de atualização
     */
    private static function needsUpdate(array $manga): bool {
        // Se não tem dados da API, não precisa atualizar
        if (empty($manga['api_id'])) {
            return false;
        }
        
        // Se nunca foi atualizado, precisa
        if (empty($manga['data_atualizacao'])) {
            return true;
        }
        
        // Se última atualização foi há mais de 24 horas
        $lastUpdate = strtotime($manga['data_atualizacao']);
        $now = time();
        
        return ($now - $lastUpdate) > 86400; // 24 horas
    }
    
    /**
     * Criar estrutura vazia para novo mangá
     */
    public static function createEmptyMangaStructure(): array {
        return [
            // Campos obrigatórios
            'id' => uniqid(),
            'nome' => '',
            'status' => 'pretendo',
            'capitulos_total' => 0,
            'capitulo_atual' => 0,
            'finalizado' => false,
            'em_lancamento' => false,
            'comentario' => '',
            'data_criacao' => date('Y-m-d H:i:s'),
            
            // Campos da API (opcionais)
            'api_id' => null,
            'titulo_original' => null,
            'titulo_ingles' => null,
            'titulo_principal' => null,
            'sinopse' => null,
            'capa_local' => null,
            'capa_url' => null,
            'thumbnail_local' => null,
            'generos' => [],
            'autores' => [],
            'score' => null,
            'popularidade' => null,
            'status_publicacao' => null,
            'data_inicio' => null,
            'data_fim' => null,
            'url_mal' => null,
            'capitulos_publicados' => null,
            'volumes' => null,
            'tipo' => 'Manga',
            'fonte_dados' => 'manual',
            'data_atualizacao' => null,
            
            // Campos calculados
            'nome_exibicao' => '',
            'progresso_percentual' => 0.0,
            'tem_dados_api' => false,
            'precisa_atualizacao' => false,
        ];
    }
    
    /**
     * Validar estrutura de dados
     */
    public static function validateMangaStructure(array $manga): array {
        $errors = [];
        
        // Validações obrigatórias
        if (empty($manga['id'])) {
            $errors[] = 'ID é obrigatório';
        }
        
        if (empty($manga['nome']) && empty($manga['titulo_principal'])) {
            $errors[] = 'Nome ou título principal é obrigatório';
        }
        
        if (!in_array($manga['status'], ['lendo', 'pretendo', 'abandonado', 'completado'])) {
            $errors[] = 'Status inválido';
        }
        
        // Validações de tipos
        if (!is_numeric($manga['capitulos_total'])) {
            $errors[] = 'Capítulos total deve ser numérico';
        }
        
        if (!is_numeric($manga['capitulo_atual'])) {
            $errors[] = 'Capítulo atual deve ser numérico';
        }
        
        if (!is_bool($manga['finalizado'])) {
            $errors[] = 'Finalizado deve ser booleano';
        }
        
        // Validações de dados da API
        if (!empty($manga['api_id']) && !is_numeric($manga['api_id'])) {
            $errors[] = 'API ID deve ser numérico';
        }
        
        if (!empty($manga['score']) && (!is_numeric($manga['score']) || $manga['score'] < 0 || $manga['score'] > 10)) {
            $errors[] = 'Score deve ser numérico entre 0 e 10';
        }
        
        if (!empty($manga['generos']) && !is_array($manga['generos'])) {
            $errors[] = 'Gêneros deve ser um array';
        }
        
        if (!empty($manga['autores']) && !is_array($manga['autores'])) {
            $errors[] = 'Autores deve ser um array';
        }
        
        return $errors;
    }
    
    /**
     * Limpar dados desnecessários
     */
    public static function cleanMangaData(array $manga): array {
        // Remover campos nulos ou vazios (exceto campos obrigatórios)
        $requiredFields = [
            'id', 'nome', 'status', 'capitulos_total', 'capitulo_atual',
            'finalizado', 'em_lancamento', 'comentario', 'data_criacao'
        ];
        
        $cleaned = [];
        
        foreach ($manga as $key => $value) {
            // Manter campos obrigatórios sempre
            if (in_array($key, $requiredFields)) {
                $cleaned[$key] = $value;
                continue;
            }
            
            // Manter apenas campos com valor
            if ($value !== null && $value !== '' && $value !== []) {
                $cleaned[$key] = $value;
            }
        }
        
        return $cleaned;
    }
    
    /**
     * Converter para formato de exibição
     */
    public static function formatForDisplay(array $manga): array {
        $formatted = $manga;
        
        // Formatar datas
        if (!empty($manga['data_criacao'])) {
            $formatted['data_criacao_formatada'] = date('d/m/Y H:i', strtotime($manga['data_criacao']));
        }
        
        if (!empty($manga['data_atualizacao'])) {
            $formatted['data_atualizacao_formatada'] = date('d/m/Y H:i', strtotime($manga['data_atualizacao']));
        }
        
        if (!empty($manga['data_inicio'])) {
            $formatted['data_inicio_formatada'] = date('d/m/Y', strtotime($manga['data_inicio']));
        }
        
        if (!empty($manga['data_fim'])) {
            $formatted['data_fim_formatada'] = date('d/m/Y', strtotime($manga['data_fim']));
        }
        
        // Formatar score
        if (!empty($manga['score'])) {
            $formatted['score_formatado'] = number_format($manga['score'], 1);
        }
        
        // Formatar gêneros
        if (!empty($manga['generos']) && is_array($manga['generos'])) {
            $genreNames = array_column($manga['generos'], 'name');
            $formatted['generos_string'] = implode(', ', $genreNames);
        }
        
        // Formatar autores
        if (!empty($manga['autores']) && is_array($manga['autores'])) {
            $authorNames = array_column($manga['autores'], 'name');
            $formatted['autores_string'] = implode(', ', $authorNames);
        }
        
        // Status de progresso
        $formatted['status_progresso'] = self::getProgressStatus($manga);
        
        // Indicadores visuais
        $formatted['badges'] = self::generateBadges($manga);
        
        return $formatted;
    }
    
    /**
     * Obter status de progresso
     */
    private static function getProgressStatus(array $manga): string {
        if ($manga['finalizado']) {
            return 'Finalizado';
        }
        
        if ($manga['em_lancamento']) {
            return 'Em Lançamento';
        }
        
        if ($manga['status'] === 'lendo') {
            $progress = self::calculateProgress($manga);
            if ($progress > 0) {
                return "Lendo ({$progress}%)";
            }
            return 'Lendo';
        }
        
        return ucfirst($manga['status']);
    }
    
    /**
     * Gerar badges para exibição
     */
    private static function generateBadges(array $manga): array {
        $badges = [];
        
        // Badge de fonte de dados
        if (!empty($manga['api_id'])) {
            $badges[] = [
                'text' => 'MyAnimeList',
                'class' => 'badge-api',
                'title' => 'Dados importados do MyAnimeList'
            ];
        }
        
        // Badge de score alto
        if (!empty($manga['score']) && $manga['score'] >= 8.5) {
            $badges[] = [
                'text' => 'Altamente Avaliado',
                'class' => 'badge-high-score',
                'title' => "Score: {$manga['score']}"
            ];
        }
        
        // Badge de popularidade
        if (!empty($manga['popularidade']) && $manga['popularidade'] <= 100) {
            $badges[] = [
                'text' => 'Popular',
                'class' => 'badge-popular',
                'title' => "Ranking: #{$manga['popularidade']}"
            ];
        }
        
        // Badge de status
        if ($manga['em_lancamento']) {
            $badges[] = [
                'text' => 'Em Lançamento',
                'class' => 'badge-ongoing',
                'title' => 'Mangá ainda sendo publicado'
            ];
        }
        
        if ($manga['finalizado']) {
            $badges[] = [
                'text' => 'Finalizado',
                'class' => 'badge-completed',
                'title' => 'Mangá finalizado'
            ];
        }
        
        return $badges;
    }
    
    /**
     * Verificar compatibilidade com versão anterior
     */
    public static function isLegacyFormat(array $manga): bool {
        // Se não tem os novos campos, é formato legado
        $newFields = ['api_id', 'titulo_original', 'sinopse', 'generos'];
        
        foreach ($newFields as $field) {
            if (array_key_exists($field, $manga)) {
                return false; // Tem pelo menos um campo novo
            }
        }
        
        return true;
    }
    
    /**
     * Obter versão da estrutura de dados
     */
    public static function getDataVersion(array $manga): string {
        if (self::isLegacyFormat($manga)) {
            return '1.0';
        }
        
        if (isset($manga['fonte_dados'])) {
            return '2.0';
        }
        
        return '1.5'; // Versão intermediária
    }
}