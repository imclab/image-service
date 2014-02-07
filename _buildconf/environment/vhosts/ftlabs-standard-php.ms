<VirtualHost *:80>
  DocumentRoot {{{path}}}/html
  ServerName ftlabs-standard-php.labs.ft.com
  ServerAlias {{{devinitials}}}.sandboxes.ftlabs-standard-php.labs.ft.com
  ServerAlias v{{{major_version}}}.uat.ftlabs-standard-php.labs.ft.com
  ServerAlias *.ci.ftlabs-standard-php.labs.ft.com

  <Directory {{{path}}}/html>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.php [L]
  </Directory>
</VirtualHost>
