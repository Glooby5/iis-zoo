Správa zoo
=============

- zdadání https://1drv.ms/b/s!AuyR1wlz5TMOhr8RXJ1J61fPQNGnQQ

## Zprovoznění
- návod https://laravel.com/docs/5.3/homestead
- stáhnout vagrant
- přidat homestead box
- naklonovat si tento repozitář
- upravit příslušně Homestead.yaml
- spustit `composer install`
- přidat ve vagrantu temp složku: `~/temp`
- přidat local.neon
- vytvořit si databázi zoo
- vytvořit tabulky `php index.php orm:schema-too:create`

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
```
