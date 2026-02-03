# Soundex Project Management Script
param(
    [Parameter(Mandatory=$false)]
    [ValidateSet("organize", "manifest", "cleanup", "backup", "restore", "status", "help")]
    [string]$Action = "help"
)

$ProjectRoot = Split-Path -Parent (Split-Path -Parent $MyInvocation.MyCommand.Path)
Set-Location $ProjectRoot

function Show-Help {
    Write-Host "(INFO) Soundex Project Management Tool" -ForegroundColor Cyan
    Write-Host "=====================================" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Usage: .\manage-project.ps1 [-Action <action>]" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Available actions:" -ForegroundColor Green
    Write-Host "  organize  - Organize files into proper directory structure" -ForegroundColor White
    Write-Host "  manifest  - Generate file manifest and inventory" -ForegroundColor White
    Write-Host "  cleanup   - Remove temporary and unnecessary files" -ForegroundColor White
    Write-Host "  backup    - Create project backup archive" -ForegroundColor White
    Write-Host "  restore   - Restore project from backup" -ForegroundColor White
    Write-Host "  status    - Show project status and statistics" -ForegroundColor White
    Write-Host "  help      - Display this help message" -ForegroundColor White
    Write-Host ""
}

function Invoke-FileOrganization {
    Write-Host "(FOLDER) Organizing project files..." -ForegroundColor Green
    
    # Define directory structure
    $directories = @(
        "config", "database", "api", "managers", "controllers", "models",
        "frontend/css", "frontend/js", "frontend/images", "frontend/assets",
        "backend", "views", "pages", "utilities", "documentation",
        "uploads/applications", "uploads/products", "uploads/profiles",
        "logs", "cache", "tests", "backups"
    )
    
    # Create directories
    foreach ($dir in $directories) {
        $fullPath = Join-Path $ProjectRoot $dir
        if (!(Test-Path $fullPath)) {
            New-Item -ItemType Directory -Path $fullPath -Force | Out-Null
            Write-Host "  Created: $dir" -ForegroundColor Gray
        }
    }
    
    # Move files to appropriate locations
    $moves = @{
        "db_config.php" = "config"
        "create_tables.php" = "database"
        "insert_sample_data.php" = "database"
        "api.php" = "api"
        "*Manager.php" = "managers"
        "*.css" = "frontend/css"
        "*.js" = "frontend/js"
        "README*.md" = "documentation"
        "*.docx" = "documentation"
        "*.ps1" = "utilities"
    }
    
    foreach ($pattern in $moves.Keys) {
        $destination = $moves[$pattern]
        Get-ChildItem -Path $ProjectRoot -Filter $pattern -File | ForEach-Object {
            $destPath = Join-Path $ProjectRoot $destination $_.Name
            if ($_.FullName -ne $destPath) {
                Move-Item -Path $_.FullName -Destination $destPath -Force
                Write-Host "  Moved: $($_.Name) -> $destination" -ForegroundColor Gray
            }
        }
    }
    
    Write-Host "(SUCCESS) File organization complete!" -ForegroundColor Green
}

function Generate-Manifest {
    Write-Host "(CLIPBOARD) Generating file manifest..." -ForegroundColor Green
    
    $manifest = @{
        project = "Soundex Electronics Store"
        version = "1.0.0"
        generated = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
        stats = @{}
        files = @{}
    }
    
    # Count files by type
    $fileTypes = @("php", "html", "css", "js", "sql", "md", "txt")
    foreach ($type in $fileTypes) {
        $count = (Get-ChildItem -Recurse -Filter "*.$type" -File).Count
        if ($count -gt 0) {
            $manifest.stats["$type files"] = $count
        }
    }
    
    # Directory statistics
    $dirs = Get-ChildItem -Directory | Where-Object { $_.Name -notin @(".git", "node_modules") }
    $manifest.stats.directories = $dirs.Count
    
    # Total file count
    $totalFiles = (Get-ChildItem -Recurse -File | Where-Object { 
        $_.DirectoryName -notlike "*\.git*" -and 
        $_.DirectoryName -notlike "*\node_modules*" 
    }).Count
    $manifest.stats.total_files = $totalFiles
    
    # Save manifest
    $manifestPath = Join-Path $ProjectRoot "project_manifest.json"
    $manifest | ConvertTo-Json -Depth 10 | Out-File -FilePath $manifestPath -Encoding UTF8
    
    Write-Host "(SUCCESS) Manifest generated: $manifestPath" -ForegroundColor Green
    Write-Host "(STATS) Project Statistics:"
    $manifest.stats.GetEnumerator() | ForEach-Object {
        Write-Host "  $($_.Key): $($_.Value)" -ForegroundColor Gray
    }
}

