import express from 'express';
import sqlite3 from 'sqlite3';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = process.env.PORT || 3000;

process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0';

app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, 'public')));

const RAJABILLER_URL = 'https://c-dev-partnerlink.rajabiller.com/json/index.php';
const UID = 'SP300203';
const PIN = '311575';

const db = new sqlite3.Database('./database.sqlite', (err) => {
    if (err) {
        console.error('Gagal koneksi database:', err.message);
    } else {
        console.log('Terhubung ke Database SQLite.');
        db.run(`CREATE TABLE IF NOT EXISTS transactions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            kode_produk TEXT,
            nama_pdam TEXT,
            idpel TEXT,
            nama TEXT,
            alamat TEXT,
            nominal REAL,
            admin REAL,
            total_bayar REAL,
            ref1 TEXT,
            ref2 TEXT,
            status TEXT,
            response_data TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )`);
    }
});

const generateRef = () => 'REF' + Date.now() + Math.floor(Math.random() * 1000);

app.post('/api/inquiry', async (req, res) => {
    const { kode_produk, idpel } = req.body;
    const ref1 = generateRef();

    const payload = {
        method: 'fastpay.inq',
        uid: UID,
        pin: PIN,
        idpel1: idpel,
        idpel2: '',
        idpel3: '',
        kode_produk: kode_produk,
        ref1: ref1
    };

    try {
        const response = await fetch(RAJABILLER_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'User-Agent': 'PostmanRuntime/7.32.3'
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        console.log("=== RESPON INQUIRY ===");
        console.log(JSON.stringify(data, null, 2));
        res.json({ ...data, ref1 });
    } catch (error) {
        res.status(500).json({ rc: '99', ket: 'Gagal koneksi ke server provider', error: error.message });
    }
});

app.post('/api/payment', async (req, res) => {
    const { kode_produk, idpel, idpel2, idpel3, nominal, ref1, ref2, nama_pdam, nama, alamat, admin, total_bayar } = req.body;

    const payload = {
        method: 'fastpay.pay',
        uid: UID,
        pin: PIN,
        idpel1: idpel,
        idpel2: idpel2 || '',
        idpel3: idpel3 || '',
        kode_produk: kode_produk,
        ref1: ref1,
        nominal: nominal,
        ref2: ref2 || '',
        ref3: ''
    };

    console.log('=== PAYLOAD PAYMENT ===');
    console.log(JSON.stringify(payload, null, 2));

    try {
        const response = await fetch(RAJABILLER_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',                
                'User-Agent': 'PostmanRuntime/7.32.3'
            },
            body: JSON.stringify(payload)
        });
        const data = await response.json();
        
        console.log('=== RESPON PAYMENT ===');
        console.log(JSON.stringify(data, null, 2));

        if (data.status === '00') {
            const query = `INSERT INTO transactions (kode_produk, nama_pdam, idpel, nama, alamat, nominal, admin, total_bayar, ref1, ref2, status, response_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`;
            db.run(query, [
                kode_produk, nama_pdam, idpel, nama, alamat, nominal, admin, total_bayar,
                ref1, data.ref2 || ref2, 'SUCCESS', JSON.stringify(data)
            ], function(err) {
                if (err) console.error('DB Insert Error:', err.message);
                res.json(data);
            });
        } else {
            res.json(data);
        }
    } catch (error) {
        res.status(500).json({ rc: '99', ket: 'Gagal melakukan pembayaran', error: error.message });
    }
});

app.get('/api/history', (req, res) => {
    db.all(`SELECT * FROM transactions ORDER BY created_at DESC`, [], (err, rows) => {
        if (err) res.status(500).json({ error: err.message });
        else res.json(rows);
    });
});

app.listen(PORT, () => {
    console.log(`Server berjalan di http://localhost:${PORT}`);
});