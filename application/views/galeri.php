<section id="galeri" class="page-section container" style="padding-top: 150px; padding-bottom: 60px;">
    <div class="d-flex flex-col flex-md-row justify-content-between align-items-start align-items-md-center mb-5 gap-4">
        <div>
            <h2 class="fw-extrabold text-dark mb-1">Galeri & Sarana Kampus</h2>
            <p class="text-secondary mb-0">Visualisasi infrastruktur penunjang akademik & kemahasiswaan</p>
        </div>

        <div class="input-group" style="max-width: 320px;">
            <span class="input-group-text bg-white border-end-0" style="border-radius: 50px 0 0 50px; border-color: #E2E8F0;">
                <i class="fas fa-search text-muted"></i>
            </span>
            <input type="text" id="gallerySearch" class="form-control border-start-0" placeholder="Cari galeri..." style="border-radius: 0 50px 50px 0; border-color: #E2E8F0;">
        </div>
    </div>

    <ul class="nav nav-pills mb-4 gap-2 border-bottom pb-3 flex-nowrap overflow-x-auto text-nowrap" id="galleryTabs">
        <li class="nav-item">
            <button class="nav-link category-btn active bg-kemenkes-teal rounded-pill text-white px-4 py-2" data-filter="all" style="font-weight: 700;">
                Semua Galeri
            </button>
        </li>
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $cat): ?>
                <li class="nav-item">
                    <button class="nav-link category-btn text-secondary rounded-pill px-4 py-2" data-filter="cat-<?= $cat->id; ?>" style="font-weight: 600;">
                        <?= htmlspecialchars($cat->name, ENT_QUOTES, 'UTF-8'); ?>
                    </button>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" id="galleryGrid">
        <?php if (!empty($galleries)): ?>
            <?php foreach ($galleries as $gallery): ?>
                <div class="col gallery-item cat-<?= $gallery->category_id; ?>">
                    <div class="card card-custom h-100 overflow-hidden shadow-sm border-0">
                        <?php
                        $image_path = base_url('public/uploads/galleries/' . $gallery->images);
                        $fallback_image = 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=600&q=80';
                        $display_image = ($gallery->images && file_exists('./public/uploads/galleries/' . $gallery->images)) ? $image_path : $fallback_image;
                        ?>
                        <img src="<?= $display_image; ?>" alt="<?= htmlspecialchars($gallery->title, ENT_QUOTES, 'UTF-8'); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-dark mb-1 gallery-title"><?= htmlspecialchars($gallery->title, ENT_QUOTES, 'UTF-8'); ?></h6>
                            <?php if (!empty($gallery->description)): ?>
                                <small class="text-muted d-block text-truncate"><?= strip_tags($gallery->description); ?></small>
                            <?php else: ?>
                                <small class="text-muted d-block"><em>Tidak ada deskripsi</em></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 w-100" id="emptyState">
                <i class="fas fa-image fa-3x text-muted mb-3"></i>
                <p class="text-secondary">Belum ada konten galeri yang dipublikasikan.</p>
            </div>
        <?php endif; ?>

        <div class="col-12 text-center py-5 d-none w-100" id="noResultsState">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <p class="text-secondary">Data galeri atau sarana tidak ditemukan.</p>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const categoryButtons = document.querySelectorAll(".category-btn");
        const galleryItems = document.querySelectorAll(".gallery-item");
        const searchInput = document.getElementById("gallerySearch");
        const noResultsState = document.getElementById("noResultsState");

        let currentFilter = "all";
        let searchQuery = "";

        function filterGallery() {
            let visibleCount = 0;

            galleryItems.forEach(item => {
                const matchesCategory = (currentFilter === "all" || item.classList.contains(currentFilter));
                const titleElement = item.querySelector(".gallery-title");
                const matchesSearch = titleElement && titleElement.textContent.toLowerCase().includes(searchQuery);

                if (matchesCategory && matchesSearch) {
                    item.style.setProperty('display', 'block', 'important');
                    visibleCount++;
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });

            // Kontrol tampilan ketika data pencarian/filter kosong
            if (visibleCount === 0 && (galleryItems.length > 0)) {
                noResultsState.classList.remove("d-none");
            } else {
                noResultsState.classList.add("d-none");
            }
        }

        // Event Handler untuk Klik Button Kategori (Tabs)
        categoryButtons.forEach(button => {
            button.addEventListener("click", function() {
                // Reset style active button sebelumnya
                categoryButtons.forEach(btn => {
                    btn.classList.remove("active", "bg-kemenkes-teal", "text-white");
                    btn.classList.add("text-secondary");
                    btn.style.fontWeight = "600";
                });

                // Set style active ke button terpilih
                this.classList.add("active", "bg-kemenkes-teal", "text-white");
                this.classList.remove("text-secondary");
                this.style.fontWeight = "700";

                currentFilter = this.getAttribute("data-filter");
                filterGallery();
            });
        });

        // Event Handler untuk Live Search Bar Input
        searchInput.addEventListener("input", function() {
            searchQuery = this.value.toLowerCase().trim();
            filterGallery();
        });
    });
</script>