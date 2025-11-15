# PowerShell-skript för att organisera WordPress-temafiler
# Kör detta skript i "Adam WP" mappen

Write-Host "Organiserar WordPress-temafiler..." -ForegroundColor Green

# Skapa temamapp
$themeFolder = "adam-klingeteg-portfolio"
if (Test-Path $themeFolder) {
    Write-Host "Mappen $themeFolder finns redan. Tar bort den först..." -ForegroundColor Yellow
    Remove-Item -Path $themeFolder -Recurse -Force
}

New-Item -ItemType Directory -Path $themeFolder | Out-Null
Write-Host "Skapade mapp: $themeFolder" -ForegroundColor Green

# Lista över filer att flytta
$filesToMove = @(
    "style.css",
    "functions.php",
    "index.php",
    "header.php",
    "footer.php",
    "front-page.php",
    "archive-project.php",
    "single-project.php",
    "page-contact.php"
)

# Flytta filer
$movedCount = 0
foreach ($file in $filesToMove) {
    if (Test-Path $file) {
        Move-Item -Path $file -Destination $themeFolder -Force
        Write-Host "  Flyttade: $file" -ForegroundColor Cyan
        $movedCount++
    }
}

# Flytta mappar
$foldersToMove = @("inc", "template-parts", "assets")
foreach ($folder in $foldersToMove) {
    if (Test-Path $folder) {
        Move-Item -Path $folder -Destination $themeFolder -Force
        Write-Host "  Flyttade mapp: $folder/" -ForegroundColor Cyan
    }
}

Write-Host "`nKlart! $movedCount filer flyttade till $themeFolder/" -ForegroundColor Green
Write-Host "`nNästa steg:" -ForegroundColor Yellow
Write-Host "1. Packa ihop $themeFolder mappen till ZIP" -ForegroundColor White
Write-Host "2. Ladda upp ZIP-filen till WordPress" -ForegroundColor White

