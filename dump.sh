mysqldump -u root -pmysql_pwd! -d -h prepro bender --dump-date > application/data/sample-sql.sql
sed -i -e 's/ AUTO_INCREMENT=[0-9]\+//' application/data/sample-sql.sql 
