# Генератор конфігураціі для bind и mikrotik

## Підготовка конфігурації bind 
1. В блок ```options {}``` додати 
```
response-policy { zone "badlist"; };
```

2. Додати зону
```
zone "badlist" {
    type master;
    file "/etc/bind/blocked";
    allow-query {none;};
};
```

