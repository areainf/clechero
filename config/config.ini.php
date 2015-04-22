; config file for Control Lechero

[application]
app_base_name=clechero
page_title=Control Lechero
default_controller = index
default_action = index
error_controller = error404
error_reporting = E_ALL
display_errors = 1
language = es
timezone = "America/Argentina/Cordoba"
site_name = Control Lechero
version = 0.0.1
currency = "$"
domain = nolose
log = /home/mmarozzi/Desarrollo/clechero/log/clechero.log


[database]
db_type = mysql
db_name = clecherodb
db_hostname = localhost
db_username = root
db_password = dijkstra
db_port = 3306
db_url = mysql://root:dijkstra@localhost/clecherodb

[template]
template_dir = "templates"
cache_dir = "/tmp/cache"
cache_lifetime = 3600

[mail]
mailer_type = system
admin_email = admin@example.com
admin_name = "Seven Kevins Admin"
smtp_server = mail.example.com 
smtp_port = 25;
x_mailer = "PHPRO.ORG Mail"
smtp_server = "mail.example.com"
smtp_port = 25
smtp_timeout = 30

[logging]
log_level = 200
log_handler = file
log_file = /tmp/clechero.log
