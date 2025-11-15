# Snabbstart - Ladda upp WordPress-temat

## ✅ Checklista - Alla filer ska finnas:

```
adam-klingeteg/                    ← Temamappen (skapa denna!)
├── style.css                      ✅ OBLIGATORISK
├── functions.php                  ✅
├── index.php                      ✅ OBLIGATORISK
├── header.php                     ✅
├── footer.php                     ✅
├── front-page.php                 ✅
├── archive-project.php            ✅
├── single-project.php              ✅
├── page-contact.php               ✅
├── inc/
│   ├── custom-post-types.php      ✅
│   ├── acf-fields.php             ✅
│   ├── acf-project-fields.php    ✅
│   ├── acf-options-fields.php    ✅
│   └── helpers.php                ✅
├── template-parts/
│   ├── navigation.php            ✅
│   ├── content.php                ✅
│   └── content-none.php           ✅
└── assets/
    ├── css/
    │   └── main.css               ✅
    └── js/
        └── main.js                ✅
```

## 📦 Steg 1: Skapa temamappen

**Viktigt:** Alla filer måste ligga i en mapp som heter `adam-klingeteg`

1. Skapa en ny mapp som heter `adam-klingeteg`
2. Kopiera ALLA filer från denna mapp till `adam-klingeteg` mappen
3. Se till att mappstrukturen är korrekt (inc/, template-parts/, assets/)

## 📦 Steg 2: Packa ihop till ZIP

1. Högerklicka på `adam-klingeteg` mappen
2. Välj "Send to" → "Compressed (zipped) folder" (Windows)
   ELLER
   Högerklicka → "Compress" (Mac)
3. Du får en fil som heter `adam-klingeteg.zip`

**VIKTIGT:** ZIP-filen ska innehålla `adam-klingeteg` mappen, INTE filerna direkt!

## 🚀 Steg 3: Ladda upp till WordPress

### Alternativ A: Via WordPress Admin (Enklast)

1. Logga in på WordPress Admin
2. Gå till **Appearance → Themes**
3. Klicka **Add New** → **Upload Theme**
4. Välj `adam-klingeteg.zip`
5. Klicka **Install Now**
6. När installationen är klar, klicka **Activate**

### Alternativ B: Via FTP

1. Anslut till din server via FTP (FileZilla, etc.)
2. Navigera till `/wp-content/themes/`
3. Ladda upp hela `adam-klingeteg` mappen
4. Gå till WordPress Admin → Appearance → Themes
5. Aktivera "Adam Klingeteg Portfolio"

## ⚙️ Steg 4: Installera ACF Plugin

**OBLIGATORISKT:** Temat kräver Advanced Custom Fields

1. Gå till **Plugins → Add New**
2. Sök efter "Advanced Custom Fields"
3. Installera och aktivera
4. (Valfritt) Installera ACF Pro för repeater-fält

## 🎨 Steg 5: Konfigurera

1. **Skapa projekt:**
   - Gå till **Projects → Add New**
   - Fyll i titel, år, beskrivning, bilder
   - Publicera

2. **Theme Settings:**
   - Gå till **Theme Settings** (ACF Options)
   - Konfigurera navigation och kontaktinfo

3. **Contact-sida:**
   - Skapa en sida med slug `contact`

## ✨ Klart!

Ditt tema är nu installerat och redo att användas!

---

## 🔧 Om något inte fungerar:

1. **Tema syns inte:** Kontrollera att `style.css` finns i temamappens rot
2. **ACF-fält saknas:** Installera ACF-plugin
3. **Projekt visas inte:** Kontrollera att Custom Post Type är registrerad
4. **Bilder visas inte:** Kontrollera att bilder är uppladdade via Media Library

Se `INSTALLATION.md` för detaljerad felsökning.

