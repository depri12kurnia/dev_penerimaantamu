<!-- =================== FAQ SECTION =================== -->
<section class="page-section container" style="padding-top: 150px; padding-bottom: 60px;">
    <div class="text-center mb-5">
        <h2 class="fw-extrabold text-dark">Pertanyaan & Jawaban (FAQ)</h2>
        <p class="text-secondary">Temukan informasi cepat seputar prosedur penerimaan kunjungan kami</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="accordion accordion-premium" id="faqPremium">
                <?php if (!empty($faqs)): ?>
                    <?php foreach ($faqs as $row): ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#f<?= htmlspecialchars($row->id); ?>">
                                    <?= htmlspecialchars($row->question); ?>
                                </button>
                            </h2>
                            <div id="f<?= htmlspecialchars($row->id); ?>" class="accordion-collapse collapse" data-bs-parent="#faqPremium">
                                <div class="accordion-body">
                                    <?= $row->answer; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>