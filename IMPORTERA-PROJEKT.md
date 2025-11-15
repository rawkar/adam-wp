# 🚀 Importera Projekt från assets/projects

## Steg 1: Importera projekt

1. **Gå till WordPress Admin**
2. **Klicka på "Projects" i menyn till vänster**
3. **Klicka på "Import from Assets"** (under Projects-menyn)
4. **Klicka på knappen "Import Projects"**
5. Vänta tills importen är klar

Detta kommer att:
- Skapa WordPress-projekt för varje mapp i `assets/projects`
- Ladda upp alla bilder till WordPress Media Library
- Sätta första bilden som cover image
- Lägga till alla bilder i galleriet

## Steg 2: Rensa cache

Efter importen, gör en **hård refresh** av sidan:
- **Windows/Linux:** `Ctrl + Shift + R`
- **Mac:** `Cmd + Shift + R`

## Steg 3: Kontrollera navigation

Om navigationen inte syns korrekt:

1. **Gå till Theme Settings** (ACF Options)
2. **Lägg till navigation items:**
   - Label: "Work", URL: `/projects` (eller din archive URL)
   - Label: "Contact", URL: `/contact` (eller din contact page URL)
3. **Spara**

## Felsökning

### CSS laddas inte
- Kontrollera i Developer Tools (F12) → Network-fliken
- Se till att `main.css` laddas
- Om den inte laddas, kontrollera att temamappen heter `adam-klingeteg-portfolio`

### Navigation syns inte
- Öppna Developer Tools (F12) → Elements
- Sök efter `#main-navigation`
- Kontrollera att elementet finns och har rätt styling

### Inga projekt syns
- Kontrollera att importen kördes korrekt
- Gå till Projects → All Projects i WordPress Admin
- Se till att projekten är publicerade

### Bilder laddas inte
- Kontrollera att bilderna finns i `assets/projects/[Projektnamn]/`
- Projektnamnet i WordPress måste matcha mappnamnet exakt
- Exempel: Om mappen heter "Blue Billie", måste projektet också heta "Blue Billie"

