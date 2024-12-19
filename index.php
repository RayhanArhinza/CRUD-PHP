<?php
include 'conn.php';

// Get search keyword
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Pagination settings
$results_per_page = 5; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page, default to 1
$page = max(1, $page); // Ensure page is at least 1

// Calculate LIMIT for SQL query
$offset = ($page - 1) * $results_per_page;

// Count total number of products based on search
$total_products_query = "SELECT COUNT(*) as total FROM produk WHERE nama LIKE '%$search%'";
$total_products_result = $conn->query($total_products_query);
$total_products_row = $total_products_result->fetch_assoc();
$total_products = $total_products_row['total'];

// Calculate total pages
$total_pages = ceil($total_products / $results_per_page);

// Fetch products for current page based on search
$query = "SELECT * FROM produk WHERE nama LIKE '%$search%' LIMIT $offset, $results_per_page";
$result = $conn->query($query);
$i = $offset + 1;  // Adjust serial number based on current page


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        /* New overlay styles */
        #sidebarOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 40;
        }
    </style>
</head>
<body class="bg-gray-50 antialiased">
    <!-- Add this overlay div -->
    <div id="sidebarOverlay"></div>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-white shadow-xl border-r fixed left-0 top-0 bottom-0 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="px-6 py-8">
                <div class="flex items-center justify-between mb-10">
                    <span class="text-2xl font-bold text-blue-600">ProductHub</span>
                    <button id="closeSidebar" class="md:hidden text-gray-600 hover:text-red-600">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                
                <nav>
                    <div class="space-y-2">
                        <a href="#" class="flex items-center space-x-3 text-gray-700 p-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                            <i class="ri-dashboard-line text-xl"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 text-gray-700 p-2 rounded-lg bg-blue-50 text-blue-600">
                            <i class="ri-product-hunt-line text-xl"></i>
                            <span class="font-medium">Produk</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 text-gray-700 p-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                            <i class="ri-user-line text-xl"></i>
                            <span class="font-medium">Pelanggan</span>
                        </a>
                        <a href="#" class="flex items-center space-x-3 text-gray-700 p-2 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                            <i class="ri-settings-3-line text-xl"></i>
                            <span class="font-medium">Pengaturan</span>
                        </a>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen md:ml-64">
            <!-- Navbar -->
            <header class="bg-white shadow-sm sticky top-0 z-40">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <button id="mobileSidebarToggle" class="md:hidden mr-4 text-gray-600 hover:text-blue-600">
                            <i class="ri-menu-line text-2xl"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800">Daftar Produk</h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="text-gray-600 hover:text-blue-600">
                                <i class="ri-notification-3-line text-xl"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Search Dropdown -->
                <div id="mobileSearchDropdown" class="md:hidden px-4 pb-4 hidden">
                    <div class="relative">
                        <input type="text" placeholder="Cari produk..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="ri-search-line absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
            </header>

 <main class="p-6 bg-gray-50">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="p-6 border-b border-gray-300">
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                <h2 class="text-xl font-semibold text-gray-800 w-full text-center sm:text-left">
                    Produk Terdaftar
                </h2>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 w-full sm:w-auto">
                    <form method="GET" class="flex items-center w-full sm:w-auto space-x-2">
                        <input 
                            type="text" 
                            name="search" 
                            value="<?= htmlspecialchars($search) ?>" 
                            placeholder="Cari produk..." 
                            class="w-full sm:w-auto border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600"
                        />
                        <button 
                            type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition duration-300">
                            Cari
                        </button>
                    </form>
                    <button 
                        id="createBtn" 
                        class="w-full sm:w-auto bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition duration-300 flex items-center justify-center space-x-2">
                        <i class="ri-add-line"></i>
                        <span>Tambah</span>
                    </button>
                    <a 
                        href="export_excel.php" 
                        class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 transition duration-300 flex items-center justify-center space-x-2">
                        <i class="ri-file-download-line"></i>
                        <span>Export</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto text-sm">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="py-4 px-6 text-left">No</th>
                        <th class="py-4 px-6 text-left">Nama</th>
                        <th class="py-4 px-6 text-left hidden md:table-cell">Harga</th>
                        <th class="py-4 px-6 text-left hidden md:table-cell">Deskripsi</th>
                        <th class="py-4 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr class="border-b border-gray-300 hover:bg-gray-50">
                            <td class="py-4 px-6"><?= $i++; ?></td>
                            <td class="py-4 px-6">
                                <div class="font-semibold"><?= htmlspecialchars($row['nama']); ?></div>
                                <div class="md:hidden text-xs text-gray-500">
                                    Rp. <?= number_format($row['harga'], 0, ',', '.'); ?>
                                </div>
                            </td>
                            <td class="py-4 px-6 hidden md:table-cell">
                                Rp. <?= number_format($row['harga'], 0, ',', '.'); ?>
                            </td>
                            <td class="py-4 px-6 hidden md:table-cell"><?= htmlspecialchars($row['deskripsi']); ?></td>
                            <td class="py-4 px-6 text-center">
                            <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-2">
    <!-- Button for opening the Detail Modal, only visible on mobile -->
    <button class="detailBtn w-full sm:w-auto bg-blue-500 text-white px-3 py-1 rounded-xl hover:bg-blue-600 transition duration-300 sm:hidden" 
        data-id="<?= $row['id']; ?>" 
        data-nama="<?= $row['nama']; ?>" 
        data-harga="<?= $row['harga']; ?>" 
        data-deskripsi="<?= $row['deskripsi']; ?>">
        Detail
    </button>
    
    <!-- Button for opening the Edit modal -->
    <button class="editBtn w-full sm:w-auto bg-yellow-500 text-white px-3 py-1 rounded-xl hover:bg-yellow-600 transition duration-300" 
        data-id="<?= $row['id']; ?>" 
        data-nama="<?= $row['nama']; ?>" 
        data-harga="<?= $row['harga']; ?>" 
        data-deskripsi="<?= $row['deskripsi']; ?>">
        Edit
    </button>
    
    <a href="delete_product.php?id=<?= $row['id']; ?>" 
    class="w-full sm:w-auto bg-red-500 text-white px-3 py-1 rounded-xl hover:bg-red-600 transition duration-300 text-center delete-link">
    Hapus
