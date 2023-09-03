<?php
trait Webforms {

    public function gallery_manager_page(): void {
        ?>
        <div class="wrap">
            <h1>Gallery Manager</h1>
            <div class="intro">
                <p>Hello everyone. This is the spot where you upload your zip files full of your gorgeous ai based renders.</p>
                <p>It aims to do the following</p>
                <ol>
                    <li>Open your zip file</li>
                    <li>Read the meta data from your PNG's</li>
                    <li>Translate and upload those png's and their meta data into Wordpress</li>
                </ol>
            </div>
            <form method="POST" enctype="multipart/form-data" action="<?php echo esc_url(admin_url('admin-post.php?action=gallery_manager')); ?>">
                <input type="file" name="gallery_zip" accept=".zip">
                <?php wp_nonce_field('gallery_manager', 'gallery_manager_nonce'); ?>
                <input type="submit" class="button button-primary" name="submit" value="Upload Gallery">
            </form>
        </div>
        <?php
    }
}