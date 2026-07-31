<div class="card dashboard-card mb-4">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h5 class="fw-bold mb-1">

                    <i class="bi bi-moisture"></i>

                    Monitoring Kelembapan Tanah

                </h5>

                <small class="text-muted">

                    Nilai kelembapan tanah setiap zona secara realtime

                </small>

            </div>

            <div class="text-end">

                <div class="text-muted" style="font-size:13px;">

                    Rata-rata

                </div>

                <span
                    id="avgSoil"
                    class="badge bg-primary fs-6">

                    0%

                </span>

            </div>

        </div>

        <?php

        $zona = ["A","B","C","D"];

        foreach($zona as $z):

        ?>

        <div class="soil-item mb-4">

            <div class="d-flex justify-content-between align-items-center mb-2">

                <strong>

                    <i class="bi bi-geo-alt-fill text-success"></i>

                    Zona <?= $z ?>

                </strong>

                <span
                    id="nilai<?= $z ?>"
                    class="fw-bold">

                    0%

                </span>

            </div>

            <div class="progress" style="height:12px;">

                <div

                    id="pb<?= $z ?>"

                    class="progress-bar bg-success"

                    role="progressbar"

                    style="width:0%">

                </div>

            </div>

            <div class="d-flex justify-content-between mt-2">

                <small

                    id="status<?= $z ?>"

                    class="text-muted">

                    Menunggu Data...

                </small>

                <small

                    id="badge<?= $z ?>"

                    class="badge bg-secondary">

                    -

                </small>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

</div>