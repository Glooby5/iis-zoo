Správa zoo
=============

- zdadání https://1drv.ms/b/s!AuyR1wlz5TMOhr8RXJ1J61fPQNGnQQ

## sample config.local.neon
```
parameters:

database:
	dsn: 'mysql:host=127.0.0.1;dbname=zoo'
	user: homestead
	password: secret
	options:
		lazy: yes

doctrine:
	user: homestead
	password: secret
	dbname: zoo
	metadata:
		App: %appDir%/entities

services:
	cache:
		class: Nette\Caching\Cache(Nette\Caching\Storages\FileStorage("/home/vagrant/temp/cache"))
```
