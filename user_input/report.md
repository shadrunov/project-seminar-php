# Отчёт по работе 1 Обработка данных пользователя  
`выполнил студент Шадрунов Алексей, группа БИБ202`  
`Москва, 2021`

## написание программы  
в ходе практической работы я написал программу, которая последовательно проверяет данные, поступившие на сервер от клиента, по различным критериям и в зависимости от корректности запроса возвращает различный ответ.  
проверка условий реализована с помощью вложенных условных операторов, функция `die()` не используется.  
небезопасный пользовательский ввод экранируется с помощью встроенной функции `htmlentities()`. это позволяет избежать xss-атаки.  
для улучшения зрительного восприятия вывода применяются элементы языка разметки html, например, `paragraphs` и `bullet lists`, а также заголовки `h1`.  
файл программы находится здесь: 

## тестирование программы  
для тестирования используется утилита `curl`, для которой подготовлены 8 запросов, 7 из которых приводят к ошибкам и 1 приводит к полностью правильному выводу.  

### curl1.sh  
#### код: 
```sh
curl -v --request POST \
  --url 'http://localhost:8006/user_input.php/?page=page1'
```  
#### вывод утилиты:  
```
[I] alex@alex ~/D/phpp> ./curl1.sh
*   Trying 127.0.0.1:8006...
* connect to 127.0.0.1 port 8006 failed: Connection refused
*   Trying ::1:8006...
* Connected to localhost (::1) port 8006 (#0)
> POST /user_input.php/?page=page1 HTTP/1.1
> Host: localhost:8006
> User-Agent: curl/7.79.1
> Accept: */*
> 
* Mark bundle as not supporting multiuse
< HTTP/1.1 200 OK
< Host: localhost:8006
< Date: Mon, 11 Oct 2021 09:01:32 GMT
< Connection: close
< X-Powered-By: PHP/8.0.11
< Content-type: text/html; charset=UTF-8
< 
* Closing connection 0
<h1>1. forbidden, token not set </h1>⏎  
```  
#### описание:  
программа вывела ошибку, так как токен `HTTP_X_ACCESS_TOKEN` не был передан в запросе  

### curl2.sh  
#### код: 
```sh
curl -v --request POST \
  --url 'http://localhost:8006/user_input.php/?page=page1' \
  --header 'X-Access-Token: new'
```  
#### вывод утилиты:  
```
[I] alex@alex ~/D/phpp> ./curl2.sh
*   Trying 127.0.0.1:8006...
* connect to 127.0.0.1 port 8006 failed: Connection refused
*   Trying ::1:8006...
* Connected to localhost (::1) port 8006 (#0)
> POST /user_input.php/?page=page1 HTTP/1.1
> Host: localhost:8006
> User-Agent: curl/7.79.1
> Accept: */*
> X-Access-Token: new
> 
* Mark bundle as not supporting multiuse
< HTTP/1.1 200 OK
< Host: localhost:8006
< Date: Mon, 11 Oct 2021 09:03:44 GMT
< Connection: close
< X-Powered-By: PHP/8.0.11
< Content-type: text/html; charset=UTF-8
< 
* Closing connection 0
<h1>2. forbidden, incorrect token </h1>⏎
```  
#### описание:  
программа вывела сообщение об ошибке, так как токен в запросе неверный (new вместо SECRET_TOKEN)
  