</a>

</div>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-300 bg-gray-50">
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                <p class="text-sm text-gray-700 text-center sm:text-left">
                    Menampilkan 
                    <span class="font-semibold text-blue-600"><?= $offset + 1 ?></span> - 
                    <span class="font-semibold text-blue-600"><?= min($offset + $results_per_page, $total_products) ?></span> 
                    dari 
                    <span class="font-semibold text-blue-600"><?= $total_products ?></span> produk
                </p>

                <div class="w-full sm:w-auto flex justify-center sm:block">
                    <nav class="inline-flex rounded-lg shadow-md" aria-label="Pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=1" class="inline-flex items-center px-3 py-2 rounded-l-lg border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-blue-100 hover:text-blue-600 transition duration-300">
                                <i class="ri-arrow-left-double-line"></i>
                            </a>
                            <a href="?page=<?= $page - 1 ?>" class="inline-flex items-center px-3 py-2 rounded-l-lg border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-blue-100 hover:text-blue-600 transition duration-300">
                                <i class="ri-arrow-left-line"></i>
                            </a>
                        <?php endif; ?>

                        <?php 
                        $start = max(1, $page - 2);
                        $end = min($total_pages, $page + 2);
                        for ($p = $start; $p <= $end; $p++): ?>
                            <?php if ($p == $page): ?>
                                <span aria-current="page" class="z-10 bg-blue-600 text-white inline-flex items-center px-4 py-2 border text-sm font-medium rounded-md">
                                    <?= $p ?>
                                </span>
                            <?php else: ?>
                                <a href="?page=<?= $p ?>" class="inline-flex items-center px-4 py-2 border text-sm font-medium text-gray-500 bg-white hover:bg-blue-100 hover:text-blue-600 transition duration-300">
                                    <?= $p ?>
                                </a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="inline-flex items-center px-3 py-2 rounded-lg border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-blue-100 hover:text-blue-600 transition duration-300">
                                <i class="ri-arrow-right-line"></i>
                            </a>
                            <a href="?page=<?= $total_pages ?>" class="inline-flex items-center px-3 py-2 rounded-r-lg border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-blue-100 hover:text-blue-600 transition duration-300">
                                <i class="ri-arrow-right-double-line"></i>
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</main>


        </div>
    </div>
    <!-- Modal Edit Produk -->
