#!/bin/bash
php artisan serve --host=0.0.0.0 --port=8080 &
php artisan reverb:start --host=0.0.0.0 --port=8081 &
wait -n
