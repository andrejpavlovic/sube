SQL Queries for Managing Site Data
=======

Since the site doesn't have a true administrative backend, some of the administrative tasks need to be carried out through the use of SQL scripts.

See below for SQL scripts used for carring our common site administration tasks.

Find all contact seller entries for user by email
--------

```sql
SELECT DATE_FORMAT(FROM_UNIXTIME(cs.time), '%Y-%m-01') date, cw.listid, cs.email contact, cw.title, cs.message
FROM `contact_seller` cs
INNER JOIN `courseware` cw ON cw.listid = cs.listid
INNER JOIN `users` u ON u.uid = cw.uid
WHERE u.`email` = 'replaceme@someemail.com'
```

Mark user postings as removed by user email
--------

```sql
UPDATE `courseware` cw
INNER JOIN `users` u ON u.uid = cw.uid
SET remove = 2
WHERE u.`email` = 'replaceme@someemail.com' AND `remove` = 0
```

Find active user postings by user email
--------

```sql
SELECT *
FROM  `courseware` cw
INNER JOIN `users` u ON u.uid = cw.uid
WHERE u.`email` = 'replaceme@someemail.com' AND `remove` = 0
```

Remove a listing permanently
--------

```sql
DELETE courseware, cw_courses FROM `courseware` AS a
LEFT JOIN `cw_courses` AS b ON a.listid=b.listid
WHERE a.listid=listid_goes_here
```

Remove a SPAM post permanently
--------

```sql
DELETE courseware, cw_courses, contact_seller FROM `courseware` AS a
LEFT JOIN `cw_courses` AS b ON a.listid=b.listid
LEFT JOIN `contact_seller` AS c ON a.listid=c.listid
WHERE a.listid=listid_goes_here
```

Mark outdated housing postings as removed
--------

```sql
UPDATE `courseware`
SET remove = 2
WHERE `category` = 5 AND `year` = 'replace_with_year'  AND `term` = 'replace_with_one_of(Spring,Fall,Winter)' AND `remove` = 0
```

Number of times sellers have been contacted each month
--------

```sql
SELECT DATE_FORMAT(FROM_UNIXTIME(time), '%Y-%m-01') time, COUNT(*) total
FROM contact_seller
GROUP BY YEAR(FROM_UNIXTIME(time)), MONTH(FROM_UNIXTIME(time))
ORDER BY time DESC;
```

Number of non-housing postings that have been delisted each month
--------

```sql
SELECT DATE_FORMAT(FROM_UNIXTIME(time), '%Y-%m-01') time, COUNT(*) total
FROM courseware
WHERE category != 5 AND remove = 1
GROUP BY YEAR(FROM_UNIXTIME(time)), MONTH(FROM_UNIXTIME(time))
ORDER BY time DESC;
```

Number of courseware that has likely been sold over UWSUBE
--------

```sql
SELECT DATE_FORMAT(FROM_UNIXTIME(c.time), '%Y-%m-01') time, COUNT(c.listid)
FROM courseware c
LEFT JOIN contact_seller cs ON cs.listid = c.listid
WHERE cs.listid IS NOT NULL AND c.category != 5 AND c.remove = 1
GROUP BY DATE_FORMAT(FROM_UNIXTIME(c.time), '%Y-%m-01')
ORDER BY DATE_FORMAT(FROM_UNIXTIME(c.time), '%Y-%m-01') DESC
```

Find specific posting and user
--------

```sql
SELECT * 
FROM `courseware` cw
INNER JOIN  `users` u ON u.uid = cw.uid
WHERE listid=13010
```
