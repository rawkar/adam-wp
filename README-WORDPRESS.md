# Adam Klingeteg Portfolio - WordPress Theme

A modern, artistic portfolio WordPress theme converted from Next.js, featuring edgy interactions, smooth animations, and a visual-first experience with full ACF integration for editable content.

## Features

- **Full ACF Integration**: All content is editable through WordPress admin panel
- **Custom Post Type**: Projects with custom fields (year, description, gallery, tags)
- **Responsive Design**: Optimized for all devices with responsive mosaic grid
- **Modern Typography**: Roboto and Tenor Sans fonts
- **Smooth Animations**: CSS transitions and hover effects
- **Lightbox Gallery**: Image lightbox for project galleries
- **Mobile Menu**: Responsive navigation with mobile menu
- **ACF Options Page**: Global settings for navigation and contact information

## Requirements

- WordPress 6.0 or higher
- PHP 8.0 or higher
- Advanced Custom Fields (ACF) plugin (free version works, Pro recommended for repeater fields)

## Installation

1. Upload the theme folder to `/wp-content/themes/`
2. Activate the theme through the 'Appearance' menu in WordPress
3. Install and activate the Advanced Custom Fields plugin
4. Go to **Projects** in the admin menu to add your first project
5. Configure global settings in **Theme Settings** (ACF Options Page)

## Theme Structure

```
adam-klingeteg/
├── style.css                 # Theme header and main stylesheet
├── functions.php              # Theme functions and setup
├── index.php                  # Main template
├── header.php                 # Header template
├── footer.php                 # Footer template
├── front-page.php             # Homepage template
├── archive-project.php        # Projects archive template
├── single-project.php         # Single project template
├── page-contact.php           # Contact page template
├── inc/
│   ├── custom-post-types.php  # Custom Post Type registration
│   ├── acf-fields.php         # ACF setup
│   ├── acf-project-fields.php # ACF fields for projects
│   ├── acf-options-fields.php # ACF fields for options page
│   └── helpers.php            # Helper functions
├── template-parts/
│   └── navigation.php        # Navigation component
└── assets/
    ├── css/
    │   └── main.css          # Main stylesheet
    └── js/
        └── main.js           # Main JavaScript
```

## Usage

### Adding Projects

1. Go to **Projects > Add New** in WordPress admin
2. Enter project title (required)
3. Fill in ACF fields:
   - **Year**: Project completion year
   - **Description**: Brief project description
   - **Cover Image**: Image shown in project grids
   - **Gallery**: Multiple images for project detail page
   - **Featured**: Check to feature on homepage
4. Add **Project Tags** (taxonomy)
5. Publish the project

### Configuring Navigation

1. Go to **Theme Settings** in WordPress admin
2. Under **Navigation**, add menu items:
   - **Label**: Menu item text
   - **URL**: Link destination
3. Save changes

### Configuring Contact Information

1. Go to **Theme Settings** in WordPress admin
2. Under **Contact Information**, fill in:
   - **Name**: Your name
   - **Title**: Your title/role
   - **Email**: Contact email
   - **Phone**: Contact phone
   - **Instagram URL**: Instagram profile URL
3. Save changes

### Creating Contact Page

1. Create a new page in WordPress
2. Set the page slug to `contact`
3. The theme will automatically use `page-contact.php` template

## Customization

### Colors

Edit CSS variables in `assets/css/main.css`:

```css
:root {
  --background: #000000;
  --foreground: #ffffff;
  --accent: #635a5a;
  --secondary: #4ecdc4;
  --muted: #666666;
}
```

### Fonts

The theme uses Google Fonts (Roboto and Tenor Sans). To change fonts, edit:
- `functions.php`: Update Google Fonts URL
- `assets/css/main.css`: Update `--font-sans` and `--font-heading` variables

### Project Archive URL

The project archive is available at `/projects/` by default. To change:
1. Go to **Settings > Permalinks**
2. The custom post type uses the slug `project`

## ACF Field Structure

### Project Fields

- `year` (text): Project year
- `description` (textarea): Project description
- `cover_image` (image): Cover image for grids
- `gallery` (gallery): Project image gallery
- `featured` (true/false): Feature on homepage

### Options Page Fields

- `navigation_items` (repeater): Navigation menu items
  - `label` (text): Menu item label
  - `url` (url): Menu item URL
- `contact_name` (text): Contact name
- `contact_title` (text): Contact title
- `contact_email` (email): Contact email
- `contact_phone` (text): Contact phone
- `contact_instagram` (url): Instagram URL

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Development

### File Organization

- **Templates**: Root level PHP files (front-page.php, single-project.php, etc.)
- **Template Parts**: Reusable components in `template-parts/`
- **Functions**: Theme functionality in `inc/`
- **Assets**: CSS and JavaScript in `assets/`

### Adding New Features

Follow the WordPress and ACF methodology:
1. Create ACF fields in `inc/acf-*.php`
2. Use helper functions from `inc/helpers.php`
3. Always include fallback values
4. Sanitize input and escape output

## Support

For issues or questions, please refer to the original Next.js project or create an issue in the repository.

## License

MIT License - Same as original Next.js project

