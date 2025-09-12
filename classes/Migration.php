<?php
/**
 * Sistema de Migração de Banco de Dados
 * 
 * Esta classe gerencia a criação e atualização da estrutura
 * do banco de dados para suporte a múltiplas fontes de dados.
 */

class Migration 
{
    private static array $migrations = [];
    
    /**
     * Registrar uma migração
     */
    public static function register(string $name, callable $up, callable $down = null): void 
    {
        self::$migrations[$name] = [
            'up' => $up,
            'down' => $down,
            'executed' => false
        ];
    }
    
    /**
     * Executar todas as migrações pendentes
     */
    public static function runAll(): array 
    {
        self::createMigrationsTable();
        self::registerMigrations();
        
        $executed = [];
        $executedMigrations = self::getExecutedMigrations();
        
        foreach (self::$migrations as $name => $migration) {
            if (!in_array($name, $executedMigrations)) {
                try {
                    Database::transaction(function() use ($name, $migration) {
                        $migration['up']();
                        self::markAsExecuted($name);
                    });
                    
                    $executed[] = $name;
                    echo "✅ Migração executada: $name\n";
                    
                } catch (Exception $e) {
                    echo "❌ Erro na migração $name: " . $e->getMessage() . "\n";
                    throw $e;
                }
            }
        }
        
        return $executed;
    }
    
    /**
     * Reverter uma migração específica
     */
    public static function rollback(string $name): bool 
    {
        if (!isset(self::$migrations[$name])) {
            throw new Exception("Migração não encontrada: $name");
        }
        
        $migration = self::$migrations[$name];
        
        if ($migration['down'] === null) {
            throw new Exception("Migração $name não tem rollback definido");
        }
        
        try {
            Database::transaction(function() use ($name, $migration) {
                $migration['down']();
                self::markAsNotExecuted($name);
            });
            
            echo "✅ Rollback executado: $name\n";
            return true;
            
        } catch (Exception $e) {
            echo "❌ Erro no rollback $name: " . $e->getMessage() . "\n";
            throw $e;
        }
    }
    
