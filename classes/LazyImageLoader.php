<?php
/**
 * Sistema de Lazy Loading para Imagens de Capa
 * 
 * Implementa carregamento sob demanda de imagens para melhorar
 * performance inicial da página e reduzir uso de banda.
 */

class LazyImageLoader 
{
    private array $config;
    private ?CacheManager $cache = null;
    
    public function __construct(array $config = []) 
    {
        $this->config = array_merge([
            'placeholder_quality' => 10,
            'blur_radius' => 5,
            'fade_duration' => 300,
            'intersection_threshold' => 0.1,
            'root_margin' => '50px',
            'cache_thumbnails' => true,
            'thumbnail_size' => [150, 200],
            'webp_support' => true,
            'progressive_loading' => true
        ], $config);
        
        if (class_exists('CacheManager')) {
            $this->cache = CacheManager::getInstance();
        }
    }
    
    /**
     * Gerar HTML para imagem lazy loading
     */
    public function generateLazyImage(array $imageData): string 
    {
        $src = $imageData['src'] ?? '';
        $alt = $imageData['alt'] ?? 'Imagem';
        $class = $imageData['class'] ?? '';
        $width = $imageData['width'] ?? null;
        $height = $imageData['height'] ?? null;
        
        // Gerar placeholder
        $placeholder = $this->generatePlaceholder($src, $width, $height);
        
        // Gerar thumbnail se disponível
        $thumbnail = $this->generateThumbnail($src);
        
        // Atributos da imagem
        $attributes = [
            'class' => trim("lazy-image {$class}"),
            'data-src' => $src,
            'data-alt' => $alt,
            'alt' => $alt,
            'loading' => 'lazy'
        ];
        
        if ($width) $attributes['width'] = $width;
        if ($height) $attributes['height'] = $height;
        
        // Usar thumbnail como src inicial se disponível, senão placeholder
        $initialSrc = $thumbnail ?: $placeholder;
        $attributes['src'] = $initialSrc;
        
        // Adicionar dados para progressive loading
        if ($this->config['progressive_loading'] && $thumbnail) {
            $attributes['data-thumbnail'] = $thumbnail;
            $attributes['data-placeholder'] = $placeholder;
        }
        
        // Construir HTML
        $attrString = $this->buildAttributeString($attributes);
        
        return "<img {$attrString}>";
    }
    
    /**
     * Gerar container com lazy loading
     */
    public function generateLazyContainer(array $imageData, array $containerOptions = []): string 
    {
        $containerClass = $containerOptions['class'] ?? 'lazy-container';
        $aspectRatio = $containerOptions['aspect_ratio'] ?? '2/3';
        $showSpinner = $containerOptions['show_spinner'] ?? true;
        
        $image = $this->generateLazyImage($imageData);
        
        $spinner = $showSpinner ? '<div class="lazy-spinner"></div>' : '';
        
        return "
        <div class=\"{$containerClass}\" style=\"aspect-ratio: {$aspectRatio}\">
            {$image}
            {$spinner}
        </div>";
    }
    
    /**
     * Gerar placeholder base64
     */
    private function generatePlaceholder(string $src, ?int $width = null, ?int $height = null): string 
    {
        // Usar cache se disponível
        $cacheKey = 'placeholder_' . md5($src . "_{$width}_{$height}");
        if ($this->cache && ($cached = $this->cache->get($cacheKey))) {
            return $cached;
        }
        
        // Dimensões padrão se não especificadas
        $width = $width ?: $this->config['thumbnail_size'][0];
        $height = $height ?: $this->config['thumbnail_size'][1];
        
        // Gerar placeholder SVG
        $placeholder = $this->generateSVGPlaceholder($width, $height);
        
        // Cache por 24 horas
        if ($this->cache) {
            $this->cache->set($cacheKey, $placeholder, 86400);
        }
        
        return $placeholder;
    }
    
    /**
     * Gerar thumbnail otimizado
     */
    private function generateThumbnail(string $src): ?string 
    {
        if (!$this->config['cache_thumbnails'] || empty($src)) {
            return null;
        }
        
        // Verificar se é URL externa
        if (!$this->isLocalImage($src)) {
            return $this->generateExternalThumbnail($src);
        }
        
        // Processar imagem local
        return $this->generateLocalThumbnail($src);
    }
    