### curl3.sh  
#### код: 
```sh
curl -v --request GET \
  --url 'http://localhost:8006/user_input.php/?page=page1' \
  --header 'X-Access-Token: SECRET_TOKEN'
```  
#### вывод утилиты:  
```
[I] alex@alex ~/D/phpp> ./curl3.sh
Note: Unnecessary use of -X or --request, GET is already inferred.
*   Trying 127.0.0.1:8006...
* connect to 127.0.0.1 port 8006 failed: Connection refused
*   Trying ::1:8006...
* Connected to localhost (::1) port 8006 (#0)
> GET /user_input.php/?page=page1 HTTP/1.1
> Host: localhost:8006
> User-Agent: curl/7.79.1
> Accept: */*
> X-Access-Token: SECRET_TOKEN
> 
* Mark bundle as not supporting multiuse
< HTTP/1.1 200 OK
< Host: localhost:8006
< Date: Mon, 11 Oct 2021 09:06:14 GMT
< Connection: close
< X-Powered-By: PHP/8.0.11
< Content-type: text/html; charset=UTF-8
< 
* Closing connection 0
<h1>3. forbidden, wrong method </h1>⏎ 
```  
#### описание:  
программа вывела сообщение об ошибке, так как метод отличается от правильного (GET вместо POST)

  
### curl4.sh  
#### код: 
```sh
curl -v --request POST \
  --url http://localhost:8006/user_input.php/ \
  --header 'X-Access-Token: SECRET_TOKEN'
```  
#### вывод утилиты:  
```
[I] alex@alex ~/D/phpp> ./curl4.sh
*   Trying 127.0.0.1:8006...
* connect to 127.0.0.1 port 8006 failed: Connection refused
*   Trying ::1:8006...
* Connected to localhost (::1) port 8006 (#0)
> POST /user_input.php/ HTTP/1.1
> Host: localhost:8006
> User-Agent: curl/7.79.1
> Accept: */*
> X-Access-Token: SECRET_TOKEN
> 
* Mark bundle as not supporting multiuse
< HTTP/1.1 200 OK
< Host: localhost:8006
< Date: Mon, 11 Oct 2021 09:07:28 GMT
< Connection: close
< X-Powered-By: PHP/8.0.11
< Content-type: text/html; charset=UTF-8
< 
* Closing connection 0
<h1>4. error, no page type provided </h1>⏎ 
```  
#### описание:  
программа вывела сообщение об ошибке, так как в параметрах GET-запроса не указана страница

  
### curl5.sh  
#### код: 
```sh
curl -v --request POST \
  --url 'http://localhost:8006/user_input.php/?page=mypage' \
  --header 'X-Access-Token: SECRET_TOKEN'
```  
#### вывод утилиты:  
```
[I] alex@alex ~/D/phpp> ./curl5.sh
*   Trying 127.0.0.1:8006...
* connect to 127.0.0.1 port 8006 failed: Connection refused
*   Trying ::1:8006...
* Connected to localhost (::1) port 8006 (#0)
> POST /user_input.php/?page=mypage HTTP/1.1
> Host: localhost:8006
> User-Agent: curl/7.79.1
> Accept: */*
> X-Access-Token: SECRET_TOKEN
> 
* Mark bundle as not supporting multiuse
< HTTP/1.1 200 OK
< Host: localhost:8006
< Date: Mon, 11 Oct 2021 09:08:30 GMT
< Connection: close
< X-Powered-By: PHP/8.0.11
< Content-type: text/html; charset=UTF-8
< 
* Closing connection 0
<h1>5. error, incorrect page </h1>⏎ 
```  
#### описание:  
программа вывела сообщение об ошибке, так как значение параметра page не входит в список разрешённых значений

  
### curl6.sh  
#### код: 
```sh
curl -v --request POST \
  --url 'http://localhost:8006/user_input.php/?page=page1' \
  --header 'X-Access-Token: SECRET_TOKEN'
```  
#### вывод утилиты:  
```
[I] alex@alex ~/D/phpp> ./curl6.sh
*   Trying 127.0.0.1:8006...
* connect to 127.0.0.1 port 8006 failed: Connection refused
*   Trying ::1:8006...
* Connected to localhost (::1) port 8006 (#0)
> POST /user_input.php/?page=page1 HTTP/1.1
> Host: localhost:8006
> User-Agent: curl/7.79.1
> Accept: */*
> X-Access-Token: SECRET_TOKEN
> 
* Mark bundle as not supporting multiuse
< HTTP/1.1 200 OK
< Host: localhost:8006
< Date: Mon, 11 Oct 2021 09:09:28 GMT
< Connection: close
< X-Powered-By: PHP/8.0.11
< Content-type: text/html; charset=UTF-8
< 
* Closing connection 0
<p> requested page: page1</p><h1>6. error, incorrect data type </h1>⏎ 
```  
#### описание:  
программа вывела сообщение об ошибке, так как не указан заголовок `Content-Type`

  
### curl7.sh  
#### код: 
```sh
curl -v --request POST \
  --url 'http://localhost:8006/user_input.php/?page=page1' \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --header 'X-Access-Token: SECRET_TOKEN'
```  
#### вывод утилиты:  
```
[I] alex@alex ~/D/phpp> ./curl7.sh
*   Trying 127.0.0.1:8006...
* connect to 127.0.0.1 port 8006 failed: Connection refused
*   Trying ::1:8006...
* Connected to localhost (::1) port 8006 (#0)
> POST /user_input.php/?page=page1 HTTP/1.1
> Host: localhost:8006
> User-Agent: curl/7.79.1
> Accept: */*
> Content-Type: application/x-www-form-urlencoded
> X-Access-Token: SECRET_TOKEN
> 
* Mark bundle as not supporting multiuse
< HTTP/1.1 200 OK
< Host: localhost:8006
< Date: Mon, 11 Oct 2021 09:10:51 GMT
< Connection: close
< X-Powered-By: PHP/8.0.11
< Content-type: text/html; charset=UTF-8
< 
* Closing connection 0
<p> requested page: page1</p><h1>7. error, data not set </h1>⏎
```  
#### описание:  
программа вывела сообщение об ошибке, так как в форме не переданы никакие значения

  
### curl8.sh  
#### код: 
```sh
curl -v --request POST \
  --url 'http://localhost:8006/user_input.php/?page=page1' \
  --header 'Content-Type: application/x-www-form-urlencoded' \
  --header 'X-Access-Token: SECRET_TOKEN' \
  --data var1=1 \
  --data var2=2 \
  --data var3=3
```  
#### вывод утилиты:  
```
[I] alex@alex ~/D/phpp> ./curl8.sh
Note: Unnecessary use of -X or --request, POST is already inferred.
*   Trying 127.0.0.1:8006...
* connect to 127.0.0.1 port 8006 failed: Connection refused
*   Trying ::1:8006...
* Connected to localhost (::1) port 8006 (#0)
> POST /user_input.php/?page=page1 HTTP/1.1
> Host: localhost:8006
> User-Agent: curl/7.79.1
> Accept: */*
> Content-Type: application/x-www-form-urlencoded
> X-Access-Token: SECRET_TOKEN
> Content-Length: 20
> 
* Mark bundle as not supporting multiuse
< HTTP/1.1 200 OK
< Host: localhost:8006
< Date: Mon, 11 Oct 2021 09:12:11 GMT
< Connection: close
< X-Powered-By: PHP/8.0.11
< Content-type: text/html; charset=UTF-8
< 
* Closing connection 0
<p> requested page: page1</p><p> with POST sent 3 variables </p><ul><li>content of var1: 1</li><li>content of var2: 2</li><li>content of var3: 3</li></ul>⏎  
```  
#### описание:  
программа выполнилась успешно: вывела тип запрашиваемой страницы, количество переменных в теле POST-запроса, а также значение каждой из переменных  

## вывод  
в ходе данной практической работы я научился безопасно обрабатывать пользовательский ввод и возвращать ответ от сервера, а также конструировать запросы в утилите `curl`.