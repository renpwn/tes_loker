import requests
import hashlib
from .models import Produk, Kategori, Status

API_URL = "https://recruitment.fastprint.co.id/tes/api_tes_programmer"

def fetch_and_store_produk():
    session = requests.Session()

    # =========================
    # STEP 1: AMBIL USERNAME
    # =========================
    first = session.post(API_URL, json={})
    username = first.headers.get("x-credentials-username").split(" ")[0]

    if not username:
        raise Exception("Gagal mengambil username dari server")

    # =========================
    # STEP 2: BUAT PASSWORD DARI USERNAME
    # =========================
    # contoh username: tesprogrammer270126C00
    
    date_part = username.replace("tesprogrammer", "").split("C")[0]
    # hasil: 270126
    
    day = date_part[0:2]
    month = date_part[2:4]
    year = date_part[4:6]
    
    raw = f"bisacoding-{day}-{month}-{year}"
    password = hashlib.md5(raw.encode()).hexdigest()

    # =========================
    # STEP 3: LOGIN + FETCH DATA
    # =========================
    payload = {
        "username": username,
        "password": password
    }

    second = session.post(API_URL, data=payload)

    if second.status_code != 200:
        err = Exception(f"Login gagal: {second.text}")
        err.username = username
        err.password = raw
        err.passwordmd5 = password
        raise err

    data = second.json().get("data", [])

    # =========================
    # STEP 4: SIMPAN KE DB
    # =========================
    for item in data:
        kategori, _ = Kategori.objects.get_or_create(
            nama_kategori=item["kategori"]
        )

        status, _ = Status.objects.get_or_create(
            nama_status=item["status"]
        )

        Produk.objects.update_or_create(
            nama_produk=item["nama_produk"],
            defaults={
                "harga": int(item["harga"]),
                "kategori": kategori,
                "status": status
            }
        )