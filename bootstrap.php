ini_set('session.save_handler', 'redis');
ini_set('session.save_path', 'tcp://redis:secret123@redis');