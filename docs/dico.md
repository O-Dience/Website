# Data Dictionnary

## USER

Champ|Description|Type|Spécificités|
-|-|-|-|
id| Identifiant unique|integer| not null, primary key, auto increment
username|Nom de l'utilisateur|varchar |250, unique, null
company_name |nom de la société|varchar|250| null
email|Adresse e-mail de l'utilisateur|varchar| unique, not null
birthdate|date de naissance de l'utilisateur|datetime| null
company_creation |date création société |datetime | null
company_role |poste dans la structure | varchar | 255, null
password|Mot de passe|varchar|255, not null
picture|Avatar|varchar|255, null|
status|Statut du compte|boolean|default true|
created at|Date de création|timestamp|on update|
updated at|Date de mise à jour|timestamp|on update, null|

## USER_SOCIAL

Champ|Description|Type|Spécificités|
-|-|-|-|
user_id| Id de l'utilisateur| tinyint|not null
social_network_id|Id du réseau social |tinyint |not null
link|lien du réseau social|varchar |250, unique, not null

## ROLE

Champ|Description|Type|Spécificités|
-|-|-|-|
id| Identifiant unique|integer| not null, primary key, auto increment
name|Non du rôle|varchar |250, not null

## ANNOUNCEMENT

Champ|Description|Type|Spécificités|
-|-|-|-|
id| Identifiant unique|integer| not null, primary key, auto increment
title|Nom de l'annonce|varchar |250, not null
content |Contenu de la publication|text | not null
picture|Image pour representer l'annonce|varchar|255, null|
user_id |Identifiant de l'utilisateur| foreign key | not null
status|Statut de la publication|boolean|default true|
created at|Date de création|timestamp|on update|
updated at|Date de mise à jour|timestamp|on update, null|

## CATEGORY

Champ|Description|Type|Spécificités|
-|-|-|-|
id| Identifiant unique|integer| not null, primary key, auto increment
label|Nom de la catégorie|varchar |250, not null
picto |Image representant la catégorie|varchar | not null, 255
created at|Date de création|timestamp|on update|
updated at|Date de mise à jour|timestamp|on update, null|

## SOCIAL NETWORK

Champ|Description|Type|Spécificités|
-|-|-|-|
id| Identifiant unique|integer| not null, primary key, auto increment
nom|Nom du réseau social |varchar |250, not null
picto |Logo du réseau social|varchar | not null, 255
link |Lien vers API |text | null
created at|Date de création|timestamp|on update|
updated at|Date de mise à jour|timestamp|on update, null |
