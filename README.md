# Тестовое задание Laravel

### Старт
```
git clone https://github.com/SrgUsn/laravel-test-console.git
cd ./laravel-test-console
composer install
cp .env.example .env
// указать настройки подключения к db в файле .env
php artisan migrate
php artisan customer:generate {count} // генерация .csv, count - количество сгенерированных записей 
php artisan customer:import // импорт из файла .csv
php artisan queue:work --stop-when-empty // запуск воркера очереди

```