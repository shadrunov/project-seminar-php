# отчёт Задание 5.2 Управление сеансом
Шадрунов Алексей, БИБ202


# Работа API
## Database
![image](https://user-images.githubusercontent.com/44522467/155309663-e246b66d-abc6-4daa-80c1-802903fb1348.png)

## login.php 
```
POST /login.php

curl --request POST \
  --url http://localhost:8006/login.php \
  --header 'Content-Type: multipart/form-data; boundary=---011000010111000001101001' \
  --form username=admin \
  --form password=xxXX1234
``` 
**авторизоваться**  


## logout.php  
```
POST /logout.php

curl --request POST \
  --url http://localhost:8006/logout.php \
  --header 'Content-Type: multipart/form-data; boundary=---011000010111000001101001' \
  --cookie 'securecookie=nqcknavu2os8pv1bm8bdo33cc7'
```
**завершить сессию**

## public.php  
```
GET /public.php

curl --request GET \
  --url http://localhost:8006/public.php \
  --cookie securecookie=n3qke91ga5rr0eiq7em9s0j285
``` 
**публичный апи**

## private.php  
```
GET /private.php

curl --request GET \
  --url http://localhost:8006/private.php \
  --cookie securecookie=n3qke91ga5rr0eiq7em9s0j285
```
**приватный апи**

# Обработка ошибок и исключений
- Ошибка авторизации (проверки пары логин пароль). Журналируется в
secure.log

- Ошибка доступа (попытка анонима получить доступ к авторизованному ресурсу). в secure.log
- Метод запроса. in secure.log
- Ошибка подключения к базе данных. Возвращаться стандартный ответ пустой массив. in secure.log

# Бонус - механизмы безопасности
- httponly -- куки отправляются только по http, защита от xss
- use strict mode -- если сессия с присланным айди не найдена, отвергаем и регенируем новый айди
- samesite -- запрет на передачу кук в междоменный запрос
- name -- используем кастомное имя, чтобы усложнить session hijacking
- only cookie (установлено по умолчанию) -- использовать только куки для сессий (не query params)

также: 
- **не храним пароли в открытом виде в базе, используем хэширование**