# Installation av Adam Klingeteg WordPress-tema

## Steg 1: Förbered temamappen

Alla temafiler ska ligga i en mapp som heter `adam-klingeteg` (eller valfritt namn).

**Struktur:**
```
adam-klingeteg/
├── style.css
├── functions.php
├── index.php
├── header.php
├── footer.php
├── front-page.php
├── archive-project.php
├── single-project.php
├── page-contact.php
├── inc/
│   ├── custom-post-types.php
│   ├── acf-fields.php
│   ├── acf-project-fields.php
│   ├── acf-options-fields.php
│   └── helpers.php
├── template-parts/
│   ├── navigation.php
│   ├── content.php
│   └── content-none.php
└── assets/
    ├── css/
    │   └── main.css
    └── js/
        └── main.js
```

## Steg 2: Ladda upp till WordPress

### Metod 1: Via FTP/SFTP (Rekommenderat)

1. **Packa ihop temamappen:**
   - Skapa en ZIP-fil av hela `adam-klingeteg` mappen
   - ZIP-filen ska innehålla alla filer OCH mappar

2. **Ladda upp via FTP:**
   - Anslut till din WordPress-server via FTP/SFTP
   - Navigera till `/wp-content/themes/`
   - Ladda upp hela `adam-klingeteg` mappen (eller packa upp ZIP-filen där)

3. **Aktivera temat:**
   - Gå till WordPress Admin → Appearance → Themes
   - Hitta "Adam Klingeteg Portfolio" och klicka "Activate"

### Metod 2: Via WordPress Admin (Om ZIP är mindre än 2MB)

1. **Skapa ZIP-fil:**
   - Packa ihop hela `adam-klingeteg` mappen till en ZIP-fil

2. **Ladda upp:**
   - Gå till WordPress Admin → Appearance → Themes
   - Klicka "Add New" → "Upload Theme"
   - Välj ZIP-filen och klicka "Install Now"
   - Klicka "Activate" när installationen är klar

## Steg 3: Installera Advanced Custom Fields (ACF)

Temat kräver ACF-plugin för att fungera korrekt:

1. Gå till WordPress Admin → Plugins → Add New
2. Sök efter "Advanced Custom Fields"
3. Installera och aktivera plugin:et
4. (Valfritt) Installera ACF Pro för repeater-fält (krävs för navigation repeater)

## Steg 4: Konfigurera temat

### Skapa projekt

1. Gå till **Projects → Add New** i WordPress admin
2. Fyll i:
   - **Title**: Projektets namn
   - **Year**: År (ACF-fält)
   - **Description**: Beskrivning (ACF-fält)
   - **Cover Image**: Omslagsbild (ACF-fält)
   - **Gallery**: Bildgalleri (ACF-fält)
   - **Featured**: Markera för att visa på startsidan
3. Lägg till **Project Tags** (taxonomy)
4. Publicera projektet

### Konfigurera Theme Settings

1. Gå till **Theme Settings** i WordPress admin (ACF Options Page)
2. Under **Navigation**:
   - Lägg till menyobjekt med Label och URL
3. Under **Contact Information**:
   - Fyll i namn, titel, email, telefon, Instagram URL

### Skapa Contact-sida

1. Gå till **Pages → Add New**
2. Skapa en sida med titeln "Contact"
3. Sätt permalink/slug till `contact`
4. Lämna innehållet tomt (temat använder `page-contact.php`)

## Steg 5: Verifiera installation

Kontrollera att följande fungerar:
- ✅ Startsidan visar projektgrid
- ✅ Projektarkiv på `/projects/`
- ✅ Enskilda projekt fungerar
- ✅ Kontaktsidan fungerar
- ✅ Navigation fungerar
- ✅ ACF-fält syns i admin

## Felsökning

### Tema visas inte i Themes-listan
- Kontrollera att `style.css` finns i temamappens rot
- Kontrollera att temamappen ligger i `/wp-content/themes/`
- Kontrollera att `style.css` har korrekt temaheader

### ACF-fält syns inte
- Kontrollera att ACF-plugin är installerat och aktiverat
- Gå till Custom Fields i admin och kontrollera att fältgrupper finns
- Om fält inte syns, gå till Custom Fields → Field Groups och synkronisera

### Projekt visas inte
- Kontrollera att Custom Post Type är registrerad (gå till Projects i admin)
- Kontrollera att projekt är publicerade
- Kontrollera permalinks: Settings → Permalinks → Save Changes

### Bilder visas inte
- Kontrollera att bilder är uppladdade via WordPress Media Library
- Kontrollera att ACF Gallery-fält är korrekt konfigurerat
- Kontrollera filrättigheter på servern

## Support

Om du stöter på problem, kontrollera:
1. WordPress-version (kräver 6.0+)
2. PHP-version (kräver 8.0+)
3. ACF-plugin är installerat
4. Temamappen har korrekt struktur

