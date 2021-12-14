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