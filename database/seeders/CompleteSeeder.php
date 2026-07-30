<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\Facility;
use App\Models\RoomPhoto;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CompleteSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Seeding database...');

        // ========================================
        // 1. USERS
        // ========================================
        $this->command->info('👤 Creating users...');
        
        $admin = User::create([
            'name' => 'Admin Gardenia',
            'email' => 'admin@gardenia.com',
            'phone' => '081234567890',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $users = [];
        $users[] = User::create([
            'name' => 'rere CANTIK',
            'email' => 'rere@gmail.com',
            'phone' => '082111222333',
            'address' => 'Jl. Mawar No. 10, Jakarta',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $users[] = User::create([
            'name' => 'cihuy',
            'email' => 'cihuy@gmail.com',
            'phone' => '082444555666',
            'address' => 'Jl. Melati No. 20, Bandung',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $users[] = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@gmail.com',
            'phone' => '082777888999',
            'address' => 'Jl. Anggrek No. 30, Surabaya',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);

        $this->command->info("✅ Created {$users->count()} users + 1 admin");

        // ========================================
        // 2. FACILITIES
        // ========================================
        $this->command->info('🛠️  Creating facilities...');
        
        $facilities = [
            ['name' => 'AC', 'icon' => 'wind'],
            ['name' => 'Kasur', 'icon' => 'bed'],
            ['name' => 'Lemari', 'icon' => 'cabinet'],
            ['name' => 'Meja Belajar', 'icon' => 'desk'],
            ['name' => 'WiFi', 'icon' => 'wifi'],
            ['name' => 'Kamar Mandi Dalam', 'icon' => 'shower'],
            ['name' => 'Jendela', 'icon' => 'window'],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }

        $this->command->info("✅ Created " . count($facilities) . " facilities");

        // ========================================
        // 3. ROOMS
        // ========================================
        $this->command->info('🏠 Creating rooms...');
        
        $roomsData = [
            [
                'name' => 'Kamar 01',
                'type' => 'standard',
                'floor' => 1,
                'price' => 750000,
                'description' => 'Kamar standard dengan fasilitas lengkap di lantai 1.',
                'is_available' => false, // Terisi (cihuy)
                'facilities' => [1, 2, 3, 4, 5], // AC, Kasur, Lemari, Meja, WiFi
            ],
            [
                'name' => 'Kamar 10',
                'type' => 'deluxe',
                'floor' => 1,
                'price' => 750000,
                'description' => 'Kamar deluxe dengan view bagus.',
                'is_available' => true, // Available (pending booking)
                'facilities' => [1, 2, 3, 4, 5, 6], // + Kamar Mandi Dalam
            ],
            [
                'name' => 'Kamar 20',
                'type' => 'deluxe',
                'floor' => 2,
                'price' => 750000,
                'description' => 'Kamar deluxe lantai 2 dengan jendela besar.',
                'is_available' => false, // Terisi (rere)
                'facilities' => [1, 2, 3, 4, 5, 6, 7], // All
            ],
        ];

        $rooms = [];
        foreach ($roomsData as $roomData) {
            $facilityIds = $roomData['facilities'];
            unset($roomData['facilities']);

            $room = Room::create($roomData);
            $room->facilities()->attach($facilityIds);

            $rooms[] = $room;
        }

        $this->command->info("✅ Created " . count($rooms) . " rooms");

        // ========================================
        // 4. BOOKINGS
        // ========================================
        $this->command->info('📅 Creating bookings...');
        
        // Booking 1: rere - Kamar 20 (CONFIRMED)
        $booking1 = Booking::create([
            'booking_code' => 'GDN-57118950',
            'user_id' => $users[0]->id, // rere
            'room_id' => $rooms[2]->id, // Kamar 20
            'check_in_date' => now()->addDays(7),
            'duration_months' => 1,
            'total_price' => 750000,
            'dp_amount' => 250000,
            'status' => 'confirmed',
        ]);

        // Booking 2: rere - Kamar 10 (PENDING)
        $booking2 = Booking::create([
            'booking_code' => 'GDN-52709187',
            'user_id' => $users[0]->id, // rere
            'room_id' => $rooms[1]->id, // Kamar 10
            'check_in_date' => now()->addDays(14),
            'duration_months' => 1,
            'total_price' => 750000,
            'dp_amount' => 250000,
            'status' => 'pending',
        ]);

        // Booking 3: cihuy - Kamar 01 (CONFIRMED)
        $booking3 = Booking::create([
            'booking_code' => 'GDN-76088110',
            'user_id' => $users[1]->id, // cihuy
            'room_id' => $rooms[0]->id, // Kamar 01
            'check_in_date' => now()->addDays(5),
            'duration_months' => 1,
            'total_price' => 750000,
            'dp_amount' => 250000,
            'status' => 'confirmed',
        ]);

        $this->command->info("✅ Created 3 bookings");

        // ========================================
        // 5. PAYMENTS
        // ========================================
        $this->command->info('💰 Creating payments...');
        
        // Payment 1: DP Booking 1 (VERIFIED)
        Payment::create([
            'booking_id' => $booking1->id,
            'user_id' => $booking1->user_id,
            'amount' => 250000,
            'payment_method' => 'qris',
            'payment_type' => 'dp',
            'status' => 'verified',
            'verified_at' => now()->subDays(2),
            'verified_by' => $admin->id,
            'notes' => 'DP Kamar 20',
        ]);

        // Payment 2: DP Booking 2 (PENDING - akan tampil di dashboard)
        Payment::create([
            'booking_id' => $booking2->id,
            'user_id' => $booking2->user_id,
            'amount' => 250000,
            'payment_method' => 'qris',
            'payment_type' => 'dp',
            'status' => 'pending',
            'proof_path' => null,
            'notes' => 'DP Kamar 10 - Menunggu verifikasi',
        ]);

        // Payment 3: DP Booking 3 (VERIFIED)
        Payment::create([
            'booking_id' => $booking3->id,
            'user_id' => $booking3->user_id,
            'amount' => 250000,
            'payment_method' => 'bca',
            'payment_type' => 'dp',
            'status' => 'verified',
            'verified_at' => now()->subDays(1),
            'verified_by' => $admin->id,
            'notes' => 'DP Kamar 01',
        ]);

        $this->command->info("✅ Created 3 payments (2 verified, 1 pending)");

        // ========================================
        // SUMMARY
        // ========================================
        $this->command->info('');
        $this->command->info('🎉 Seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📊 SUMMARY:');
        $this->command->info('- Users: ' . User::count() . ' (1 admin + ' . (User::count() - 1) . ' users)');
        $this->command->info('- Rooms: ' . Room::count() . ' (' . Room::where('is_available', true)->count() . ' available, ' . Room::where('is_available', false)->count() . ' occupied)');
        $this->command->info('- Facilities: ' . Facility::count());
        $this->command->info('- Bookings: ' . Booking::count());
        $this->command->info('- Payments: ' . Payment::count());
        $this->command->info('');
        $this->command->info('🔐 LOGIN CREDENTIALS:');
        $this->command->info('Admin: admin@gardenia.com / admin123');
        $this->command->info('User 1: rere@gmail.com / password');
        $this->command->info('User 2: cihuy@gmail.com / password');
        $this->command->info('User 3: siti@gmail.com / password');
        $this->command->info('');
        $this->command->info('💰 PENDAPATAN BULAN INI:');
        $monthlyIncome = Payment::where('status', 'verified')
            ->whereMonth('verified_at', now()->month)
            ->whereYear('verified_at', now()->year)
            ->sum('amount');
        $this->command->info('Rp ' . number_format($monthlyIncome, 0, ',', '.') . ' (2 pembayaran terverifikasi)');
    }
}