    /**
     * Gerar thumbnail para imagem externa
     */
    private function generateExternalThumbnail(string $url): ?string 
    {
        $cacheKey = 'thumbnail_' . md5($url);
        
        if ($this->cache && ($cached = $this->cache->get($cacheKey))) {
            return $cached;
        }
        
        try {
            // Para URLs externas, usar serviço de proxy/resize se disponível
            // Por enquanto, retornar a URL original
            $thumbnail = $url;
            
            // Cache por 2 horas
            if ($this->cache) {
                $this->cache->set($cacheKey, $thumbnail, 7200);
            }
            
            return $thumbnail;
            
        } catch (Exception $e) {
            error_log("Erro ao gerar thumbnail externo: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Gerar thumbnail para imagem local
     */
    private function generateLocalThumbnail(string $path): ?string 
    {
        if (!file_exists($path)) {
            return null;
        }
        
        $cacheKey = 'thumbnail_local_' . md5($path . filemtime($path));
        
        if ($this->cache && ($cached = $this->cache->get($cacheKey))) {
            return $cached;
        }
        
        try {
            $thumbnailPath = $this->createThumbnailFile($path);
            
            if ($thumbnailPath) {
                // Cache por 24 horas
                if ($this->cache) {
                    $this->cache->set($cacheKey, $thumbnailPath, 86400);
                }
                
                return $thumbnailPath;
            }
            
        } catch (Exception $e) {
            error_log("Erro ao gerar thumbnail local: " . $e->getMessage());
        }
        
        return null;
    }
    
    /**
     * Criar arquivo de thumbnail
     */
    private function createThumbnailFile(string $originalPath): ?string 
    {
        $thumbnailDir = 'covers/thumbnails/';
        if (!is_dir($thumbnailDir)) {
            mkdir($thumbnailDir, 0755, true);
        }
        
        $pathInfo = pathinfo($originalPath);
        $thumbnailName = $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];
        $thumbnailPath = $thumbnailDir . $thumbnailName;
        
        // Se já existe e é mais novo que o original, usar
        if (file_exists($thumbnailPath) && filemtime($thumbnailPath) >= filemtime($originalPath)) {
            return $thumbnailPath;
        }
        
        // Criar thumbnail
        $imageInfo = getimagesize($originalPath);
        if (!$imageInfo) {
            return null;
        }
        
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        $mimeType = $imageInfo['mime'];
        
        // Calcular dimensões do thumbnail
        list($thumbWidth, $thumbHeight) = $this->calculateThumbnailSize(
            $originalWidth, 
            $originalHeight, 
            $this->config['thumbnail_size'][0], 
            $this->config['thumbnail_size'][1]
        );
        
        // Criar imagem a partir do original
        $originalImage = $this->createImageFromFile($originalPath, $mimeType);
        if (!$originalImage) {
            return null;
        }
        
        // Criar thumbnail
        $thumbnail = imagecreatetruecolor($thumbWidth, $thumbHeight);
        
        // Preservar transparência para PNG
        if ($mimeType === 'image/png') {
            imagealphablending($thumbnail, false);
            imagesavealpha($thumbnail, true);
            $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
            imagefill($thumbnail, 0, 0, $transparent);
        }
        
        // Redimensionar
        imagecopyresampled(
            $thumbnail, $originalImage,
            0, 0, 0, 0,
            $thumbWidth, $thumbHeight,
            $originalWidth, $originalHeight
        );
        
        // Salvar thumbnail
        $success = false;
        switch ($mimeType) {
            case 'image/jpeg':
                $success = imagejpeg($thumbnail, $thumbnailPath, 85);
                break;
            case 'image/png':
                $success = imagepng($thumbnail, $thumbnailPath, 6);
                break;
            case 'image/gif':
                $success = imagegif($thumbnail, $thumbnailPath);
                break;
            case 'image/webp':
                if (function_exists('imagewebp')) {
                    $success = imagewebp($thumbnail, $thumbnailPath, 85);
                }
                break;
        }
        
        // Limpar memória
        imagedestroy($originalImage);
        imagedestroy($thumbnail);
        
        return $success ? $thumbnailPath : null;
    }
    
    /**
     * Gerar placeholder SVG
     */
    private function generateSVGPlaceholder(int $width, int $height): string 
    {
        $color = '#f0f0f0';
        $textColor = '#999';
        
        $svg = "
        <svg width=\"{$width}\" height=\"{$height}\" xmlns=\"http://www.w3.org/2000/svg\">
            <rect width=\"100%\" height=\"100%\" fill=\"{$color}\"/>
            <text x=\"50%\" y=\"50%\" font-family=\"Arial, sans-serif\" font-size=\"14\" 
                  fill=\"{$textColor}\" text-anchor=\"middle\" dy=\".3em\">📖</text>
        </svg>";
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
    
    /**
     * Criar imagem a partir de arquivo
     */
    private function createImageFromFile(string $path, string $mimeType) 
    {
        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($path);
            case 'image/png':
                return imagecreatefrompng($path);
            case 'image/gif':
                return imagecreatefromgif($path);
            case 'image/webp':
                if (function_exists('imagecreatefromwebp')) {
                    return imagecreatefromwebp($path);
                }
                break;
        }
        return false;
    }
    
    /**
     * Calcular dimensões do thumbnail mantendo proporção
     */
    private function calculateThumbnailSize(int $originalWidth, int $originalHeight, int $maxWidth, int $maxHeight): array 
    {
        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
        
        return [
            (int)($originalWidth * $ratio),
            (int)($originalHeight * $ratio)
        ];
    }
    
    /**
     * Verificar se é imagem local
     */
    private function isLocalImage(string $src): bool 
    {
        return !filter_var($src, FILTER_VALIDATE_URL) || 
               strpos($src, $_SERVER['HTTP_HOST']) !== false;
    }
    
    /**
     * Construir string de atributos HTML
     */
    private function buildAttributeString(array $attributes): string 
    {
        $parts = [];
        foreach ($attributes as $key => $value) {
            $parts[] = $key . '="' . htmlspecialchars($value) . '"';
        }
        return implode(' ', $parts);
    }
    
    /**
     * Gerar JavaScript para lazy loading
     */
    public function generateLazyLoadingScript(): string 
    {
        $config = json_encode([
            'threshold' => $this->config['intersection_threshold'],
            'rootMargin' => $this->config['root_margin'],
            'fadeDuration' => $this->config['fade_duration'],
            'progressiveLoading' => $this->config['progressive_loading']
        ]);
        
        return "
        <script>
        class LazyImageLoader {
            constructor(config = {}) {
                this.config = Object.assign({
                    threshold: 0.1,
                    rootMargin: '50px',
                    fadeDuration: 300,
                    progressiveLoading: true
                }, config);
                
                this.observer = null;
                this.init();
            }
            
            init() {
                if ('IntersectionObserver' in window) {
                    this.observer = new IntersectionObserver(
                        this.handleIntersection.bind(this),
                        {
                            threshold: this.config.threshold,
                            rootMargin: this.config.rootMargin
                        }
                    );
                    
                    this.observeImages();
                } else {
                    // Fallback para navegadores antigos
                    this.loadAllImages();
                }
            }
            
            observeImages() {
                const images = document.querySelectorAll('.lazy-image[data-src]');
                images.forEach(img => this.observer.observe(img));
            }
            
            handleIntersection(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadImage(entry.target);
                        this.observer.unobserve(entry.target);
                    }
                });
            }
            
            loadImage(img) {
                const src = img.dataset.src;
                if (!src) return;
                
                // Mostrar spinner se disponível
                const container = img.closest('.lazy-container');
                const spinner = container?.querySelector('.lazy-spinner');
                if (spinner) spinner.style.display = 'block';
                
                // Criar nova imagem para pré-carregamento
                const newImg = new Image();
                
                newImg.onload = () => {
                    // Progressive loading se habilitado
                    if (this.config.progressiveLoading && img.dataset.thumbnail) {
                        this.progressiveLoad(img, src);
                    } else {
                        this.directLoad(img, src);
                    }
                    
                    // Esconder spinner
                    if (spinner) spinner.style.display = 'none';
                };
                
                newImg.onerror = () => {
                    img.classList.add('lazy-error');
                    if (spinner) spinner.style.display = 'none';
                };
                
                newImg.src = src;
            }
            
            progressiveLoad(img, finalSrc) {
                // Fade para a imagem final
                img.style.transition = `opacity \${this.config.fadeDuration}ms ease`;
                img.style.opacity = '0.7';
                
                setTimeout(() => {
                    img.src = finalSrc;
                    img.style.opacity = '1';
                    img.classList.add('lazy-loaded');
                    img.removeAttribute('data-src');
                }, 50);
            }
            
            directLoad(img, src) {
                img.style.transition = `opacity \${this.config.fadeDuration}ms ease`;
                img.style.opacity = '0';
                
                setTimeout(() => {
                    img.src = src;
                    img.style.opacity = '1';
                    img.classList.add('lazy-loaded');
                    img.removeAttribute('data-src');
                }, 50);
            }
            
            loadAllImages() {
                const images = document.querySelectorAll('.lazy-image[data-src]');
                images.forEach(img => this.loadImage(img));
            }
            
            // Método público para observar novas imagens
            observeNewImages() {
                if (this.observer) {
                    this.observeImages();
                }
            }
        }
        
