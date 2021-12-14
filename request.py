import requests
import json

payload = {
    'lastname': 'Шадрунов', 
    'firstname': 'Алексей', 
    'grid': '105', 
    'age': '18'
    }

r = requests.post(url="http://localhost:8006/cr1/cr1.php", data=payload)

print(r.json())