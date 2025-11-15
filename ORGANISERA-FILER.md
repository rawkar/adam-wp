# 📁 Organisera WordPress-temafiler

## ⚠️ Viktigt: Alla temafiler måste ligga i en egen mapp!

För att WordPress ska känna igen temat måste alla filer ligga i en mapp med temanamnet.

## 🎯 Steg-för-steg instruktioner:

### Steg 1: Skapa temamappen

1. I File Explorer, högerklicka i "Adam WP" mappen
2. Välj **New → Folder**
3. Döp mappen till: `adam-klingeteg`

### Steg 2: Flytta alla WordPress-temafiler

Flytta följande filer och mappar till `adam-klingeteg` mappen:

**Filer att flytta:**
- ✅ `style.css`
- ✅ `functions.php`
- ✅ `index.php`
- ✅ `header.php`
- ✅ `footer.php`
- ✅ `front-page.php`
- ✅ `archive-project.php`
- ✅ `single-project.php`
- ✅ `page-contact.php`

**Mappar att flytta:**
- ✅ `inc/` (hela mappen)
- ✅ `template-parts/` (hela mappen)
- ✅ `assets/` (hela mappen)

### Steg 3: Kontrollera strukturen

Efter flyttning ska `adam-klingeteg` mappen se ut så här:

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

### Steg 4: Packa ihop till ZIP

1. Högerklicka på `adam-klingeteg` mappen
2. Välj **Send to → Compressed (zipped) folder**
3. Du får en fil: `adam-klingeteg.zip`

### Steg 5: Ladda upp till WordPress

1. Gå till WordPress Admin → **Appearance → Themes**
2. Klicka **Add New** → **Upload Theme**
3. Välj `adam-klingeteg.zip`
4. Klicka **Install Now**
5. Klicka **Activate**

## ✅ Klart!

Nu är temat installerat och redo att användas!

---

## 💡 Tips:

- **Lämna kvar:** `Adam-Klingeteg/` mappen (det är Next.js-projektet)
- **Lämna kvar:** README-filerna (de behövs inte i temat)
- **Flytta INTE:** `.gitignore` eller andra konfigurationsfiler

## 🔍 Verifiera:

Efter flyttning ska du ha:
- `adam-klingeteg/` mappen med alla temafiler
- `Adam-Klingeteg/` mappen (Next.js-projektet) kvar i rotmappen
- README-filerna kvar i rotmappen

