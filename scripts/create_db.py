import psycopg2

conn = psycopg2.connect(
    dbname="postgres",
    user="postgres",
    password="",
    host="localhost",
    port="5432"
)
conn.autocommit = True
cur = conn.cursor()

cur.execute("CREATE DATABASE renpwn_db;")
cur.execute("CREATE USER renpwn_user WITH PASSWORD 'renpwn_pass';")
cur.execute("GRANT ALL PRIVILEGES ON DATABASE renpwn_db TO renpwn_user;")

cur.close()
conn.close()

print("Database & user created")