function Invoke-Cleanup {
    Write-Host "(CLEAN) Cleaning up temporary files..." -ForegroundColor Green
    
    $tempPatterns = @(
        "*.tmp", "*.bak", "*~", ".DS_Store", "Thumbs.db", 
        "*.log", "error_log", "*.cache"
    )
    
    $deletedCount = 0
    foreach ($pattern in $tempPatterns) {
        $files = Get-ChildItem -Recurse -Filter $pattern -File
        foreach ($file in $files) {
            Remove-Item -Path $file.FullName -Force
            Write-Host "  Deleted: $($file.Name)" -ForegroundColor Gray
            $deletedCount++
        }
    }
    
    # Clean empty directories
    $emptyDirs = Get-ChildItem -Recurse -Directory | Where-Object {
        (Get-ChildItem -Path $_.FullName -Force | Measure-Object).Count -eq 0
    }
    
    foreach ($dir in $emptyDirs) {
        Remove-Item -Path $dir.FullName -Force
        Write-Host "  Removed empty directory: $($dir.Name)" -ForegroundColor Gray
    }
    
    # Remove duplicate HTML files that have PHP counterparts
    $duplicateHtmlFiles = @(
        "Dhruve.html",
        "Gallery.html", 
        "INTERNSHIP.html",
        "Product1_Gallery.html",
        "Product2_Gallery.html",
        "Product3_Gallery.html",
        "Product4_Gallery.html",
        "Product5_Gallery.html",
        "Product6_Gallery.html",
        "Product7_Gallery.html",
        "Product8_Gallery.html",
        "Untitled-1.html",
        "about.html",
        "booknow.html",
        "buy.html",
        "checkout.html",
        "contact us.html",
        "faqs.html",
        "home.html",
        "home1.html",
        "internship2.html",
        "navigation bar.html",
        "portable speaker.html",
        "product_detail.html",
        "repair.html",
        "se.html",
        "sell.html",
        "services.html",
        "signup.html"
    )
    
    foreach ($file in $duplicateHtmlFiles) {
        $filePath = Join-Path (Join-Path $ProjectRoot "frontend\pages") $file
        if (Test-Path $filePath) {
            Remove-Item -Path $filePath -Force
            Write-Host "  Removed duplicate HTML file: $file" -ForegroundColor Gray
            $deletedCount++
        }
    }
    
    Write-Host "(SUCCESS) Cleanup complete! Removed $deletedCount files." -ForegroundColor Green
}

function Create-Backup {
    Write-Host "(DISK) Creating project backup..." -ForegroundColor Green
    
    $timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
    $backupName = "soundex_backup_$timestamp.zip"
    $backupPath = Join-Path $ProjectRoot "backups" $backupName
    
    # Create backups directory if it doesn't exist
    $backupsDir = Join-Path $ProjectRoot "backups"
    if (!(Test-Path $backupsDir)) {
        New-Item -ItemType Directory -Path $backupsDir -Force | Out-Null
    }
    
    # Create backup excluding unnecessary files
    $excludePaths = @(".git", "node_modules", "vendor", "backups", "*.zip")
    
    Compress-Archive -Path "$ProjectRoot\*" -DestinationPath $backupPath -CompressionLevel Optimal -Force
    
    Write-Host "(SUCCESS) Backup created: $backupPath" -ForegroundColor Green
    
    # List recent backups
    Write-Host ""
    Write-Host "Recent backups:" -ForegroundColor Yellow
    Get-ChildItem -Path $backupsDir -Filter "*.zip" | 
        Sort-Object LastWriteTime -Descending | 
        Select-Object -First 5 | 
        Format-Table Name, Length, LastWriteTime -AutoSize
}

function Show-Status {
    Write-Host "(CHART) Project Status Report" -ForegroundColor Cyan
    Write-Host "========================" -ForegroundColor Cyan
    
    # Git status
    if (Test-Path ".git") {
        Write-Host ""
        Write-Host "Git Status:" -ForegroundColor Yellow
        try {
            $gitStatus = git status --short 2>$null
            if ($gitStatus) {
                Write-Host $gitStatus
            } else {
                Write-Host "Working directory is clean"
            }
        } catch {
            Write-Host "Git command not available or not in a git repository"
        }
    }
    
    # File counts
    Write-Host ""
    Write-Host "File Statistics:" -ForegroundColor Yellow
    $extensions = @("php", "html", "css", "js", "sql")
    foreach ($ext in $extensions) {
        $count = (Get-ChildItem -Recurse -Filter "*.$ext" -File).Count
        if ($count -gt 0) {
            Write-Host "  .$ext files: $count" -ForegroundColor Gray
        }
    }
    
    # Directory sizes
    Write-Host ""
    Write-Host "Directory Sizes:" -ForegroundColor Yellow
    Get-ChildItem -Directory | Where-Object { 
        $_.Name -notin @(".git", "node_modules", "vendor") 
    } | ForEach-Object {
        $size = (Get-ChildItem -Path $_.FullName -Recurse -File | Measure-Object -Property Length -Sum).Sum
        if ($size -gt 0) {
            $sizeMB = [math]::Round($size / 1MB, 2)
            Write-Host "  $($_.Name): $sizeMB MB" -ForegroundColor Gray
        }
    }
    
    # Recent activity
    Write-Host ""
    Write-Host "Recent Changes (Last 7 days):" -ForegroundColor Yellow
    Get-ChildItem -Recurse -File | 
        Where-Object { $_.LastWriteTime -gt (Get-Date).AddDays(-7) } |
        Sort-Object LastWriteTime -Descending |
        Select-Object -First 10 |
        Format-Table Name, LastWriteTime -AutoSize
}

# Main execution
switch ($Action) {
    "organize" { Invoke-FileOrganization }
    "manifest" { Generate-Manifest }
    "cleanup" { Invoke-Cleanup }
    "backup" { Create-Backup }
    "status" { Show-Status }
    "help" { Show-Help }
    default { Show-Help }
}