    /**
     * Registrar todas as migrações do sistema
     */
    private static function registerMigrations(): void 
    {
        // Migração 1: Criar tabela de usuários
        self::register('001_create_users_table', function() {
            $sql = "
                CREATE TABLE IF NOT EXISTS users (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    username VARCHAR(50) UNIQUE NOT NULL,
                    email VARCHAR(100) UNIQUE NOT NULL,
                    password_hash VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            Database::getConnection()->exec($sql);
        });
        
        // Migração 2: Criar tabela principal de manga
        self::register('002_create_manga_table', function() {
            $sql = "
                CREATE TABLE IF NOT EXISTS manga (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    title VARCHAR(255) NOT NULL,
                    title_english VARCHAR(255) NULL,
                    title_original VARCHAR(255) NULL,
                    synopsis TEXT NULL,
                    content_type ENUM('manga', 'manhwa', 'manhua') DEFAULT 'manga',
                    origin_country VARCHAR(50) DEFAULT 'Japan',
                    status VARCHAR(50) DEFAULT 'Unknown',
                    content_rating VARCHAR(20) NULL,
                    year INT NULL,
                    score DECIMAL(3,2) DEFAULT 0.00,
                    members INT DEFAULT 0,
                    cover_image VARCHAR(500) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    
                    INDEX idx_title (title),
                    INDEX idx_type (content_type),
                    INDEX idx_status (status),
                    INDEX idx_year (year),
                    INDEX idx_score (score)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            Database::getConnection()->exec($sql);
        });
        
        // Migração 3: Criar tabela de fontes de dados
        self::register('003_create_manga_sources_table', function() {
            $sql = "
                CREATE TABLE IF NOT EXISTS manga_sources (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    manga_id INT NOT NULL,
                    source VARCHAR(20) NOT NULL,
                    external_id VARCHAR(100) NOT NULL,
                    source_url VARCHAR(500) NULL,
                    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    
                    FOREIGN KEY (manga_id) REFERENCES manga(id) ON DELETE CASCADE,
                    UNIQUE KEY unique_source_mapping (manga_id, source),
                    INDEX idx_source (source),
                    INDEX idx_external_id (external_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            Database::getConnection()->exec($sql);
        });
        
        // Migração 4: Criar tabela de gêneros
        self::register('004_create_manga_genres_table', function() {
            $sql = "
                CREATE TABLE IF NOT EXISTS manga_genres (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    manga_id INT NOT NULL,
                    genre VARCHAR(50) NOT NULL,
                    
                    FOREIGN KEY (manga_id) REFERENCES manga(id) ON DELETE CASCADE,
                    INDEX idx_manga_genre (manga_id, genre),
                    INDEX idx_genre (genre)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            Database::getConnection()->exec($sql);
        });
        
        // Migração 5: Criar tabela de autores
        self::register('005_create_manga_authors_table', function() {
            $sql = "
                CREATE TABLE IF NOT EXISTS manga_authors (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    manga_id INT NOT NULL,
                    name VARCHAR(100) NOT NULL,
                    role ENUM('author', 'artist', 'both') DEFAULT 'author',
                    
                    FOREIGN KEY (manga_id) REFERENCES manga(id) ON DELETE CASCADE,
                    INDEX idx_manga_author (manga_id, name),
                    INDEX idx_author_name (name)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            Database::getConnection()->exec($sql);
        });
        
        // Migração 6: Criar tabela de capítulos
        self::register('006_create_manga_chapters_table', function() {
            $sql = "
                CREATE TABLE IF NOT EXISTS manga_chapters (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    manga_id INT NOT NULL,
                    source VARCHAR(20) NOT NULL,
                    external_chapter_id VARCHAR(100) NOT NULL,
                    chapter_number VARCHAR(20) NOT NULL,
                    title VARCHAR(255) NULL,
                    language VARCHAR(10) DEFAULT 'en',
                    pages INT DEFAULT 0,
                    published_at TIMESTAMP NULL,
                    external_url VARCHAR(500) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    
                    FOREIGN KEY (manga_id) REFERENCES manga(id) ON DELETE CASCADE,
                    INDEX idx_manga_chapter (manga_id, chapter_number),
                    INDEX idx_source_chapter (source, external_chapter_id),
                    INDEX idx_published (published_at)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            Database::getConnection()->exec($sql);
        });
        
        // Migração 7: Criar tabela de biblioteca do usuário
        self::register('007_create_user_library_table', function() {
            $sql = "
                CREATE TABLE IF NOT EXISTS user_library (
                    id INT PRIMARY KEY AUTO_INCREMENT,
                    user_id INT NOT NULL,
                    manga_id INT NOT NULL,
                    status ENUM('reading', 'completed', 'on_hold', 'dropped', 'plan_to_read') DEFAULT 'plan_to_read',
                    progress INT DEFAULT 0,
                    score DECIMAL(3,2) NULL,
                    notes TEXT NULL,
                    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                    FOREIGN KEY (manga_id) REFERENCES manga(id) ON DELETE CASCADE,
                    UNIQUE KEY unique_user_manga (user_id, manga_id),
                    INDEX idx_user_status (user_id, status),
                    INDEX idx_user_score (user_id, score)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ";
            Database::getConnection()->exec($sql);
        });
    }
    
    /**
     * Criar tabela de controle de migrações
     */
    private static function createMigrationsTable(): void 
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS migrations (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(255) UNIQUE NOT NULL,
                executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        Database::getConnection()->exec($sql);
    }
    
    /**
     * Obter lista de migrações já executadas
     */
    private static function getExecutedMigrations(): array 
    {
        try {
            $result = Database::query("SELECT name FROM migrations ORDER BY executed_at");
            return array_column($result, 'name');
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Marcar migração como executada
     */
    private static function markAsExecuted(string $name): void 
    {
        Database::insert('migrations', ['name' => $name]);
    }
    
    /**
     * Marcar migração como não executada (rollback)
     */
    private static function markAsNotExecuted(string $name): void 
    {
        Database::delete('migrations', ['name' => $name]);
    }
    
    /**
     * Verificar se todas as tabelas necessárias existem
     */
    public static function checkTables(): array 
    {
        $requiredTables = [
            'users',
            'manga', 
            'manga_sources',
            'manga_genres',
            'manga_authors',
            'manga_chapters',
            'user_library'
        ];
        
        $status = [];
        
        foreach ($requiredTables as $table) {
            $status[$table] = Database::tableExists($table);
        }
        
        return $status;
    }
}