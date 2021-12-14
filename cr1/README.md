# Отчёт  

## Модификация БД  
в данной БД можно создать две пары внешних ключей: `students.grid -> groups.grid` и `groups.speciality_id -> specialities.speciality_id`. так как типы `students.grid` и `groups.grid` не совпадают, приводим их к одному типу. итоговый запрос:  
```mysql
use prof202;

ALTER TABLE students MODIFY grid int(10) unsigned;
ALTER TABLE students
    ADD FOREIGN KEY (grid)
    REFERENCES groups(grid);

ALTER TABLE groups
    ADD FOREIGN KEY (speciality_id)
    REFERENCES specialities(speciality_id);
```  

## создание API
![image](https://user-images.githubusercontent.com/44522467/145993229-1c4b7dd7-579f-494b-9862-9893a5aaa222.png)
![image](https://user-images.githubusercontent.com/44522467/146040823-b401de9b-2048-40db-b65f-c9860fb2d48e.png)
![image](https://user-images.githubusercontent.com/44522467/146040891-7d848183-a2fb-48b8-a22c-9447d7396d3b.png)
![image](https://user-images.githubusercontent.com/44522467/146040987-54ed18b2-8b09-4627-b6f8-e37a189954fd.png)
![image](https://user-images.githubusercontent.com/44522467/146041090-559e8e0b-350d-4c1a-9202-1c7000ef690a.png)


![image](https://user-images.githubusercontent.com/44522467/146053062-53dc34f7-13e3-4fe5-a19c-c3a446b7af47.png)

![image](https://user-images.githubusercontent.com/44522467/146053178-0a30c4a7-249a-4a9f-86be-43f5fb29850f.png)

![image](https://user-images.githubusercontent.com/44522467/146053321-c5855dea-19f2-4625-87c8-078d587d10a4.png)

![image](https://user-images.githubusercontent.com/44522467/146053397-84595b77-e82f-41c1-ae79-8e475154b7ed.png)

![image](https://user-images.githubusercontent.com/44522467/146053490-fff79ec9-a131-405f-8c7f-fcf643bbc2b0.png)

![image](https://user-images.githubusercontent.com/44522467/146053586-6e4581f0-8768-47cf-98ca-fadcaa97ac1b.png)