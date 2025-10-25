# FoodSupply Management System (FSMS)

Sistem manajemen pasokan bahan makanan yang menghubungkan yayasan, admin, dan supplier untuk memastikan distribusi makanan yang efisien dan transparan.

## 🎯 Deskripsi Sistem

FSMS adalah platform yang memfasilitasi proses permintaan dan distribusi bahan makanan dengan alur kerja yang terstruktur:

1. **Foundation (Yayasan)** - Mengajukan request bahan makanan untuk program mereka
2. **Super Admin** - Menyetujui request, menentukan harga maksimal, dan memilih supplier
3. **Supplier** - Mengelola produk, mengajukan harga (dalam batas maksimal), dan mengirim barang

## 🚀 Fitur Utama

### Role-Based Access Control
- **Super Admin**: Kelola seluruh sistem, tentukan harga maksimal, koordinasi supplier
- **Supplier**: Kelola produk, terima pesanan, kirim bahan makanan
- **Foundation**: Ajukan request bahan makanan untuk program yayasan

### Dashboard Khusus
- Dashboard Super Admin dengan manajemen akun dan sistem
- Dashboard Supplier untuk kelola produk dan pesanan
- Dashboard Foundation untuk request dan monitoring

### Keamanan
- Middleware untuk proteksi route berdasarkan role
- Authentication dengan Laravel Fortify
- Password hashing dan email verification

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 12.x dengan PHP 8.2+
- **Frontend**: Livewire dengan Tailwind CSS
- **Database**: SQLite (development) / MySQL (production)
- **Authentication**: Laravel Fortify
- **UI Components**: Livewire Flux

## 📋 Requirements

- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- SQLite atau MySQL

## 🔧 Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd fsms
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

6. **Run Development Server**
   ```bash
   php artisan serve
   ```

## 👥 Test Accounts

Setelah menjalankan seeder, Anda dapat login dengan akun berikut:

- **Super Admin**: admin@fsms.com / admin@fsms.com
- **Supplier**: supplier@fsms.com / supplier@fsms.com  
- **Foundation**: foundation@fsms.com / foundation@fsms.com

## 📁 Struktur Project

```
app/
├── Http/Middleware/     # Role-based middleware
├── Models/              # User, Role, dan model lainnya
resources/views/
├── admin/               # Dashboard dan halaman admin
├── supplier/            # Dashboard dan halaman supplier
├── foundation/          # Dashboard dan halaman foundation
├── layouts/             # Layout utama aplikasi
database/
├── migrations/          # Database migrations
├── seeders/             # Data seeders
routes/
└── web.php              # Route definitions
```

## 🔐 Middleware & Security

- `role:super_admin` - Hanya Super Admin
- `role:supplier` - Hanya Supplier
- `role:foundation` - Hanya Foundation
- `auth` - User yang sudah login
- `verified` - Email sudah diverifikasi

## 📊 Database Schema

### Tables
- `users` - Data pengguna
- `roles` - Role sistem (super_admin, supplier, foundation)
- `role_user` - Pivot table untuk relasi user-role
- `food_requests` - Request bahan makanan dari yayasan
- `food_items` - Item bahan makanan dalam request
- `supplier_quotations` - Penawaran harga dari supplier
- `deliveries` - Data pengiriman

## 🎨 UI/UX Features

- **Responsive Design** - Mobile-friendly layout
- **Modern UI** - Clean design dengan Tailwind CSS
- **Role-based Navigation** - Menu sesuai dengan role user
- **Dashboard Analytics** - Statistik dan informasi penting
- **Form Validation** - Client dan server-side validation

## 🚀 Deployment

1. Setup production environment variables
2. Run migrations: `php artisan migrate --force`
3. Seed database: `php artisan db:seed --force`
4. Build assets: `npm run build`
5. Configure web server (Apache/Nginx)
6. Set proper file permissions

## 📝 License

This project is licensed under the MIT License.

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📞 Support

Untuk pertanyaan atau dukungan, silakan buat issue di repository ini.

---

**FoodSupply Management System** - Membantu distribusi makanan untuk yang membutuhkan 🍽️
