<?php
/**
 * Soundex File Management System
 * Organizes and manages project files
 */

class FileManager {
    private $rootPath;
    
    public function __construct($rootPath = '.') {
        $this->rootPath = realpath($rootPath);
    }
    
    /**
     * Get organized file structure
     */
    public function getFileStructure() {
        $structure = [
            'config' => [],
            'database' => [],
            'api' => [],
            'managers' => [],
            'frontend' => [
                'css' => [],
                'js' => [],
                'images' => [],
                'assets' => []
            ],
            'pages' => [],
            'utilities' => [],
            'documentation' => [],
            'uploads' => [],
            'tests' => []
        ];
        
        // Scan and categorize files
        $this->scanDirectory($this->rootPath, $structure);
        
        return $structure;
    }
    
    /**
     * Scan directory and categorize files
     */
    private function scanDirectory($path, &$structure, $basePath = '') {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = substr($file->getPathname(), strlen($this->rootPath) + 1);
                $this->categorizeFile($relativePath, $file, $structure);
            }
        }
    }
    
    /**
     * Categorize individual files
     */
    private function categorizeFile($relativePath, $fileInfo, &$structure) {
        $filename = $fileInfo->getFilename();
        $extension = strtolower($fileInfo->getExtension());
        
        // Configuration files
        if (in_array($filename, ['db_config.php', '.env', 'config.php']) || 
            strpos($filename, 'config') !== false) {
            $structure['config'][] = $relativePath;
        }
        // Database files
        elseif (in_array($filename, ['create_tables.php', 'insert_sample_data.php']) ||
                strpos($filename, 'table') !== false) {
            $structure['database'][] = $relativePath;
        }
        // API files
        elseif ($filename === 'api.php') {
            $structure['api'][] = $relativePath;
        }
        // Manager classes
        elseif (strpos($filename, 'Manager.php') !== false) {
            $structure['managers'][] = $relativePath;
        }
        // CSS files
        elseif ($extension === 'css') {
            $structure['frontend']['css'][] = $relativePath;
        }
        // JavaScript files
        elseif ($extension === 'js') {
            $structure['frontend']['js'][] = $relativePath;
        }
        // Image files
        elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
            if (strpos($relativePath, 'assets/') !== false || strpos($relativePath, 'images/') !== false) {
                $structure['frontend']['images'][] = $relativePath;
            }
        }
        // HTML files
        elseif ($extension === 'html') {
            if (dirname($relativePath) === 'pages' || strpos($relativePath, 'page') !== false) {
                $structure['pages'][] = $relativePath;
            } elseif (strpos($relativePath, 'test') !== false) {
                $structure['tests'][] = $relativePath;
            }
        }
        // Documentation
        elseif (in_array($extension, ['md', 'txt', 'docx', 'pdf']) || 
                strpos($filename, 'README') !== false) {
            $structure['documentation'][] = $relativePath;
        }
        // Utility scripts
        elseif (in_array($extension, ['ps1', 'sh', 'bat']) || 
                strpos($filename, 'script') !== false) {
            $structure['utilities'][] = $relativePath;
        }
    }
    
    /**
     * Create organized directory structure
     */
    public function createOrganizedStructure() {
        $directories = [
            'config',
            'database',
            'api',
            'managers',
            'frontend/css',
            'frontend/js',
            'frontend/images',
            'frontend/assets',
            'backend',
            'controllers',
            'models',
            'views',
            'pages',
            'utilities',
            'documentation',
            'uploads/applications',
            'uploads/products',
            'uploads/profiles',
            'logs',
            'cache',
            'tests'
        ];
        
        foreach ($directories as $dir) {
            $fullPath = $this->rootPath . DIRECTORY_SEPARATOR . $dir;
            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0755, true);
                echo "Created directory: $dir\n";
            }
        }
    }
    
    /**
     * Move files to organized structure
     */
    public function organizeFiles() {
        $structure = $this->getFileStructure();
        
        // Move database files
        foreach ($structure['database'] as $file) {
            $this->moveFile($file, 'database/' . basename($file));
        }
        
        // Move manager files
        foreach ($structure['managers'] as $file) {
            $this->moveFile($file, 'managers/' . basename($file));
        }
        
        // Move API files
        foreach ($structure['api'] as $file) {
            $this->moveFile($file, 'api/' . basename($file));
        }
        
        // Move configuration files
        foreach ($structure['config'] as $file) {
            $this->moveFile($file, 'config/' . basename($file));
        }
        
        echo "Files organized successfully!\n";
    }
    
    /**
     * Move file from source to destination
     */
    private function moveFile($source, $destination) {
        $sourcePath = $this->rootPath . DIRECTORY_SEPARATOR . $source;
        $destPath = $this->rootPath . DIRECTORY_SEPARATOR . $destination;
        
        if (file_exists($sourcePath) && !file_exists($destPath)) {
            // Create directory if it doesn't exist
            $destDir = dirname($destPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            
            if (rename($sourcePath, $destPath)) {
                echo "Moved: $source -> $destination\n";
            } else {
                echo "Failed to move: $source\n";
            }
        }
    }
    
    /**
     * Generate file manifest
     */
    public function generateManifest() {
        $structure = $this->getFileStructure();
        $manifest = [
            'generated_at' => date('Y-m-d H:i:s'),
            'total_files' => 0,
            'structure' => $structure
        ];
        
        // Count total files
        array_walk_recursive($structure, function($item) use (&$manifest) {
            if (is_string($item)) {
                $manifest['total_files']++;
            }
        });
        
        $manifestFile = $this->rootPath . DIRECTORY_SEPARATOR . 'file_manifest.json';
        file_put_contents($manifestFile, json_encode($manifest, JSON_PRETTY_PRINT));
        
        return $manifest;
    }
    
    /**
     * Clean up unused files
     */
    public function cleanupUnusedFiles() {
        $unusedPatterns = [
            '*~',
            '*.bak',
            '*.tmp',
            'Thumbs.db',
            '.DS_Store',
            '*.log'
        ];
        
        $deleted = [];
        foreach ($unusedPatterns as $pattern) {
            $files = glob($this->rootPath . DIRECTORY_SEPARATOR . '**' . DIRECTORY_SEPARATOR . $pattern, GLOB_BRACE);
            foreach ($files as $file) {
                if (is_file($file) && unlink($file)) {
                    $deleted[] = $file;
                }
            }
        }
        
        return $deleted;
    }
}

// Command line interface
if (php_sapi_name() === 'cli') {
    $fm = new FileManager(__DIR__);
    
    $action = $argv[1] ?? 'help';
    
    switch ($action) {
        case 'organize':
            $fm->createOrganizedStructure();
            $fm->organizeFiles();
            break;
            
        case 'manifest':
            $manifest = $fm->generateManifest();
            echo "Manifest generated with {$manifest['total_files']} files\n";
            break;
            
        case 'cleanup':
            $deleted = $fm->cleanupUnusedFiles();
            echo "Cleaned up " . count($deleted) . " files\n";
            break;
            
        case 'structure':
            $structure = $fm->getFileStructure();
            echo json_encode($structure, JSON_PRETTY_PRINT);
            break;
            
        default:
            echo "Usage: php file_manager.php [organize|manifest|cleanup|structure]\n";
            break;
    }
}
?>