# Remote Images

This is a simple extension that allows you to prepend remote domains to media asset URLs. Allowing local sites to use remote image assets.

Example configation: define( 'DEKODE_PREPEND_IMAGE_URL', 'https://productions-site.com/' );
Put this in your environment file.

Other configuration options:

DEKODE_PREPEND_CONTENT_FOLDER (defaults to what your local folder is named) - if the content-folder of the remote URL has a different name than what you have locally. A typical case would be in a transition from "wp-content" to "content".
DEKODE_LOCAL_FILE_LOOKUP (default true) - Whether to look for the file locally before rewriting the URL to remote URL.
DEKODE_SUBDOMAIN_SUPPORT (default false) - Whether to take subdomains into account when rewriting the remote URL.
