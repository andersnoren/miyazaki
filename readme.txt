=== Miyazaki ===
Contributors: Anlino
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=anders%40andersnoren%2ese&lc=US&item_name=Free%20WordPress%20Themes%20from%20Anders%20Noren&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Requires at least: 4.5
Requires PHP: 5.4
Tested up to: 6.0
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html


== Installation ==

1. Upload the theme
2. Activate the theme


== Change pagination type ==

1. Log in to the administration panel of your site.
2. Go to Appearance → Customize.
3. Click the "Pagination" panel.
4. There are three options for the pagination:
	a. "Load more on button click": Displays a button that, when clicked, loads more posts without a hard reload.
	b. "Load more on scroll": When the visitor has reached the bottom of the page, more posts are loaded without a hard reload.
	c. "Previous and next page links": Displays links that, when clicked, takes the visitor to then next or previous archive page with a hard reload.
5. Select the type you want to use, and click the blue "Publish" button to save your changes.


== Licenses ==

Charis SIL font
License: SIL Open Font License, 1.1, https://opensource.org/licenses/OFL-1.1
Source: https://fonts.google.com/specimen/Charis+SIL

Teko font
License: SIL Open Font License, 1.1, https://opensource.org/licenses/OFL-1.1
Source: https://fonts.google.com/specimen/Teko

Custom Made Icons and Fallback Image
License: GPLv2, https://www.gnu.org/licenses/gpl-2.0.html

Images in screenshot.png from Pixabay
License: Creative Commons Zero (CC0), https://creativecommons.org/publicdomain/zero/1.0/
Source: https://www.pixabay.com/
Images (top left to bottom right):
- Polygon Rabbit: https://pixabay.com/sv/små-poly-djur-vektor-konst-polygon-3350170/
- Polygon Owl: https://www.pexels.com/photo/architecture-building-cars-pavement-169572/
- Polygon Deer: https://pixabay.com/sv/hjort-polygoner-konst-design-natur-3275594/
- Polygon Dog: https://pixabay.com/sv/hund-illustration-bakgrund-porträtt-3275593/


== Changelog ==

Version 2.2 (2022-10-05)
-------------------------
- JS: Updated the smoothScroll to make the mobile menu support anchor links on the same page.

Version 2.1.2 (2022-09-19)
-------------------------
- Fixed the site header overlapping the WordPress admin bar.

Version 2.1.1 (2022-07-01)
-------------------------
- Improved fonts.css enqueue for child themes.

Version 2.1 (2021-06-29)
-------------------------
- Switched from the Google Fonts CDN to font files included in the theme folder.
- Replaced Charis SIL font files with versions from Google Fonts for reduced file size.
- Fixed alignwide element width on large screens.
- Updated "Tested up to" to 6.0.
- Removed www prefix from andersnoren.se URLs.

Version 2.0.4 (2020-09-12)
-------------------------
- Fixed the post meta controls in the Customizer not working since WordPress 5.5.

Version 2.0.3 (2020-08-12)
-------------------------
- Added escaping of `get_template_directory_uri()`.
- JS: Fixed compatibility with WordPress 5.5 by replacing `live()` with `on()`.
- Updated "Tested up to".

Version 2.0.2 (2020-06-05)
-------------------------
- Removed a reference to Chaplin in a comment in `construct.js`. 
- Fixed incorrect HTML closing element comment in `pagination.php`.
- Fixed navigation toggle being misaglined on mobile in Chrome (thanks, @antomal).
- Bumped "Tested up to" to 5.4.1.
- Added "Requires PHP" to readme.txt.
- Added "Requires PHP" and "Tested up to" to style.css, per new theme requirements.

Version 2.0.1 (2020-04-30)
-------------------------
- CSS: Updated Block Editor font size class targeting to be consistent across media queries.

Version 2.0.0 (2020-04-08)
-------------------------
- Moved editor style files to the `/assets/css/` folder.
- Moved classes from `functions.php` to the `/inc/classes/` folder.
- Block editor styles: Fixed targeting of headings.
- Block editor styles: Fixed button links having the wrong font due to markup changes in core.
- Updated Firefox text antialiasing to better match styling in Webkit/Blink browsers.
- Added a hover effect to the load more button.
- Changed "Tested up to" to 5.4.
- Restructured block CSS, added base block margins.
- Added hover effect to post meta links.
- Updated Button: Style Outline to match the footprint of a regular button.
- 5.4 Updates: Fixed social block style.
- 5.4 Updates: Calendar widget styles.
- General cleanup in `style.css`.
- Updated `style.css` TOC with the correct numbers of the sections in the CSS.
- Condensed widget area registration code.
- Check if widget area has widgets before outputting widget area wrapper element in the footer.
- Removed the "Comments are closed" message previously displayed when comments are closed on a post.

Version 1.10 (2020-02-15)
-------------------------
- Fixed a layout issue with Masonry
- Updated sub menus so they can be navigated with focus only
- Added a clearing block to the CSS
- Updated register sidebar args to include the ID of the widgets, and to make widget titles H2 elements
- Changed screenshot.png to a JPG file, reducing file size by two thirds
- Compressed the default fallback image
- Updated translateable strings to not include special characters
- Updated "Tested up to" to 5.3.2

Version 1.09 (2019-07-17)
-------------------------
- Fixed the wrong text domain being used for the skip link

Version 1.08 (2019-07-17)
-------------------------
- Added theme URI to style.css
- Updated "Tested up to"
- Added theme tags
- Added skip link
- Removed removal of focus outline
- Don't show comments if the post is password protected
- Don't show the post thumbnail if the post is password protected
- Fixed font issues in the block editor styles

Version 1.07 (2019-06-08)
-------------------------
- Updated updateHistory() to work with permalink structure without an ending slash
- Explicitly set links inside figcaption/.wp-caption-text to inline
- Spelling error in readme docs
- Added demo link to theme description

Version 1.06 (2019-04-07)
-------------------------
- Added the new wp_body_open() function, along with a function_exists check

Version 1.05 (2019-03-22)
-------------------------
- Added a Customizer option for hiding the search output in the header/mobile menu

Version 1.04 (2019-01-08)
-------------------------
- Fixed alignright pullquotes having the wrong width on large displays

Version 1.03 (2019-01-08)
-------------------------
- Fixed the front page title preventing line breaks

Version 1.02 (2018-12-26)
-------------------------
- Adjusted styling of content elements
- Added a search form to the menu overlay

Version 1.01 (2018-12-23)
-------------------------
- Improved display of blocks following titles
- Fixed pagination being displayed on pages

Version 1.00 (2018-12-23)
-------------------------
- Initial version