<div id="editModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Edit Produk</h2>
            <button id="closeEditModal" class="text-gray-500 hover:text-red-600">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <form action="edit_product.php" method="POST">
            <input type="hidden" name="id" id="editProductId">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                    <input type="text" name="nama" id="editNama" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                    <input type="number" name="harga" id="editHarga" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="editDeskripsi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancelEditModal" class="px-4 py-2 text-gray-500 hover:bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Modal Detail Produk -->
<div id="detailModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Detail Produk</h2>
            <button id="closeDetailModal" class="text-gray-500 hover:text-red-600">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                <p id="detailNama" class="text-gray-800"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                <p id="detailHarga" class="text-gray-800"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <p id="detailDeskripsi" class="text-gray-800"></p>
            </div>
            <div class="flex justify-end">
                <button type="button" id="closeDetailButton" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Konfirmasi Penghapusan</h2>
            <button id="closeDeleteModal" class="text-gray-500 hover:text-red-600">
                <i class="ri-close-line text-2xl"></i>
            </button>
        </div>
        <p class="text-sm text-gray-600 mb-6">Apakah Anda yakin ingin menghapus produk ini?</p>
        <div class="flex justify-end space-x-4">
            <button id="cancelDelete" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Batal</button>
            <button id="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Hapus</button>
        </div>
    </div>
