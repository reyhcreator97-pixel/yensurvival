<?= $this->extend('templates/index'); ?>
<?= $this->section('page-content'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Video Tutorial</h1>
    <div class="card shadow mb-3">
        <div class="card-body">
            <form class="form-inline">
                <label class="mr-2 text-center">Contact Service :</label>
                <?php
                $username = urlencode(user()->username ?? 'User');
                $msg = "Halo Admin,%0ASaya%20*$username*%20ingin%20bertanya%20soal%20Yen Survival";
                $waLink = "https://wa.me/" . 628557663472 . "?text=" . $msg;
                ?>
                <a href="<?= $waLink; ?>" target="_blank" class="btn btn-success btn-sm">
                    WA ADMIN
                </a>
            </form>
        </div>
    </div>



    <div class="row">
        <?php foreach ($videos as $v): ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-left-primary h-100">
                    <div class="card-body">
                        <h5 class="font-weight-bold"><?= esc($v['title']) ?></h5>

                        <?php
                        // Ganti domain ke youtube-nocookie
                        $url = str_replace('youtube.com/embed', 'youtube-nocookie.com/embed', trim($v['video_url']));
                        // Tambah parameter aman
                        $url .= (strpos($url, '?') === false ? '?' : '&') . 'rel=0&modestbranding=1&controls=1&iv_load_policy=3';
                        ?>

                        <div class="embed-responsive embed-responsive-16by9 video-container my-2">
                            <iframe
                                class="embed-responsive-item video-frame"
                                src="<?= esc($url) ?>"
                                frameborder="0"
                                allow="autoplay; encrypted-media; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                            <!-- overlay -->
                            <div class="video-overlay"></div>
                        </div>

                        <small class="text-muted d-block mb-1">
                            Kategori: <?= esc($v['category']) ?>
                        </small>
                        <p class="mb-0"><?= esc($v['description']) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($videos)): ?>
            <div class="col-12 text-center text-muted">Belum ada video tutorial.</div>
        <?php endif; ?>
    </div>
</div>

<style>
    .video-container {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
    }

    .video-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 40px;
        /* area tombol YouTube */
        background: white;
        opacity: 0;
        z-index: 10;
        pointer-events: none;
    }

    /* Disable klik di tombol YouTube */
    .video-frame {
        pointer-events: auto;
    }
</style>

<?= $this->endSection(); ?>