# Open Translation Engine (OTE)
# Create admin user

INSERT INTO `ote_user` ( `username`, `password`, `email`, `level`, `created`, `last_login` ) 
VALUES ( 'admin', 'admin', 'admin@localhost', '9', NOW(), NOW() );
