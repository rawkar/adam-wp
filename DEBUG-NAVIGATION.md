# Debug Navigation Problem

## Problem
Navigationen hamnar mitt på sidan istället för i headern, och loggan syns inte.

## Lösningar att testa:

### 1. Kontrollera att navigationen renderas i header.php
Öppna `header.php` och kontrollera att den innehåller:
```php
<?php get_template_part('template-parts/navigation'); ?>
```

### 2. Kontrollera i Developer Tools
1. Öppna Developer Tools (F12)
2. Gå till Elements/Inspector
3. Sök efter `#main-navigation`
4. Kontrollera:
   - Finns elementet?
   - Har det `position: fixed`?
   - Har det `top: 0` eller `top: 32px`?
   - Var i DOM-trädet ligger det? (ska vara direkt efter `<body>`)

### 3. Kontrollera CSS-laddning
1. Gå till Network-fliken i Developer Tools
2. Ladda om sidan
3. Sök efter `main.css`
4. Kontrollera att den laddas (status 200)

### 4. Testa inline styles
Navigationen har nu inline styles direkt i HTML:en, så den ska fungera även om CSS inte laddas.

### 5. Kontrollera WordPress admin bar
Om WordPress admin bar är synlig, ska navigationen ligga under den (top: 32px).

### 6. Rensa cache
- Tryck `Ctrl + Shift + R` (Windows) eller `Cmd + Shift + R` (Mac)
- Eller öppna i incognito/private window

### 7. Kontrollera att temamappen är korrekt
Temamappen ska heta `adam-klingeteg-portfolio` och ligga i:
`wp-content/themes/adam-klingeteg-portfolio/`

### 8. Kontrollera att temat är aktiverat
Gå till WordPress Admin → Appearance → Themes och kontrollera att "Adam Klingeteg Portfolio" är aktiverat.