        // Inicializar quando DOM estiver pronto
        document.addEventListener('DOMContentLoaded', () => {
            window.lazyLoader = new LazyImageLoader({$config});
        });
        </script>";
    }
    
    /**
     * Gerar CSS para lazy loading
     */
    public function generateLazyLoadingCSS(): string 
    {
        return "
        <style>
        .lazy-container {
            position: relative;
            overflow: hidden;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            contain: layout style paint;
        }
        
        .lazy-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: opacity 300ms ease, filter 300ms ease;
            will-change: opacity, filter;
        }
        
        .lazy-image:not(.lazy-loaded) {
            filter: blur(2px);
        }
        
        .lazy-image.lazy-loaded {
            filter: none;
            will-change: auto;
        }
        
        .lazy-image.lazy-error {
            opacity: 0.5;
            filter: grayscale(100%);
        }
        
        .lazy-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 24px;
            height: 24px;
            border: 2px solid #ddd;
            border-top: 2px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: none;
            z-index: 1;
            contain: strict;
        }
        
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        /* Placeholder melhorado */
        .lazy-image[src*='data:image/svg'] {
            filter: blur(1px);
            opacity: 0.8;
        }
        
        /* Performance optimizations */
        .lazy-image {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
        
        /* Preload hint for critical images */
        .lazy-image.critical {
            content-visibility: auto;
        }
        
        /* Responsividade */
        @media (max-width: 768px) {
            .lazy-spinner {
                width: 20px;
                height: 20px;
            }
            
            .lazy-image {
                image-rendering: auto;
            }
        }
        
        /* Modo escuro */
        [data-theme='dark'] .lazy-container {
            background: #2a2a2a;
        }
        
        [data-theme='dark'] .lazy-spinner {
            border-color: #555;
            border-top-color: #007bff;
        }
        
        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            .lazy-image {
                transition: none;
            }
            
            .lazy-spinner {
                animation: none;
            }
        }
        </style>";
    }
    
    /**
     * Implementar preload de imagens críticas
     */
    public function preloadCriticalImages(array $criticalImages): string 
    {
        $preloadLinks = [];
        
        foreach ($criticalImages as $image) {
            $src = $image['src'] ?? '';
            $type = $image['type'] ?? 'image';
            
            if (!empty($src)) {
                $preloadLinks[] = "<link rel=\"preload\" href=\"{$src}\" as=\"{$type}\" crossorigin>";
            }
        }
        
        return implode("\n", $preloadLinks);
    }
    
    /**
     * Otimizar imagens com WebP e AVIF
     */
    public function generateOptimizedImageSources(string $originalSrc): array 
    {
        $sources = [];
        $pathInfo = pathinfo($originalSrc);
        $baseName = $pathInfo['filename'];
        $baseDir = dirname($originalSrc);
        
        // AVIF (melhor compressão)
        $avifPath = $baseDir . '/' . $baseName . '.avif';
        if (file_exists($avifPath)) {
            $sources[] = [
                'srcset' => $avifPath,
                'type' => 'image/avif'
            ];
        }
        
        // WebP (boa compressão, amplo suporte)
        $webpPath = $baseDir . '/' . $baseName . '.webp';
        if (file_exists($webpPath)) {
            $sources[] = [
                'srcset' => $webpPath,
                'type' => 'image/webp'
            ];
        }
        
        // Original como fallback
        $sources[] = [
            'srcset' => $originalSrc,
            'type' => $this->getMimeType($originalSrc)
        ];
        
        return $sources;
    }
    
    /**
     * Gerar picture element com múltiplos formatos
     */
    public function generatePictureElement(array $imageData): string 
    {
        $src = $imageData['src'] ?? '';
        $alt = $imageData['alt'] ?? 'Imagem';
        $class = $imageData['class'] ?? '';
        
        if (empty($src)) {
            return '';
        }
        
        $sources = $this->generateOptimizedImageSources($src);
        $sourceElements = [];
        
        // Gerar source elements (exceto o último que é o fallback)
        for ($i = 0; $i < count($sources) - 1; $i++) {
            $source = $sources[$i];
            $sourceElements[] = "<source srcset=\"{$source['srcset']}\" type=\"{$source['type']}\">";
        }
        
        // Imagem fallback com lazy loading
        $fallbackSrc = end($sources)['srcset'];
        $lazyImage = $this->generateLazyImage(array_merge($imageData, ['src' => $fallbackSrc]));
        
        $pictureContent = implode("\n", $sourceElements) . "\n" . $lazyImage;
        
        return "<picture class=\"lazy-picture {$class}\">\n{$pictureContent}\n</picture>";
    }
    
    /**
     * Obter MIME type de arquivo
     */
    private function getMimeType(string $path): string 
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'avif' => 'image/avif',
            'svg' => 'image/svg+xml'
        ];
        
        return $mimeTypes[$extension] ?? 'image/jpeg';
    }
}