</div>



    <!-- Modal Create Produk (remains the same as previous version) -->
    <div id="createModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Tambah Produk Baru</h2>
                <button id="closeCreateModal" class="text-gray-500 hover:text-red-600">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            <form action="create_product.php" method="POST">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                        <input type="text" name="nama" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                        <input type="number" name="harga" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" id="cancelCreateModal" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<!-- jQuery CDN -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
       $(document).ready(function() {
    // Mobile Sidebar Toggle
    $('#mobileSidebarToggle').click(function() {
        $('#sidebar').removeClass('-translate-x-full');
        $('#sidebarOverlay').show();
    });

    // Close Sidebar
    $('#closeSidebar').click(function() {
        $('#sidebar').addClass('-translate-x-full');
        $('#sidebarOverlay').hide();
    });

    // Close Sidebar when clicking on Overlay
    $('#sidebarOverlay').click(function() {
        $('#sidebar').addClass('-translate-x-full');
        $(this).hide();
    });

    // Modal Handling for Create
    $('#createBtn').click(function() {
        $('#editModal, #detailModal').addClass('hidden');
        $('#createModal').removeClass('hidden');
    });

    $('#closeCreateModal, #cancelCreateModal').click(function() {
        $('#createModal').addClass('hidden');
    });

    // Open Edit Modal
    $('.editBtn').click(function() {
        $('#createModal, #detailModal').addClass('hidden');
        
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const harga = $(this).data('harga');
        const deskripsi = $(this).data('deskripsi');

        $('#editProductId').val(id);
        $('#editNama').val(nama);
        $('#editHarga').val(harga);
        $('#editDeskripsi').val(deskripsi);

        $('#editModal').removeClass('hidden');
        $('#sidebarOverlay').removeClass('hidden');
    });

    // Close Edit Modal
    $('#closeEditModal, #sidebarOverlay, #cancelEditModal').click(function() {
        $('#editModal, #sidebarOverlay').addClass('hidden');
    });

    // Handle Detail Modal
    $('.detailBtn').click(function() {
        const detailNama = $(this).data('nama');
        const detailHarga = 'Rp. ' + Number($(this).data('harga')).toLocaleString('id-ID');
        const detailDeskripsi = $(this).data('deskripsi');

        $('#detailNama').text(detailNama);
        $('#detailHarga').text(detailHarga);
        $('#detailDeskripsi').text(detailDeskripsi);

        $('#detailModal').removeClass('hidden');
    });

    $('#closeDetailModal, #closeDetailButton').click(function() {
        $('#detailModal').addClass('hidden');
    });

    $('#detailModal').click(function(e) {
        if (e.target === this) {
            $(this).addClass('hidden');
        }
    });

    // Delete Confirmation Modal
    $('a.delete-link').click(function(event) {
        event.preventDefault();

        const deleteUrl = $(this).attr('href');

        $('#confirmDelete').off('click').click(function() {
            window.location.href = deleteUrl;
        });

        $('#deleteConfirmationModal').removeClass('hidden');
    });

    $('#cancelDelete, #closeDeleteModal').click(function() {
        $('#deleteConfirmationModal').addClass('hidden');
    });

    $('#deleteConfirmationModal').click(function(e) {
        if (e.target === this) {
            $(this).addClass('hidden');
        }
    });

    // Notification Management
    const notificationButton = $('button .ri-notification-3-line').closest('button');
    const notificationContainer = $('<div id="notificationDropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 hidden"></div>');
    notificationButton.parent().css('position', 'relative').append(notificationContainer);

    function addNotification(type, message) {
        let notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
        notifications.unshift({
            id: Date.now(),
            type: type,
            message: message,
            timestamp: new Date().toLocaleString()
        });
        notifications = notifications.slice(0, 10);
        localStorage.setItem('notifications', JSON.stringify(notifications));
        updateNotificationBadge();
    }

    function updateNotificationBadge() {
        const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
        const badge = notificationButton.find('.notification-badge');
        
        if (notifications.length > 0) {
            if (badge.length === 0) {
                notificationButton.append('<span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">' + notifications.length + '</span>');
            } else {
                badge.text(notifications.length);
            }
        } else {
            badge.remove();
        }
    }

    function renderNotificationDropdown() {
        const notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
        notificationContainer.html(`
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-semibold text-gray-800">Notifications</h3>
                <button id="clearNotifications" class="text-sm text-red-500 hover:text-red-700">Clear All</button>
            </div>
            <div class="max-h-96 overflow-y-auto">
                ${notifications.length > 0 ? notifications.map(notification => `
                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium ${getNotificationClass(notification.type)}">${getNotificationText(notification.type)}</span>
                            <span class="text-xs text-gray-500">${notification.timestamp}</span>
                        </div>
                        <p class="text-sm text-gray-700">${notification.message}</p>
                    </div>
                `).join('') : '<div class="p-4 text-center text-gray-500">Tidak ada notifikasi</div>'}
            </div>
        `);

        $('#clearNotifications').click(function() {
            localStorage.removeItem('notifications');
            renderNotificationDropdown();
        });
    }

    function getNotificationClass(type) {
        switch (type) {
            case 'create': return 'text-green-600';
            case 'update': return 'text-blue-600';
            case 'delete': return 'text-red-600';
            default: return 'text-gray-600';
        }
    }

    function getNotificationText(type) {
        switch (type) {
            case 'create': return 'Produk Ditambahkan';
            case 'update': return 'Produk Diperbarui';
            case 'delete': return 'Produk Dihapus';
            default: return 'Aktivitas';
        }
    }

    notificationButton.click(function(e) {
        e.stopPropagation();
        notificationContainer.toggleClass('hidden');
        renderNotificationDropdown();
    });

    $(document).click(function(e) {
        if (!notificationContainer.is(e.target) && !notificationButton.is(e.target)) {
            notificationContainer.addClass('hidden');
        }
    });

    // Notification for Create, Edit, Delete actions
    $('#createModal form').submit(function() {
        const nama = $(this).find('input[name="nama"]').val();
        addNotification('create', `Produk "${nama}" berhasil ditambahkan`);
    });

    $('#editModal form').submit(function() {
        const nama = $(this).find('#editNama').val();
        addNotification('update', `Produk "${nama}" berhasil diperbarui`);
    });

    $('a.delete-link').click(function() {
        const nama = $(this).closest('tr').find('.font-semibold').text();
        addNotification('delete', `Produk "${nama}" berhasil dihapus`);
    });

    updateNotificationBadge();
});

    </script>
</body>
</html>