<?php
/**
 * Versão simplificada do MangaDataProcessor para testes
 */

class MangaDataProcessor {
    
    /**
     * Processar resultados de busca
     */
    public function processSearchResults(array $results): array {
        $processed = [];
        
        foreach ($results as $manga) {
            $processed[] = $this->processMangaData($manga);
        }
        
        return $processed;
    }
    
    /**
     * Processar dados completos de um mangá
     */
    public function processFullMangaData(array $mangaData): array {
        return $this->processMangaData($mangaData);
    }
    
    /**
     * Processar dados básicos de um mangá
     */
    private function processMangaData(array $manga): array {
        return [
            'mal_id' => $manga['mal_id'] ?? 0,
            'title' => $manga['title'] ?? 'Título Desconhecido',
            'title_english' => $manga['title_english'] ?? null,
            'title_japanese' => $manga['title_japanese'] ?? null,
            'synopsis' => $manga['synopsis'] ?? null,
            'image_url' => $manga['images']['jpg']['image_url'] ?? null,
            'score' => $manga['score'] ?? null,
            'popularity' => $manga['popularity'] ?? null,
            'status' => $manga['status'] ?? 'Unknown',
            'chapters' => $manga['chapters'] ?? null,
            'volumes' => $manga['volumes'] ?? null,
            'published_from' => $manga['published']['from'] ?? null,
            'published_to' => $manga['published']['to'] ?? null,
            'genres' => $manga['genres'] ?? [],
            'authors' => $manga['authors'] ?? [],
            'url' => $manga['url'] ?? null,
            'type' => $manga['type'] ?? 'Manga'
        ];
    }
}