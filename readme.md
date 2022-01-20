# отчёт 5.1 Приложение с базой данных

## listitems.php {}
```
GET /listitems.php
``` 
получить список всех студентов  
также отдаются значения из справочных таблиц (`department_name`)   
формат ответа: json  
![listitems](https://user-images.githubusercontent.com/44522467/150412078-403b6407-71ef-41c2-b002-690350f6dbc1.png)

## listitems2.php
```
GET /listitems2.php
```
получить список всех курсов
также отдаются значения из справочных таблиц (`department_name`)    
формат ответа: json  
![listitems2](https://user-images.githubusercontent.com/44522467/150412220-d17ad083-bb8e-4887-bcb4-07b959dce04d.png)

## getitem.php  
```
GET /getitem.php?ID={student_id}
``` 
выбор конкретного студента, также связанные записи (курсы) из второй таблицы  
![getitem-200](https://user-images.githubusercontent.com/44522467/150412595-fe885a74-2a31-40f9-a889-d9241eb861ce.png)
производится валидация ID: если записи нет или ID не передан, то отдаётся пустой массив  
![getitem-400](https://user-images.githubusercontent.com/44522467/150413406-3c088a0e-c676-4fc8-99aa-6c0b08824653.png)  

## additem.php  
```
POST /additem.php
```
```json
body = {
    "name": "имя студента",
    "hometown": "ID города",
    "department": "ID департамента"
}
```
добавление студента, параметры в body в формате json  
![additem-200](https://user-images.githubusercontent.com/44522467/150413910-da81e858-94f3-44a4-b16c-d59e599e07ea.png)
изменения в базе данных:  
![additem-db](https://user-images.githubusercontent.com/44522467/150414049-912c0504-d44b-4afc-a4a6-378619509d71.png)

производится валидация параметров:   
- если не все параметры заданы:  
![additem-err1](https://user-images.githubusercontent.com/44522467/150414358-1eebbf8d-886f-43e4-9b06-7eeb6cbff676.png)
- если в справочниках не существует указанных `hometown` или `department`:  
![additem-err2](https://user-images.githubusercontent.com/44522467/150414566-ebcd1fdd-a92a-4e09-94fc-a9e4f1107f6f.png)

## additem2.php  
```
POST /additem2.php
```
```json
body = {
    "name": "имя курса",
    "rating": "рейтинг",
    "department": "ID департамента"
}
```
добавление курса, параметры в body в формате json    
![additem-200](https://user-images.githubusercontent.com/44522467/150414900-0b547db0-c0c4-407f-988e-844e3f950633.png)
изменения в базе данных:   
![additem2-db](https://user-images.githubusercontent.com/44522467/150415074-52ae38dd-8652-426c-9ea4-825360b416a8.png)

производится валидация параметров аналогично предыдущему пункту:  
- если не все параметры заданы  
- если в справочниках не существует указанного `department`  

## addlink.php
```
POST /addlink.php
```
```json
body = {
    "ID1": "course ID",
    "ID2": "student ID"
}
```
добавление М-М связи студент-курс, параметры в body в формате json  
![addlink-200](https://user-images.githubusercontent.com/44522467/150415439-2e21b066-6779-4b77-b215-28101cbc1cea.png)
изменения в базе данных:   
![addlink-db](https://user-images.githubusercontent.com/44522467/150415588-91d29aac-29af-48fe-af5c-c3c0baa267af.png)

производится валидация параметров:  
- если не все параметры заданы:  
![image](https://user-images.githubusercontent.com/44522467/150417596-38bff46e-9e1a-4026-89f0-9431cc8c579e.png)
- если в таблицах не существует указанных записей:  
![image](https://user-images.githubusercontent.com/44522467/150417495-334eba4d-c967-4fa5-acff-6a2dfe7f6777.png)
- если в таблице уже существует такая же связь:  
![image](https://user-images.githubusercontent.com/44522467/150417367-c0f63455-c917-4c89-bccf-e01de4e9f8cb.png)  

## edititem.php
```
POST /edititem.php
```
```json
body = {
    "ID": "student ID",
    "name": "имя студента (optional)",
    "hometown": "ID города (optional)",
    "department": "ID департамента (optional)"
}
```
редактирование записи студента, параметры передаются в json  
![image](https://user-images.githubusercontent.com/44522467/150417923-496a46f6-d1bf-4993-a456-c7f13298a662.png)
изменения в базе данных:   
![image](https://user-images.githubusercontent.com/44522467/150417969-4b1cece4-979f-47f7-9bc9-f86092ad24e1.png)  

производится валидация параметров:  
- если ни одно поле не задано:  
![image](https://user-images.githubusercontent.com/44522467/150418697-12b788aa-5942-4c97-bb28-d436f537649d.png)
- если не указан ID:  
![image](https://user-images.githubusercontent.com/44522467/150418512-955db7fc-413e-4f44-892a-1a666a1a6693.png)
- если не существует `hometown` или `department`:  
![image](https://user-images.githubusercontent.com/44522467/150418586-80ab5409-7901-421a-a633-ebf013ac5564.png)
- если не существует такая запись студента:  
![image](https://user-images.githubusercontent.com/44522467/150418388-8c6c6573-160b-4792-9822-22056e0566fe.png)

## edititem2.php
```
POST /edititem2.php
```
```json
body = {
    "ID": "course ID",
    "name": "имя курса (optional)",
    "rating": "рейтинг курса (optional)",
    "department": "ID департамента (optional)"
}
```
редактирование записи курса, параметры передаются в json  
![image](https://user-images.githubusercontent.com/44522467/150419118-d5d24723-6e46-404c-8483-80680ebe8e56.png)
изменения в базе данных:  
![image](https://user-images.githubusercontent.com/44522467/150419224-380d3cac-fc4f-4f41-9bd1-99dc255d0adc.png)

производится валидация параметров аналогично предыдущему пункту:  
- если ни одно поле не задано  
- если не указан ID  
- если не существует `department`  
- если не существует такая запись курса  

## deleteitem.php
```
DELETE /deleteitem.php?ID={student_id}
```
удаление записи студента, параметры передаются в query params  
![image](https://user-images.githubusercontent.com/44522467/150421485-67cf62d8-7e58-4e0c-94ef-5ec59b5267d9.png)
изменения в базе данных:  
![image](https://user-images.githubusercontent.com/44522467/150421718-0050c387-6b82-4b49-a0bb-d755319cabcc.png)


производится валидация параметров:  
- если не указан ID:  
![image](https://user-images.githubusercontent.com/44522467/150422179-460ab90a-8cb2-48eb-a294-a7907983f6eb.png)
- если существует связь в таблице М-М:  
![image](https://user-images.githubusercontent.com/44522467/150422081-7a9c2214-d2db-4465-9a9b-c7c50604de7c.png)
![image](https://user-images.githubusercontent.com/44522467/150422113-827dd8e3-df8c-482f-af86-1a45f2c03a65.png)
- если не существует такая запись студента:  
![image](https://user-images.githubusercontent.com/44522467/150421989-5cc6b7a4-c398-4ad2-848a-b9ec4a665445.png)

# обработка ошибок
- в каждом api проверяется метод запроса, если метод неверный, запрос не выполняется:  
![image](https://user-images.githubusercontent.com/44522467/150422751-95bfb977-39c2-4f73-8b3f-00050cc0128a.png)  
- при отсутствии подключения к базе данных возвращается пустой ответ:  
![image](https://user-images.githubusercontent.com/44522467/150423109-efad673a-cde7-4a2e-aa2b-013b4310150d.png)
![image](https://user-images.githubusercontent.com/44522467/150423006-d32317b6-a5a0-4565-aeb3-0cf526cf584b.png)

- бонус: сообщения об ошибках логируются в [error.log](./db2/error.log)
![image](https://user-images.githubusercontent.com/44522467/150424089-15cd9d89-4ab4-4bd2-b501-3861aeb0273d.png)

# работа с БД
- все данные экранируются
- используется `left join`
- используется таблица М-М
- сгенерировано большое количество записей
дамп базы данных: [dump.sql](./dump.sql)