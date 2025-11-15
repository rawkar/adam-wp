# 🎯 Flytta WordPress-temafiler till rätt mapp

## ⚠️ Viktigt problem:

Det finns redan en mapp som heter `adam-klingeteg` som innehåller Next.js-projektet. 

## ✅ Lösning - Välj ett av alternativen:

### Alternativ 1: Skapa ny mapp med annat namn (Rekommenderat)

1. Skapa en **ny mapp** som heter: `adam-klingeteg-portfolio` eller `adam-klingeteg-wp`
2. Flytta alla WordPress-temafiler till den nya mappen
3. Packa ihop den nya mappen till ZIP

### Alternativ 2: Döp om Next.js-mappen först

1. Döp om `adam-klingeteg` mappen till `adam-klingeteg-nextjs`
2. Skapa en **ny mapp** som heter: `adam-klingeteg`
3. Flytta alla WordPress-temafiler till den nya `adam-klingeteg` mappen

---

## 📋 Filer att flytta till temamappen:

**Alla dessa filer ligger just nu i "Adam WP" mappen och ska flyttas:**

### PHP-filer:
- `style.css` ⭐ (OBLIGATORISK - måste ligga i temamappen!)
- `functions.php`
- `index.php` ⭐ (OBLIGATORISK)
- `header.php`
- `footer.php`
- `front-page.php`
- `archive-project.php`
- `single-project.php`
- `page-contact.php`

### Mappar:
- `inc/` (hela mappen med alla PHP-filer)
- `template-parts/` (hela mappen)
- `assets/` (hela mappen med css/ och js/)

---

## 🎯 Snabbguide:

1. **Skapa mapp:** `adam-klingeteg-portfolio` (eller valfritt namn)
2. **Markera alla filer ovan** i File Explorer
3. **Dra och släpp** dem i den nya mappen
4. **Kontrollera** att strukturen är korrekt
5. **Packa ihop** mappen till ZIP
6. **Ladda upp** till WordPress

---

## ✅ Efter flyttning ska temamappen innehålla:

```
adam-klingeteg-portfolio/  (eller ditt valda namn)
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

---

## 🚀 När filerna är flyttade:

1. Högerklicka på temamappen
2. Välj "Compress" eller "Send to → Compressed folder"
3. Du får en ZIP-fil
4. Ladda upp ZIP-filen till WordPress via Admin → Appearance → Themes → Upload Theme

---

**OBS:** Lämna `Adam-Klingeteg/` mappen (Next.js-projektet) kvar där den är!

