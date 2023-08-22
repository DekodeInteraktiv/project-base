# Theme icons

There are basically two alternative ways to use a custom icon set in your
theme or project:

1. Create a custom icon font from svg icons.
2. Use svg icons with css mask.

## Alternative 1. Create a custom icon font from svg icons

The theme icon font is **deactivated** by default. You activate it by
running `npm run icon-font`. With the generated font files present,
the Starter theme will **switch** from svg icons to using the the icon font.

### Setup and activation

1. Add your original icons as svg files to the theme icon folder: [assets/icons/original](assets/icons/original).
2. Naming is important! Base the names on [T2 names](https://t2.teft.io/packages/icons.html)
   whenever possible.
3. Use hyphen-separated lower case letters for the svg file names (aka kebab-case).
   They will automatically be converted to camel case before they are added to T2, like

```
add.svg
arrow-forward.svg
arrow-outward.svg
```

4. Convert the original svg icons from `stroke` to `fill` with this online tool:
   [https://iconly.io/tools/svg-convert-stroke-to-fill](https://iconly.io/tools/svg-convert-stroke-to-fill).
   It supports batches of up to 50 svg files.
5. Unpack the converted svg files in [assets/icons](assets/icons).
6. From the command line, run the script `npm run icon-font`.
7. Check that the icon font folder `assets/fonts/theme-icons` has been generated.
8. Add all files (approx 12) in the icon font folder to the project **git repository**.

### Deactivate the theme icon font

1. Delete the complete theme icons font folder `assets/fonts/theme-icons`.
2. Commit the deleted files to the project **git repository**.

### Usage

Open [assets/fonts/theme-icons/index.html](assets/fonts/theme-icons/index.html) in a browser to get an overview
of all **icons** and their **names**.

For **accessibility**, do NOT display icons without any **contextual** information for
screen readers. A button text or menu item text provide sufficient context.

#### CSS

You typically add an icon to a pseudo-element in css.
Always use **css variables** instead of literal glyphs. When you add or remove
icons from the icon font, the glyphs may change, but the **variable name** will
stay the same.

In addition to `content`, you must also add `font-family: theme-icons;`
to the pseudo element and tell screen readers that the icons should be ignored
with `speak: never;` (mostly unsupported).

    ::before {
    	content: var(--theme-icons-arrow-forward, "");
    	font-family: theme-icons !important;
    	speak: never;
    }

#### HTML

To display an icon in html, just insert a tag with a `theme-icons-{icon-name}` class name, i.e.

    <i class="theme-icons-arrow-forward"></i>

#### PHP

All font icons are automatically added to [T2 Icons](https://t2.teft.io/packages/icons.html).
In addition to displaying icons as plain html (see above), you also can output SVGs
directly with the T2 icon functions `T2\Icons\icon()` and `T2\Icons\get_icon()`, i.e.

    T2\Icons\icon( 'arrowForward' );
    echo T2\Icons\get_icon( 'arrowForward' );

Please note that the fist parameter is the icon name in **camel case**.

#### T2 blocks

Many T2 blocks use T2 Icons for displaying icons. With correct **naming**
of the svg icon files (step 3 above), you can let T2 display your custom icon
set by overriding the default T2 icons.

### Tech info

-   The total **woff2** font file size with all 27 Starter theme **icons** is **5KB**.
    It`s approx 1/3 of the size of all svg files combined.
-   It's **compressed** and **cacheable** and only needs to be loaded **once**.
-   The icon font is **preloaded** and uses `font-display: block;` to prevent
    FOIT (Flash of Invisible Text) and FOUT (Flash of Unstyled Text) issues.
-   The icon font is loaded with a **timestamp** query string, just like
    other css and js in your project. When you regenerate an icon font, the
    most resent version will be loaded even if it's cached.
-   We are using `css variables` based on the icon svg **file name** for the `content`
    css property in pseudo-elements. Even if the literal glyphs may change when the
    icon font is regenerated, css variable will stay the same.
-   With **consistent** svg file names, you can replace the theme icon set without
    changing any code.

## Alternative 2. Use svg icons with css mask

SVG icons are **activated** by default. They are automatically **deactivated**
when the theme icon font is activated.

### Setup

1. Add your original icons as svg files to the theme icon folder: [assets/icons/original](assets/icons/original).
2. Naming is important! Base the names on [T2 names](https://t2.teft.io/packages/icons.html)
   whenever possible.
3. Use hyphen-separated lower case letters for the svg file names (aka kebab-case), like

```
add.svg
arrow-forward.svg
arrow-outward.svg
```

4. Check that the source svg icons are suitable for use as svg icons.
   You might need to manually add `fill="none"` and `stroke="currentColor"`
   to some svg elements.
5. Copy the svg icons (including the root element) into `css variables`
   in [src/icons.css](src/icons.css). The svg icons must be url or base64 **encoded**.
   Use hyphen-separated lower case letters for the css variables (aka kebab-case)
   and prefix them with `--theme-svg-.`

```
--theme-svg-arrow-forward: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24'%3E%3Cpath d='m20.78125 12.53125-6.75 6.75c-.292969.292969-.769531.292969-1.0625 0-.292969-.292969-.292969-.769531 0-1.0625L18.441406 12.75H3.75c-.414062 0-.75-.335938-.75-.75s.335938-.75.75-.75h14.691406L12.96875 5.78125c-.292969-.292969-.292969-.769531 0-1.0625.292969-.292969.769531-.292969 1.0625 0l6.75 6.75C20.921875 11.609375 21 11.800781 21 12c0 .199219-.078125.390625-.21875.53125Zm0 0'/%3E%3C/svg%3E");
--theme-svg-close: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24'%3E%3Cpath d='m12 13.4-4.9 4.9c-.2.2-.4.3-.7.3-.3 0-.5-.1-.7-.3-.2-.2-.3-.4-.3-.7s.1-.5.3-.7l4.9-4.9-4.9-4.9c-.2-.2-.3-.4-.3-.7 0-.3.1-.5.3-.7.2-.2.4-.3.7-.3.3 0 .5.1.7.3l4.9 4.9 4.9-4.9c.2-.2.4-.3.7-.3s.5.1.7.3c.2.2.3.4.3.7 0 .3-.1.5-.3.7L13.4 12l4.9 4.9c.2.2.3.4.3.7s-.1.5-.3.7c-.2.2-.4.3-.7.3s-.5-.1-.7-.3L12 13.4z'/%3E%3C/svg%3E");
--theme-svg-facebook: url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE4IDFIMTQuNDU0NUMxMi44ODc0IDEgMTEuMzg0NCAxLjU3OTQ2IDEwLjI3NjIgMi42MTA5MUM5LjE2ODAyIDMuNjQyMzYgOC41NDU0NSA1LjA0MTMxIDguNTQ1NDUgNi41VjkuOEg1VjE0LjJIOC41NDU0NVYyM0gxMy4yNzI3VjE0LjJIMTYuODE4MkwxOCA5LjhIMTMuMjcyN1Y2LjVDMTMuMjcyNyA2LjIwODI2IDEzLjM5NzIgNS45Mjg0NyAxMy42MTg5IDUuNzIyMThDMTMuODQwNSA1LjUxNTg5IDE0LjE0MTEgNS40IDE0LjQ1NDUgNS40SDE4VjFaIiBzdHJva2U9IiMyMjIwOUEiIHN0cm9rZS13aWR0aD0iMS41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPC9zdmc+Cg==");
```

6. Also in in [src/icons.css](src/icons.css), create **css classes** for each icon.
   They can later be used to insert icons in pure html.
   Use hyphen-separated lower case letters for the css classes (aka kebab-case)
   and prefix them with `--theme-icons-.`

```
.theme-icons-arrow-forward::before {
	mask: var(--theme-svg-arrow-forward) 0 / cover;
}
```

7. Also copy the svg icons into a **PHP array** in the `import_svg_icons()` function
   in [inc/icons.php](inc/icons.php). Only copy the **inner elements** (excluding the svg root
   element) and use **camel case** for array key names.

```
$icons['arrowForward'] = '<path d="M4 12L19 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 19L19 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 5L19 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>';
$icons['close']        = '<path d="M6.34326 17.6569L17.657 6.34315" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.34375 6.34302L17.6575 17.6567" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>';
$icons['menu']         = '<path d="M4 12H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 18H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 6H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>';
```

### Usage

For **accessibility**, do NOT display icons without any **contextual** information for
screen readers. A button text or menu item text provide sufficient context.

#### CSS

You typically add an icon to a pseudo-element in css.
Always use the **css variables** that you created during setup (step 5).
In addition to the required `content`, `mask` and `background-color` css properties,
you'll probably need to add `display`, `width` and `height` as well.

    ::before {
    	background-color: var(--theme--color--svg);
    	content: "";
    	display: inline-block;
    	height: 1em;
    	mask: var(--theme-svg-facebook) 0 / cover;
    	vertical-align: middle;
    	width: 1em;
    }

#### HTML

To display an icon in html, just insert a tag with a `theme-icons-{icon-name}` class name, i.e.

    <i class="theme-icons-arrow-forward"></i>

#### PHP

During setup, you should have added all necessary font
to [T2 Icons](https://t2.teft.io/packages/icons.html) (step 6).
You can now output SVGs directly with the T2 icon functions `T2\Icons\icon()`
and `T2\Icons\get_icon()`, i.e.

    T2\Icons\icon( 'arrowForward' );
    echo T2\Icons\get_icon( 'arrowForward' );

Please note that the first parameter is the icon name in **camel case**.

#### T2 blocks

Many T2 blocks use T2 Icons for displaying icons. With correct **naming** of
the array keys (step 7 above), you can let T2 display your custom icon
set by overriding the default T2 icons.

## Resources

-   Icon handling logic is located in [inc/icons.php](inc/icons.php).
-   This is also where icons are added to [T2 Icons](https://t2.teft.io/packages/icons.html),
    automatically (icon font) or manually (svg icons).
-   Svg icons for css are located in [src/icons.css](src/icons.css).
-   Icon fonts are generated with [SVG to Font](https://github.com/jaywcjlove/svgtofont)
-   SVGs are converted with [Convert SVG Strokes to Fills](https://iconly.io/tools/svg-convert-stroke-to-fill).
-   Online tool for url encoding: https://www.urlencoder.org/
-   Online tool for base64 encoding: https://www.base64encode.org/
