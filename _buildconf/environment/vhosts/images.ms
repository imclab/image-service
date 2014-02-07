<VirtualHost *:80>
  DocumentRoot {{{path}}}/html
  ServerName images.labs.ft.com
  ServerAlias {{{devinitials}}}.sandboxes.images.labs.ft.com
  ServerAlias v{{{major_version}}}.uat.images.labs.ft.com
  ServerAlias *.ci.images.labs.ft.com

  <Directory {{{path}}}/html>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.php [L]
  </Directory>
</VirtualHost>
