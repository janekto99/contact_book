# Instalace projektu
Nejprve naklonujte repozitář do složky, kde chcete mít projekt umístěn pomocí:

>git clone https://github.com/janekto99/contact_book.git

Přejděte do složky projektu:
>cd contact_book

Nainstalujte závislosti pomocí:
>composer install

V .env souboru nastavte databázové spojení:
![img.png](img.png)

Dále vytvořte databázi pomocí:
>php bin/console doctrine:database:create

Proveďte migraci a potvrďte:
>php bin/console doctrine:migrations:migrate

Nyní nastartujte symfony server a aplikace by měla být k dispozici.
>symfony server:start