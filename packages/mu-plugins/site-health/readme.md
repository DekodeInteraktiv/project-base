# Site Health Quality Assurance

The Site Health feature in WordPress is great for indicating problems with a site, but depending on your setup, can also provide some false-positive results that are unexpected or unwanted.

This introduces the concept of a MU plugin that tackles this by removing tests with known false-positives relating to updates, plugins and themes, which are all managed via version control and Composer, and more.
